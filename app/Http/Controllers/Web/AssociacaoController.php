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
            'associacoes' => Associacao::with('presidentes')->get()
        ]);
    }

    public function store(StoreAssociacaoRequest $request)
    {
        DB::beginTransaction();
        $associacao = Associacao::create($request->only('nome', 'codigo', 'user_id'));
        $associacao->contato()->update($request->only('email','telefone'));
        $associacao->presidentes()->sync($request->input('presidente'));
        DB::commit();
        
        return response()->json(['associacao' => $associacao->load(['presidentes', 'contato'])]);
    }

    public function update(UpdateAssociacaoRequest $request, $id)
    {
        $associacao = Associacao::findOrFail($id);
        $associacao->update($request->except('_token'));

        $associacao->presidentes()->sync($request->input('presidente'));

        return response()->json(['associacao' => $associacao->load(['presidentes', 'contato'])]);
    }
}
