<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bairro;
use App\Models\Cidade;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBairroRequest;
use App\Http\Requests\UpdateBairroRequest;

class BairroController extends Controller
{
    public function index()
    {
        $todos = Bairro::with('cidade')->get();

        return response()->json(['bairros' => $todos]);
    }

    public function store(StoreBairroRequest $request)
    {
        $bairro = Bairro::create($request->all());
        $cidade = Cidade::findOrFail($request->cidade_id);
        $bairro->cidade()->associate($cidade);
        
        return response()->json(['bairro' => $bairro], 201);
    }

    public function update(UpdateBairroRequest $request, $id)
    {
        $bairro = Bairro::findOrFail($id);

        $bairro->update($request);

        return response()->json(['bairro' => $bairro], 200);
    }

    public function destroy($id)
    {
        $bairro = Bairro::findOrFail($id);

        $bairro->delete();

        return response()->noContent();
    }

    public function bairrosPorCidade($cidade_id)
    {
        $cidade = Cidade::findOrFail($cidade_id);
        $bairros = $cidade->bairros;

        return response()->json(['bairros' => $bairros], 200);
    }
}
