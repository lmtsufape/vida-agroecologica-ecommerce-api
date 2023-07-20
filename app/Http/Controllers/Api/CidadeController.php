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
        $cidades = Cidade::All();

        return response()->json(['cidades' => $cidades]);
    }


    public function store(StoreCidadeRequest $request)
    {
        $cidade = Cidade::create($request->all());
        return response()->json(['cidade' => $cidade]);

    }

}
