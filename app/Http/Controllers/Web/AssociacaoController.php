<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssociacaoRequest;
use App\Http\Requests\UpdateAssociacaoRequest;
use App\Models\Associacao;
use App\Models\Contato;

class AssociacaoController extends Controller
{

    public function index()
    {   
        return response()->json([
            'associacoes' => Associacao::with('presidentes')->get()
        ]);
    }

    public function store(StoreAssociacaoRequest $request)
    {
        $associacao = Associacao::create($request->only('nome', 'codigo', 'user_id'));

        $contato = new Contato([
            'email' => $request->input('email'),
            'telefone' => $request->input('telefone'),
        ]);

        $associacao->contato()->save($contato);
        $associacao->presidentes()->sync($request->input('presidente'));

        return response()->json(['associacao' => $associacao->load(['presidentes', 'contato'])]);
    }

    public function update(UpdateAssociacaoRequest $request)
    {
        $associacao = Associacao::findOrFail($request->input('associacao_id'));
        $associacao->update($request->except('_token', 'associacao_id'));

        $associacao->presidentes()->sync($request->input('presidente'));

        return response()->json(['associacao' => $associacao->load(['presidentes', 'contato'])]);
    }
}
