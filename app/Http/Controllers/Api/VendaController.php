<?php

namespace App\Http\Controllers\Api;

use App\Models\ItemVenda;
use App\Models\Produtor;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FormaPagamento;
use App\Models\Produto;
use App\Models\Venda;
use App\Notifications\EnviarEmailCompra;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $transacoes = $user->papel_type == 'Produtor'
            ? $user->papel->vendas->load('itens') //carrega os itens de cada venda
            : $user->papel->compras->load('itens');
        return response()->json(['transações' => $transacoes], 200);
    }

    public function store(Request $request)
    {
        $consumidor = Auth::user()->papel;

        DB::beginTransaction();
        $venda = new Venda();
        $venda->status = 'pendente';
        $venda->data_pedido = Carbon::now();
        $venda->consumidor()->associate($consumidor);
        $venda->produtor()->associate(Produtor::find($request->produtor));
        $formaPagamento = FormaPagamento::find($request->forma_pagamento);
        $venda->formaPagamento()->associate($formaPagamento);
        $venda->save();
        $subtotal = BigDecimal::of('0.00');
        $taxaEntrega = BigDecimal::of(Auth::user()->endereco->bairro->taxa);

        foreach ($request->produtos as $produto) {
            $prod = Produto::find($produto[0]); // índice 0: id do produto; índice 1: quantidade do produto.
            if ($produto[1] > $prod->estoque || !$prod->disponivel) {
                DB::rollBack();
                return response()->json(['error' => 'A quantidade solicitada ultrapassa o estoque, ou o produto não está a venda.'], 400);
            }
            $item = new ItemVenda();
            $item->tipo_unidade = $prod->tipo_unidade;
            $item->quantidade = $produto[1];
            $item->preco = BigDecimal::of($prod->preco);
            $item->venda()->associate($venda);
            $item->produto()->associate($prod);
            $item->save();
            $subtotal = $subtotal->plus(BigDecimal::of($prod->preco)->multipliedBy($produto[1])); // preço x quantidade
        }

        $venda->subtotal = $subtotal;
        $venda->taxa_entrega = $taxaEntrega;
        $venda->total = $subtotal->plus($taxaEntrega);
        $venda->save();

        DB::commit();
        $consumidor->user->notify(new EnviarEmailCompra($venda));
        return response()->json(['venda' => $venda], 200);
    }

    public function show($id)
    {
        $venda = Venda::findOrFail($id);
        $comprovante = $venda->comprovante_pagamento != null;
        return response()->json(['venda' => $venda->makeHidden('comprovante_pagamento'), 'comprovante' => $comprovante]);
    }

    // public function confirmarVenda($id) {
    //     $venda = Venda::findOrFail($id);

    // }

    public function anexarComprovante(Request $request, $id)
    {
        $request->validate(['comprovante' => 'required|file|mimes:jpeg,png,pdf|max:2048']);
        $venda = Venda::findOrFail($id);
        $conteudo = base64_encode(file_get_contents($request->file('comprovante')->path()));
        $venda->comprovante_pagamento = $conteudo;
        $venda->save();

        return response(base64_decode($venda->comprovante_pagamento))->header('Content-Type', $request->file('comprovante')->getMimeType());
    }   

    public function verComprovante($id)
    {
        $venda = Venda::findOrFail($id);
        $file = base64_decode($venda->comprovante_pagamento);

        if (!$file) {
            return response()->json(['error' => 'A venda não possui comprovante de pagamento'], 404);
        }
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_buffer($finfo, $file);
        finfo_close($finfo);
        return response($file)->header('Content-Type', $mimeType);
    }
}
