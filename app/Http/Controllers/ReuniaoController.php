<?php

namespace App\Http\Controllers;

use App\Models\Reuniao;
use App\Models\Associacao;
use Illuminate\Http\Request;

class ReuniaoController extends Controller
{
    public function index()
    {
        $reunioes = Reuniao::all();

        return response()->json(['reunioes'=> $reunioes]);
    }
    
    public function store(Request $request)
    {
        $reuniao = Reuniao::create($request->all());
        $associacao = Associacao::findOrFail($request->associao_id);
        $reuniao->associacao()->associate($associacao);
        
        return response()->json(['reuniao' => $reuniao]);
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
}

