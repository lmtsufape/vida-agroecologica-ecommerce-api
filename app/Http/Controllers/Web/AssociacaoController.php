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
            'associacoes' => Associacao::with('presidentes', 'contato')->get()
        ]);
    }

    public function show($id)
    {
    $associacao = Associacao::where('id', $id)->with('presidentes', 'contato')->first();

    if (!$associacao) {
        return response()->json(['message' => 'Association not found'], 404);
    }

    return response()->json(['associacao' => $associacao]);
}

public function store(StoreAssociacaoRequest $request)
{

    DB::beginTransaction();

    $associacao = Associacao::create($request->only('nome', 'data_fundacao', 'user_id'));
    $associacao->contato()->create($request->only('email','telefone'));
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

    $codigo = $request->input('codigo');
    $existingAssociacao = Associacao::where('codigo', $codigo)->where('id', '!=', $id)->first();

    if ($existingAssociacao) {
        return response()->json(['message' => 'Código já registrado.'], 422);
    }

    $associacao = Associacao::where('id', $id)->first();

    if (!$associacao) {
        return response()->json(['message' => 'Associação não encontrada.'], 404);
    }

    $associacao->update($request->only('nome', 'codigo', 'presidente'));
    $associacao->contato()->update($request->except('_token', 'nome', 'codigo', 'presidente'));

    $associacao->presidentes()->sync($request->input('presidente'));

    return response()->json(['associacao' => $associacao->load(['presidentes', 'contato'])]);
}
}
