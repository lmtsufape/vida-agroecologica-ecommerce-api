<?php

namespace App\Http\Controllers;

use App\Models\Reuniao;
use App\Models\Associacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReuniaoController extends Controller
{
    public function index()
    {
        $reunioes = Reuniao::all();

        return response()->json(['reunioes'=> $reunioes]);
    }

    public function store(Request $request)
    {
        if(Auth::user()->roles->whereIn('nome', ['administrador', 'presidente', 'secretario'])->first()){
            $reuniao = Reuniao::create($request->all());
            $associacao = Associacao::findOrFail($request->associacao_id);
            $reuniao->associacao()->associate($associacao);

            return response()->json(['reuniao' => $reuniao]);
        }else{
            $reuniao = Reuniao::create($request->except('status'))->refresh();
            $associacao = Associacao::findOrFail($request->associacao_id);
            $reuniao->associacao()->associate($associacao);
            return response()->json(['reuniao' => $reuniao]);
        }

    }

    public function show($id)
    {
        $reuniao = Reuniao::findOrFail($id);
        $reuniao->load('associacao');
        return response()->json(['reunioes'=> $reuniao]);
    }

    public function update(Request $request, $id)
    {
        $reuniao = Reuniao::findOrFail($id);
        $reuniao->status = $request->status;
        $reuniao->tipo = $request->tipo;
        $reuniao->data = $request->data;

        $reuniao->update();

        return response()->json(['reuniao' => $reuniao]);
    }

    public function destroy($id)
    {
        $reuniao = Reuniao::findOrFail($id);

        $reuniao->delete();

        return response()->noContent();
    }

    public function solicitarReuniao(Request $request)
    {
        $reuniao = Reuniao::create($request->except('status'));
        $reuniao->status = 'Em analise';
        $reuniao->save();

        return response()->json(['reuniao' => $reuniao]);
    }
}

