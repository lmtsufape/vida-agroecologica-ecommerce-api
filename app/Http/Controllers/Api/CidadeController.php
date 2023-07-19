<?php


namespace App\Http\Controllers\Api;
use App\Models\Cidade;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CidadeController extends Controller
{
    public function index()
    {
        $cidades = Cidade::All();

        return response()->json(['cidades' => $cidades]);
    }


    public function store(Request $request)
    {
        if(!Cidade::where('nome', $request->nome)->first())
        {
            Cidade::create($request->all());
            return response()->json(['cidade' => $request->all()]);
        }else{
            return response()->json('Cidade ja cadastrada');
        }
    }

}
