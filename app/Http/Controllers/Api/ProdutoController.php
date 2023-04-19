<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProdutoRequest;
use App\Models\Banca;
use App\Models\Categoria;
use App\Models\Produto;
use App\Models\Produtor;
use App\Models\ProdutoTabelado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\File;
use Illuminate\Http\Response;
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
        $user = Auth::user();
        $banca = $user->papel->banca->id;
        $produtos = DB::table('produtos')
            ->where('banca_id', $banca)
            ->join('produtos_tabelados', 'produtos.produto_tabelado_id', '=', 'produtos_tabelados.id')
            ->select('produtos_tabelados.nome', 'produtos.*')
            ->get();

        if (!$produtos ||  sizeof($produtos) == 0) {
            return response()->json(['erro' => 'Não foi encontrar os produtos ou a banca está vazia'], 400);
        }

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
        $produtoTabelado = ProdutoTabelado::find($request->produto_id); // id do produto tabelado que se quer referenciar
        if ($produtoTabelado) {
            $user = Auth::user();
            $banca = $user->papel->banca;
            DB::beginTransaction();
            $produto = $banca->produtos()->make($request->all());
            $produto->produtoTabelado()->associate($produtoTabelado);
            $produto->save();
            if (!$produto) {
                return response()->json(['erro' => 'Não foi possível criar o produto'], 400);
            }
            $banca->save();
            $produto->banca;
            DB::commit();
        } else {
            return response()->json(['erro' => 'Nenhum produto tabelado corresponde ao id fornecido.'], 400);
        }
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
        $produto = Produto::find($id);
        if (!$produto) {
            return response()->json(['erro' => 'Não foi encontrar o produto'], 400);
        }
        return response()->json(['produto' => $produto], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreProdutoRequest $request, $id)
    {
        DB::beginTransaction();

        $produto = Produto::find($id);
        $produtoTabelado = ProdutoTabelado::find($request->produto_id);
        if (!$produto || !$produtoTabelado) {
            return response()->json(['erro' => 'Não foi encontrar o produto'], 404);
        }
        $produto->fill($request->all());
        $produto->produtoTabelado()->associate($produtoTabelado);
        $produto->save();
        $produto->banca;
        DB::commit();
        return response()->json(['produto' => $produto], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        $produto = Produto::find($id);
        $produto->delete();
        DB::commit();
        return response()->noContent();
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
        $tabelas['categorias'] = Categoria::where('nome', 'like', "%$busca%")->get();
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
        if (empty($nomeCategoria)) {
            return response()->json(['erro' => 'Nenhum critério de busca fornecido.'], 400);
        }

        $categoria = Categoria::where('nome', $nomeCategoria)->first();

        if (empty($categoria)) {
            return response()->json(['erro' => 'Nenhuma categoria encontrada.'], 404);
        }

        $produtos = $categoria->produtos;
        if ($produtos->isEmpty()) {
            return response()->json(['erro' => "Nenhum produto encontrado em $nomeCategoria."], 400);
        }

        return response()->json(['produtos' => $produtos], 200);
    }

    public function getImagem($id)
    {
        $imagem = ProdutoTabelado::find($id)->imagem;

        if (!$imagem || !file_exists(base_path($imagem->caminho))) {
            abort(404);
        }

        $file = new File(base_path($imagem->caminho));
        $type = Storage::mimeType(str_replace('storage' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR, '', $imagem->caminho)); // remove o "storage\app\" do caminho para que a função funcione corretamente

        $response = new Response(file_get_contents($file), 200);
        $response->header("Content-Type", $type);

        return $response;
    }
}
