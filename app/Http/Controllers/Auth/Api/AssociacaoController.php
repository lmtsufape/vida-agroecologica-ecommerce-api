<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use App\Models\Associacao;
use App\Models\Contato;
use App\Models\User;
use Illuminate\Http\Request;

class AssociacaoController extends Controller
{
    public function store(Request $request)
    {
        $associacao = new Associacao();
        $contato = new Contato();

        $contato->email = $request->email;
        $contato->telefone = $request->telefone;
        $contato->save();

        $associacao->nome = $request->nome;
        $associacao->codigo = $request->codigo;
        $associacao->user_id = $request->presidente;
        $associacao->contato_id = $contato->id;
        $associacao->save();

        return response()->json([
            'data' => [
                'status' => "Associação Criada com Sucesso!"
            ]
        ]);
    }

    public function update(Request $request)
    {
        $associacao = Associacao::find($request->associacao_id);
        $contato = $associacao->contato;

        $contato->email = $request->email;
        $contato->telefone = $request->telefone;
        $contato->update();

        $associacao->nome = $request->nome;
        $associacao->codigo = $request->codigo;
        $associacao->user_id = $request->presidente;
        $associacao->update();

        return response()->json([
            'data' => [
                'status' => "Associação Atualizada com Sucesso!"
            ]
        ]);
    }

    public function index()
    {
        $associacoes = Associacao::all();
        $presidentes = User::where('tipo_usuario_id', 2)->get();

        return response()->json([
            'associacoes' => $associacoes,
            'presidentes' => $presidentes
        ]);
    }


}
