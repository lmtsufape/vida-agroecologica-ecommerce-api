<?php

namespace App\Http\Controllers\Api;

use App\Models\Banca;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\ProdutoTabelado;
use Illuminate\Http\Request;

class BuscaController extends Controller
{
    

    public function buscar_produto(Request $request)
    {
        $busca = $request->input('search');

        if (empty($busca)) {
            return response()->json(['erro' => 'Nenhum critério de busca fornecido.'], 400);
        }

        $tabelas = array();

        $tabelas['nomeBanca'] = Banca::where('nome', 'ilike', '%'.$busca.'%')->get();//pequisar independente do capslock
        $tabelas['nomeProdutor'] = User::where('name', 'ilike', '%'.$busca.'%')->whereHas('roles', function($query)
        {
            $query->where('nome', 'agricultor');
        })->get();
        $tabelas['produtos_categoria'] = ProdutoTabelado::where('categoria', 'ilike', '%'.$busca.'%')->get();

        $tabelas['produtos'] = ProdutoTabelado::where('nome', 'like', "%$busca%")
             ->join('produtos', 'produtos_tabelados.id', '=', 'produtos.produto_tabelado_id')
             ->select('produtos_tabelados.nome', 'produtos.*')
             ->get();
        $tabelas['categorias'] = ProdutoTabelado::where('categoria', 'like', "%$busca%")->distinct()->pluck('categoria');

        $tabelas = array_filter($tabelas, function ($valor)
        {
            return !$valor->isEmpty();
        });
        if (empty($tabelas))
        {
            return response()->json(['No Content' => 'Nenhum elemento encontrado.'], 204);
        }
        return response()->json($tabelas, 200);
    }



}
