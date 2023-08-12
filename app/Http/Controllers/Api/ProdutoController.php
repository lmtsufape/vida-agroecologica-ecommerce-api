<?php

namespace App\Http\Controllers\Api;

use App\Models\Banca;
use App\Models\Produto;
use App\Models\ProdutoTabelado;
use App\Http\Requests\StoreProdutoRequest;
use App\Http\Requests\UpdateProdutoRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $produtos = Produto::all();

        return response()->json(['produtos' => $produtos], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreProdutoRequest $request)
    {
        $validatedData = $request->validated();
        $banca = Banca::find($request->banca_id);

        $produto = $banca->produtos()->create($validatedData);

        return response()->json(['produto' => $produto], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $produto = Produto::findOrFail($id);

        return response()->json(['produto' => $produto], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProdutoRequest $request, $id)
    {
        $validatedData = $request->validated();
        $produto = Produto::find($id);

        $produto->update($validatedData);

        return response()->json(['produto' => $produto]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $produto = Produto::findOrfail($id);
        $this->authorize('delete', $produto);

        $produto->delete();

        return response()->noContent();
    }

    public function getTabelados()
    {
        $produtos = ProdutoTabelado::all();

        return response()->json(['produtos' => $produtos], 200);
    }

    public function getCategorias()
    {
        return response()->json(['categorias' => ProdutoTabelado::distinct()->pluck('categoria')], 200);
    }

    public function getImagem($id)
    {
        $imagem = ProdutoTabelado::findOrFail($id)->imagem;

        if (!$imagem || !Storage::exists($imagem->caminho)) {
            return response()->json(["error" => "imagem não encontrada."], 404);
        }

        $file = Storage::get($imagem->caminho);
        $mimeType = Storage::mimeType($imagem->caminho);

        return response($file)->header('Content-Type', $mimeType);
    }

    public function buscar(Request $request)
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
        $tabelas['produtores'] = Produtor::whereHas('user', function ($query) use ($busca) {
            $query->where('name', 'like', "%$busca%");
        })->get();

        $tabelas = array_filter($tabelas, function ($valor) {
            return !$valor->isEmpty();
        });
        if (empty($tabelas)) {
            return response()->json(['No Content' => 'Nenhum elemento encontrado.'], 204);
        }
        return response()->json($tabelas, 200);
    }

    public function buscarCategoria(String $nomeCategoria)
    {
        $produtos = ProdutoTabelado::where('categoria', $nomeCategoria)->get();

        if ($produtos->count() == 0) {
            return response()->json(['erro' => "Nenhum produto encontrado em $nomeCategoria."], 400);
        }

        return response()->json(['produtos' => $produtos], 200);
    }
}
