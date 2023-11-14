<?php

namespace App\Http\Controllers\Api;

use App\Events\PedidoConfirmadoEvent;
use App\Models\ItemVenda;
use App\Models\User;
use App\Services\FileService;
use Brick\Math\BigDecimal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVendaRequest;
use App\Models\Banca;
use App\Models\Endereco;
use App\Models\FormaPagamento;
use App\Models\Produto;
use App\Models\Venda;
use App\Notifications\EnviarEmailCompra;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendaController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index()
    {
        $this->authorize('viewAny', Venda::class);
        $vendas = Venda::orderBy('data_pedido', 'desc')->get();

        return response()->json(['vendas' => $vendas], 200);
    }

    public function store(StoreVendaRequest $request)
    {
        $validatedData = $request->validated();

        $consumidor = $request->user();
        $banca = Banca::findOrFail($validatedData['banca_id']);
        $enderecoEntrega = Endereco::find($request->endereco_id);
        $formaPagamento = FormaPagamento::findOrFail($validatedData['forma_pagamento_id']);

        if (!now()->isBetween($banca->horario_abertura, $banca->horario_fechamento, true)) {
            return response()->json(['error' => 'O pedido não pode ser feito fora do horário de funcionamento da banca.'], 400);
        }

        if (!$formaPagamento->bancas()->where('banca_id', $banca->id)->exists()) {
            return response()->json(['error' => 'A banca não aceita ' . $formaPagamento->tipo]);
        }

        if ($validatedData['tipo_entrega'] == 'entrega') {
            if (!$enderecoEntrega) {
                return response()->json(['error' => 'Endereço de entrega não informado.'], 400);
            }

            if (!$banca->bairros_info_entrega()->where('bairro_id', $enderecoEntrega->bairro_id)->exists()) {
                return response()->json(['error' => 'Esta banca não faz entrega no endereço selecionado.'], 400);
            }
        }

        DB::beginTransaction();
        $venda = new Venda();
        $venda->status = 'pedido realizado';
        $venda->tipo_entrega = $validatedData['tipo_entrega'];
        $venda->data_pedido = now();
        $venda->consumidor()->associate($consumidor);
        $venda->banca()->associate($banca);
        $venda->formaPagamento()->associate($formaPagamento);
        $subtotal = BigDecimal::of('0.00');
        $taxaEntrega = BigDecimal::of('0.00');
        $venda->save();

        if ($validatedData['tipo_entrega'] == 'entrega') {
            $venda->enderecoEntrega()->create($enderecoEntrega->getAttributes());
            $taxaEntrega = BigDecimal::of($banca->bairros_info_entrega()->where('bairro_id', $enderecoEntrega->bairro_id)->first()->pivot->taxa);
        }


        $itens = [];
        $produtosForaEstoque = [];

        foreach ($validatedData['produtos'] as $produto) {
            $prod = Produto::findOrFail($produto[0]); // índice 0: id do produto; índice 1: quantidade do produto.

            if (!$prod->disponivel) {
                DB::rollBack();

                return response()->json(['error' => 'O produto não está a venda.', 'produto' => $prod], 400);
            } elseif ($produto[1] > $prod->estoque) {

                array_push($produtosForaEstoque, $prod);
            } elseif ($banca->id != $prod->banca->id) {
                DB::rollBack();
                
                return response()->json(['error' => 'O produto não pertence à banca especificada.', 'produto' => $prod], 400);
            }

            $item = new ItemVenda();
            $item->tipo_medida = $prod->tipo_medida;
            $item->quantidade = $produto[1];
            $item->preco = BigDecimal::of($prod->preco);
            $item->venda()->associate($venda);
            $item->produto()->associate($prod);
            $item->save();
            array_push($itens, $item->makeHidden('venda'));
            $subtotal = $subtotal->plus(BigDecimal::of($prod->preco)->multipliedBy($produto[1])); // preço x quantidade
            $prod->estoque = $prod->estoque - $produto[1] <= 0 ? 0 : $prod->estoque - $produto[1];
            $prod->save();
        }
        if ($subtotal->isLessThan($banca->preco_minimo) && $validatedData['tipo_entrega'] == 'entrega') {
            DB::rollBack();
            return response()->json(['error' => 'O valor da compra é inferior ao valor mínimo para entrega.']);
        }

        $venda->subtotal = $subtotal;
        $venda->taxa_entrega = $taxaEntrega;
        $venda->total = $subtotal->plus($taxaEntrega);
        $venda->save();
        DB::commit();

        return response()->json(['venda' => $venda->load(['consumidor', 'banca', 'enderecoEntrega', 'formaPagamento', 'itens.produto']), 'produtos fora de estoque' => $produtosForaEstoque], 201);
    }

    public function show($id)
    {
        $venda = Venda::findOrFail($id);
        $this->authorize('view', $venda);

        return response()->json(['venda' => $venda], 200);
    }

    public function confirmarVenda(Request $request, $id)
    {
        $request->validate(['confirmacao' => 'required|boolean']);
        $venda = Venda::findOrFail($id);
        $this->authorize('confirmarVenda', $venda);

        if ($venda->status != 'pedido realizado') {
            return response()->json(['error' => 'Esta venda já foi confirmada ou recusada.'], 400);
        }

        if ($request->confirmacao) {
            DB::beginTransaction();
            $venda->status = 'pagamento pendente';
            $venda->data_confirmacao = now();
            $venda->save();
            DB::commit();

            event(new PedidoConfirmadoEvent($venda));

            return response()->json(['success' => 'O pedido foi confirmado.', 'pedido' => $venda->refresh()]);
        } else {
            return $this->cancelarCompra($venda->id);
        }
    }

    public function cancelarCompra($id)
    {
        $user = Auth::user();
        $venda = Venda::findOrFail($id);
        $this->authorize('cancelarCompra', $venda);

        if ($venda->status != 'pedido realizado' && $venda->status != 'pagamento pendente' && $venda->status != 'comprovante anexado') {
            return response()->json(['error' => 'Esta venda não pode mais ser cancelada.'], 400);
        }

        DB::beginTransaction();
        foreach ($venda->itens as $item) {
            $produto = $item->produto;
            $produto->estoque += $item->quantidade;
            $produto->save();
        }

        $status = '';

        if ($user) {
            switch ($user->id) {
                case $venda->consumidor_id:
                    $status = 'pedido cancelado';
                    break;
                case $venda->banca->agricultor_id:
                    if ($venda->status == 'pedido realizado') {
                        $status = 'pedido recusado';
                    } elseif ($venda->status == 'comprovante anexado') {
                        $status = 'comprovante recusado';
                    }
                    break;
            }
        } else {
            $status = 'pagamento expirado';
        }

        $venda->status = $status;
        $venda->data_cancelamento = now();
        $venda->save();
        DB::commit();

        return response()->json(['success' => 'Pedido cancelado', 'pedido' => $venda->refresh()], 200);
    }

    public function anexarComprovante(Request $request, $id)
    {
        $request->validate(['comprovante' => 'required|file|mimes:jpeg,png,pdf|max:2048']); // Aceita somente jpeg, png e pdf
        
        $venda = Venda::findOrFail($id);
        $this->authorize('anexarComprovante', $venda);

        if ($venda->status != 'pagamento pendente' && $venda->status != 'comprovante anexado') {
            return response()->json(['error' => 'Não é possível anexar comprovante a esta venda.'], 400);
        }

        $comprovante = $request->file('comprovante');
        if ($venda->comprovante) {
            $this->fileService->updateFile($comprovante, $venda->comprovante);
        } else {
            $this->fileService->storeFile($comprovante, $venda, 'comprovantes');

        }

        DB::beginTransaction();
        $venda->status = 'comprovante anexado';
        $venda->data_pagamento = now();
        $venda->save();
        DB::commit();

        return response()->json(['success' => 'Comprovante enviado.'], 200);
    }

    public function verComprovante($id)
    {
        $venda = Venda::findOrFail($id);
        $this->authorize('verComprovante', $venda);

        $dados = $this->fileService->getFile($venda->comprovante);

        return response($dados['file'])->header('Content-Type', $dados['$mimeType']);
    }

    public function marcarEnviado($id)
    {
        $venda = Venda::findOrFail($id);
        $this->authorize('marcarEnviado', $venda);

        if ($venda->status != 'comprovante anexado') {
            return response()->json(['error' => 'Esta venda não pode ser marcada como "enviada".'], 400);
        }

        DB::beginTransaction();
        $venda->status = $venda->tipo_entrega == 'entrega' ? 'enviado' : 'aguardando retirada';
        $venda->data_envio = now();
        $venda->save();
        DB::commit();

        $venda->consumidor->notify(new EnviarEmailCompra($venda));

        return response()->json(['success' => 'A compra foi marcada como enviada.'], 200);
    }

    public function marcarEntregue($id)
    {
        $venda = Venda::findOrFail($id);
        $this->authorize('marcarEntregue', $venda);

        if ($venda->status != 'enviado' && $venda->status != 'aguardando retirada') {
            return response()->json(['error' => 'Esta venda não pode ser marcada como "entregue".'], 400);
        }

        DB::beginTransaction();
        $venda->status = 'entregue';
        $venda->data_entrega = now();
        $venda->save();
        DB::commit();

        return response()->json(['success' => 'A compra foi marcada como entregue.'], 200);
    }

    public function getCompras($consumidorId)
    {
        $user = User::findOrFail($consumidorId);
        $this->authorize('getTransacoes', [Venda::class, $user]);

        $compras = $user->compras;

        return response()->json(['compras' => $compras], 200);
    }

    public function getVendas($agricultorId)
    {
        $user = User::findOrFail($agricultorId);
        $this->authorize('getTransacoes', [Venda::class, $user]);

        $vendas = $user->vendas;

        return response()->json(['vendas' => $vendas], 200);
    }

    public function getBancaVendas($bancaId)
    {
        $banca = Banca::findOrFail($bancaId);
        $this->authorize('getTransacoes', [Venda::class, $banca->agricultor]);

        $vendas = $banca->vendas;

        return response()->json(['vendas' => $vendas], 200);

    }
}
