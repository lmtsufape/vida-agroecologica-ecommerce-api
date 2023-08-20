<?php

namespace App\Http\Controllers\Api;

use App\Models\Banca;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\ProdutoTabelado;
use Illuminate\Http\Request;

class BuscaController extends Controller
{
    public function buscar_banca($nome)
    {
        $nomeBanca = Banca::where('nome', 'like', '%'.$nome.'%')->get();//pequisar independente do capslock

        return response()->json(['nome' => $nomeBanca]);
    }

    public function buscar_produto(Request $request)
    {
        $busca = $request->input('search');

        if (empty($busca)) {
            return response()->json(['erro' => 'Nenhum critério de busca fornecido.'], 400);
        }

        $tabelas = array();

        $tabelas['produtos'] = ProdutoTabelado::where('nome', 'like', "%$busca%")
             ->join('produtos', 'produtos_tabelados.id', '=', 'produtos.produto_tabelado_id')
             ->select('produtos_tabelados.nome', 'produtos.*')
             ->get();
        $tabelas['bancas'] = Banca::where('nome', 'like', "%$busca%")->get();
        $tabelas['categorias'] = ProdutoTabelado::where('categoria', 'like', "%$busca%")->distinct()->pluck('categoria');
        // $tabelas['produtores'] = User::whereHas('user', function ($query) use ($busca) {
        //     $query->where('name', 'like', "%$busca%");
        // })->get();

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

    public function buscar_vendedor($name)
    {

        $produtor = User::where('name', 'like', '%'.ucwords($name).'%')->whereHas('roles', function($query)
        {
            $query->where('nome', 'agricultor');
        })->get();
        if($produtor->count() == 0)
        {
            return response()->json(['erro' => "Nenhum produtor encontrado com o nome: $name"]);
        }
        return response()->json(['produtor' => $produtor]);

    }

    public function buscar_categoria(String $nomeCategoria)
    {
        $produtos = ProdutoTabelado::where('categoria', 'like', '%'.$nomeCategoria.'%')->get();

        if ($produtos->count() == 0)
        {
            return response()->json(['erro' => "Nenhum produto encontrado em $nomeCategoria."], 400);
        }

        return response()->json(['produtos' => $produtos], 200);
    }



}
