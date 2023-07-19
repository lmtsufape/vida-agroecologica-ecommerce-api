<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bairro;
use App\Models\Cidade;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBairroRequest;
class BairroController extends Controller
{
    public function index()
    {
        $todos = Bairro::all();

        return response()->json(['bairros' => $todos]);
    }

    public function store(StoreBairroRequest $request)
    {

        if(!Bairro::where( 'nome', $request->nome )->where( 'cidade_id', $request->cidade_id )->first( ))
        {
            $bairro = Bairro::create($request->all());
            $cidade = Cidade::findOrFail($request->cidade_id);
            $bairro->cidade()->associate($cidade);

            return response()->json(['bairro' => $bairro]);
        }else
        {
            return response()->json("bairro ja exitente");
        }

    }

}
