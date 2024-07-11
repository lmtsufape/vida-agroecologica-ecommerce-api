<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssociacaoRequest;
use App\Http\Requests\UpdateAssociacaoRequest;
use App\Models\Associacao;
use App\Models\Contato;
use Illuminate\Support\Facades\DB;

class AssociacaoController extends Controller
{

    public function index()
    {
        return response()->json([
            'associacoes' => Associacao::with('presidentes', 'secretarios', 'contato')->get()
        ]);
    }

    public function show($id)
    {
        $associacao = Associacao::findOrFail($id)->load('presidentes', 'secretarios', 'contato', 'endereco');
        return response()->json(['associacao' => $associacao]);
    }

    public function store(StoreAssociacaoRequest $request)
    {
        DB::beginTransaction();

        $associacaoData = $request->only('nome', 'data_fundacao', 'user_id');
        $associacao = Associacao::create($associacaoData);

        if ($request->filled('email') && $request->filled('telefone')) {
            $contatoData = $request->only('email', 'telefone');
            $associacao->contato()->create($contatoData);
        }

        $associacao->presidentes()->sync($request->input('presidentes_id'));
        $associacao->secretarios()->sync($request->input('secretarios_id'));
        $associacao->endereco()->create($request->only('rua', 'cep', 'numero', 'bairro_id', 'complemento'));

        DB::commit();

        return response()->json(['associacao' => $associacao->load(['presidentes', 'contato', 'endereco', 'secretarios'])]);
    }
    public function destroy($id)
    {
        $associacao = Associacao::findOrFail($id);

        $associacao->delete();

        return response()->json($associacao);
    }

    public function update(UpdateAssociacaoRequest $request, $id)
    {

        $associacao = Associacao::findOrFail($id);


        $associacao->update($request->only('nome', 'data_fundacao'));
        if ($request->has('email') && $request->has('telefone')) {
            $associacao->contato->update($request->only('email', 'telefone'));
        }
        $associacao->endereco()->update($request->except('_token', 'nome', 'data_fundacao','presidentes_id', 'secretarios_id', 'email','telefone'));

        $associacao->presidentes()->sync($request->input('presidentes_id'));
        $associacao->secretarios()->sync($request->input('secretarios_id'));

        return response()->json(['associacao' => $associacao->load(['presidentes', 'contato', 'secretarios', 'endereco'])]);
    }
}
