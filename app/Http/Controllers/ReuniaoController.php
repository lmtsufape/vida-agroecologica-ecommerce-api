<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReuniaoRequest;
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
    
    public function store(StoreReuniaoRequest $request)
    {
        $validatedData = $request->validated();

        $reuniao = Reuniao::create($validatedData);

        if ($request->user()->hasAnyRoles(['administrador', 'presidente', 'secretario'])) {
            $reuniao->status = 'Aprovada';
            $reuniao->save();
        }

        return response()->json(['reuniao' => $reuniao]);
    }

    public function update(StoreReuniaoRequest $request, $id)
    {
        $validatedData = $request->validated();
        $reuniao = Reuniao::findOrFail($id);

        $reuniao->update($validatedData);

        return response()->json(['reuniao' => $reuniao]);
    }

    public function destroy($id)
    {
        $reuniao = Reuniao::findOrFail($id);
        
        $reuniao->delete();

        return response()->noContent();
    }
}

