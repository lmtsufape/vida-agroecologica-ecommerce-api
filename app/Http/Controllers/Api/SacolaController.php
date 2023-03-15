<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\Sacola;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SacolaController extends Controller
{
    public function index()
    {
        $carrinho = Auth::user()->papel->carrinho;
        $sacolas = Sacola::where("sacolas.carrinho_id", "=", $carrinho->id)
        ->join("item_sacolas","item_sacolas.sacola_id","=","sacolas.id")
        ->join("produtos","produtos.id","=","produto_id")
		->select(DB::raw("produtos.preco as produto_preço"), "nome",DB::raw("item_sacolas.preco as preço"),
       "quantidade","produto_id","sacolas.total","sacolas.loja_id")->get();
    
        if($sacolas->isEmpty()){
            return response()->json(['erro' =>'O carrinho está vazio'],400);
        }

        return response()->json(['sacola'=>$sacolas]);
    }
    public function store(Request $request)
    {
        $carrinho = Auth::user()->papel->carrinho;
        $produto = Produto::find($request->produto_id);
        $sacola = $carrinho->sacolas()->where("loja_id","=",$produto->banca->id)->first();
        DB::beginTransaction();
        //verifica se já existe uma loja com aquele id, caso exista, faz apenas um update no preço total
        if(!$sacola){
            //cria sacola
            $sacola = $carrinho->sacolas()->create(['loja_id'=>$produto->banca->id,
            'total'=>0]);
        }
        $item = $sacola->itens->where('produto_id',$produto->id)->first();
        $valorCompra = $produto->preco*$request->quantidade;
        if($item){
            //item existe na sacola
            $item->update(['preço'=>$item->preco+$valorCompra,'quantidade'=>$item->quantidade+$request->quantidade]);
            $sacola->update(['total'=>($sacola->total+$valorCompra)]);
        }else{
            //item não existe na sacola
            $sacola->itens()->create(['quantidade'=>$request->quantidade,
            'produto_id'=>$produto->id,'preco'=>$valorCompra])->save();
            $sacola->update(['total'=>($sacola->total+$valorCompra)]);
        }
        DB::commit();
        return response()->json(['sacola'=>$sacola,'produto'=>$produto],201);
    }
    //remover um produto
    public function destroy($id)
    {
        $produto = Produto::find($id);
        $sacola = Auth::user()->papel->carrinho->sacolas()->where("loja_id","=",$produto->banca->id)->first();
        $sacola->total = $sacola->total-$produto->preco;
        $item = $sacola->itens->where('produto_id',$produto->id)->first();
        $item->delete();
        $sacola->save();
        if(!$sacola->itens()->exists()){
            $sacola->delete();
        }
        return response()->noContent();
    }
}
