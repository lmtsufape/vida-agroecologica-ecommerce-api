<?php


namespace App\Http\Controllers\Api;

use App\Models\Cidade;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCidadeRequest;

class CidadeController extends Controller
{
    public function index()
    {
        $cidades = Cidade::all();

        return response()->json(['cidades' => $cidades], 200);
    }

    public function store(StoreCidadeRequest $request)
    {
        $validatedData = $request->validated();
        $cidade = Cidade::create($validatedData);

        return response()->json(['cidade' => $cidade], 201);
    }
}
