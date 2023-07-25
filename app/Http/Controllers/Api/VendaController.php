<?php

namespace App\Http\Controllers\Api;

use App\Events\PedidoConfirmadoEvent;
use App\Models\ItemVenda;
use App\Models\Produtor;
use Brick\Math\BigDecimal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVendaRequest;
use App\Models\FormaPagamento;
use App\Models\Produto;
use App\Models\Venda;
use App\Notifications\EnviarEmailCompra;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class VendaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $transacoes = $user->papel_type == 'Produtor'
            ? $user->papel->vendas->load('itens')->load('consumidor.user.endereco') //carrega os itens de cada venda
            : $user->papel->compras->load('itens')->load('produtor.user.endereco');
        return response()->json(['transações' => $transacoes], 200);
    }

    public function store(StoreVendaRequest $request)
    {
        $consumidor = Auth::user()->papel;
        $produtor = Produtor::findOrFail($request->produtor);
        if (!now()->isBetween($produtor->banca->horario_abertura, $produtor->banca->horario_fechamento, true)) {
            return response()->json(['error' => 'O pedido não pode ser feito fora do horário de funcionamento da banca.'], 400);
        }
        if ($request->tipo_entrega == 'entrega' && !$produtor->banca->faz_entrega) {
            return response()->json(['error' => 'Esta banca não faz entregas.'], 400);
        }

        DB::beginTransaction();
        $venda = new Venda();
        $venda->status = 'pedido realizado';
        $venda->tipo_entrega = $request->tipo_entrega;
        $venda->data_pedido = now();
        $venda->consumidor()->associate($consumidor);
        $venda->produtor()->associate($produtor);
        $formaPagamento = FormaPagamento::find($request->forma_pagamento);
        $venda->formaPagamento()->associate($formaPagamento);
        $venda->save();
        $subtotal = BigDecimal::of('0.00');
        $taxaEntrega = BigDecimal::of('0.00');
        if ($request->tipo_entrega == 'entrega') {
            $taxaEntrega = BigDecimal::of(Auth::user()->endereco->bairro->taxa);
        }
        $itens = [];

        foreach ($request->produtos as $produto) {
            $prod = Produto::findOrFail($produto[0]); // índice 0: id do produto; índice 1: quantidade do produto.

            if ($produto[1] > $prod->estoque || !$prod->disponivel) {
                DB::rollBack();
                return response()->json(['error' => 'A quantidade solicitada ultrapassa o estoque, ou o produto não está a venda.', 'produto' => $prod], 400);
            } elseif ($request->produtor != $prod->banca->produtor->id) {
                DB::rollBack();
                return response()->json(['error' => 'O produto não pertence à banca do produtor especificado', 'produto' => $prod], 400);
            }

            $item = new ItemVenda();
            $item->tipo_unidade = $prod->tipo_unidade;
            $item->quantidade = $produto[1];
            $item->preco = BigDecimal::of($prod->preco);
            $item->venda()->associate($venda);
            $item->produto()->associate($prod);
            $item->save();
            array_push($itens, $item->makeHidden('venda'));
            $subtotal = $subtotal->plus(BigDecimal::of($prod->preco)->multipliedBy($produto[1])); // preço x quantidade
            $prod->estoque -= $produto[1];
            $prod->save();
        }
        if ($subtotal->isLessThan($produtor->banca->preco_minimo) && $request->tipo_entrega == 'entrega') {
            DB::rollBack();
            return response()->json(['error' => 'O valor da compra é inferior ao valor mínimo para entrega.']);
        }

        $venda->subtotal = $subtotal;
        $venda->taxa_entrega = $taxaEntrega;
        $venda->total = $subtotal->plus($taxaEntrega);
        $venda->save();
        DB::commit();
        return response()->json(['venda' => $venda->makeHidden('consumidor'), 'consumidor' => $consumidor->user->makeHidden('endereco'), 'endereço' => $consumidor->user->endereco, 'itens' => $itens], 200);
    }

    public function show($id)
    {
        $venda = Venda::findOrFail($id);
        $comprovante = $venda->comprovante_pagamento != null;
        return response()->json(['venda' => $venda->makeHidden('comprovante_pagamento'), 'comprovante' => $comprovante]);
    }

    public function confirmarVenda(Request $request, $id)
    {
        $request->validate(['confirmacao' => 'required|boolean']);
        $venda = Venda::findOrFail($id);
        $this->authorize('confirmarVenda', $venda);
        if ($venda->status != 'pedido realizado') {
            return response()->json(['error' => 'Esta venda já foi confirmada ou recusada'], 400);
        }

        if ($request->confirmacao) {
            DB::beginTransaction();
            $venda->status = 'pagamento pendente';
            $venda->data_confirmacao = now();
            $venda->save();
            event(new PedidoConfirmadoEvent($venda));
            DB::commit();
            return response()->json(['sucess' => 'O pedido foi confirmado.', 'pedido' => $venda->refresh()]);
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
            switch ($user->papel_type) {
                case 'Consumidor':
                    $status = 'pedido cancelado';
                    break;
                case 'Produtor':
                    if ($venda->status == 'pedido realizado') {
                        $status = 'pedido recusado';
                    } elseif ($venda->status == 'comprovante anexado') {
                        $status = 'comprovante recusado';
                    }
            }
        } else {
            $status = 'pagamento expirado';
        }
        $venda->status = $status;
        $venda->data_cancelamento = now();
        $venda->save();
        DB::commit();
        return response()->json(['sucess' => 'Pedido cancelado', 'pedido' => $venda->refresh()], 200);
    }

    public function anexarComprovante(Request $request, $id)
    {
        $venda = Venda::findOrFail($id);
        $this->authorize('anexarComprovante', $venda);
        if ($venda->status != 'pagamento pendente' && $venda->status != 'comprovante anexado') {
            return response()->json(['error' => 'Não é possível anexar comprovante a esta venda.'], 400);
        }

        $request->validate(['comprovante' => 'required|file|mimes:jpeg,png,pdf|max:2048']);
        $imagem = $request->file('comprovante');
        $nomeImagem = $venda->id . '.' . $imagem->getClientOriginalExtension();
        $comprovanteAntigo = glob(storage_path("app/public/uploads/imagens/comprovante/{$venda->id}.*"));

        $caminho = $imagem->storeAs('public/uploads/imagens/comprovante', $nomeImagem); // O caminho completo é storage/app/public/uploads/imagens/banca.

        if (!$caminho) {
            return response()->json(['erro' => 'Não foi possível fazer upload do comprovante'], 500);
        }

        DB::beginTransaction();
        $venda->comprovante()->updateOrCreate(['imageable_id' => $venda->id, 'imageable_type' => 'Venda'], ['caminho' => $caminho]);
        $venda->status = 'comprovante anexado';
        $venda->data_pagamento = now();
        $venda->save();
        DB::commit();

        foreach ($comprovanteAntigo as $arquivo) {
            if (basename($arquivo) != $nomeImagem) {
                File::delete($arquivo);
            }
        }

        return response()->json(['success' => 'Comprovante enviado.']);
    }

    public function verComprovante($id)
    {
        $venda = Venda::findOrFail($id);
        $this->authorize('verComprovante', $venda);
        $comprovante = $venda->comprovante;

        if (!$comprovante || !Storage::exists($comprovante->caminho)) {
            return response()->json(['error' => 'A venda não possui comprovante de pagamento'], 404);
        }

        $file = Storage::get($comprovante->caminho);
        $mimeType = Storage::mimeType($comprovante->caminho);
        return response($file)->header('Content-Type', $mimeType);
    }

    public function marcarEnviado($id)
    {
        $venda = Venda::findOrFail($id);
        $this->authorize('marcarEnviado', $venda);
        if ($venda->status != 'comprovante anexado') {
            return response()->json(['error' => 'Esta venda não pode ser marcada como "enviada".'], 400);
        }
        DB::beginTransaction();
        $venda->status = 'enviado';
        $venda->data_envio = now();
        $venda->save();
        DB::commit();
        $venda->consumidor->user->notify(new EnviarEmailCompra($venda));
        return response()->json(['success' => 'A compra foi marcada como enviada.'], 200);
    }

    public function marcarEntregue($id)
    {
        $venda = Venda::findOrFail($id);
        $this->authorize('marcarEntregue', $venda);
        if ($venda->status != 'enviado') {
            return response()->json(['error' => 'Esta venda não pode ser marcada como "entregue".'], 400);
        }
        DB::beginTransaction();
        $venda->status = 'entregue';
        $venda->data_entrega = now();
        $venda->save();
        DB::commit();
        return response()->json(['success' => 'A compra foi marcada como entregue.'], 200);
    }
}
