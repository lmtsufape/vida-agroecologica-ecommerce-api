<?php

namespace App\Http\Controllers\Api;

use App\Models\Banca;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\ProdutoTabelado;
use Illuminate\Http\Request;

class BuscaController extends Controller
{
    public function buscar(Request $request)
    {
        $busca = $request->input('search');

        if (empty($busca)) {
            return response()->json(['erro' => 'Nenhum critério de busca fornecido.'], 400);
        }

        $tabelas = array();

        $tabelas['banca'] = Banca::where('nome', 'ilike', '%'.$busca.'%')->get();//pequisar independente do capslock
        $tabelas['agricultor'] = User::where('name', 'ilike', '%'.$busca.'%')->whereHas('roles', function($query)
        {
            $query->where('nome', 'agricultor');
        })->get();
        $tabelas['categoria'] = ProdutoTabelado::where('categoria', 'ilike', '%'.$busca.'%')->distinct()->pluck('categoria');//havia o metodo get

        $tabelas['produtos'] = ProdutoTabelado::where('nome', 'ilike', "%$busca%")
             ->join('produtos', 'produtos_tabelados.id', '=', 'produtos.produto_tabelado_id')
             ->select('produtos_tabelados.nome', 'produtos.*')
             ->get();

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
