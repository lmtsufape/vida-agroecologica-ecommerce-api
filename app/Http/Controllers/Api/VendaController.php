<?php

namespace App\Http\Controllers\Api;

use App\Models\ItemVenda;
use App\Models\Produtor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FormaPagamento;
use App\Models\Produto;
use App\Models\Venda;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendaController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        $venda = new Venda();
        $venda->status = 'pendente';
        $venda->data_pedido = Carbon::now();
        $venda->consumidor()->associate(Auth::user()->papel);
        $venda->produtor()->associate(Produtor::find($request->produtor));
        $venda->formaPagamento()->associate(FormaPagamento::find($request->forma_pagamento));
        $venda->save();
        $total = 0;

        foreach ($request->produtos as $produto) {
            $prod = Produto::find($produto[0]); // índice 0: id do produto; índice 1: quantidade do produto.
            $item = new ItemVenda();
            $item->tipo_unidade = $prod->tipo_unidade;
            $item->quantidade = $produto[1];
            $item->preco = $prod->preco;
            $item->venda()->associate($venda);
            $item->produto()->associate($prod);
            $item->save();
            $total += $prod->preco * $produto[1];
        }

        $venda->total = $total;
        $venda->save();

        DB::commit();
        return response()->json(['venda' => $venda], 200);
    }
}
