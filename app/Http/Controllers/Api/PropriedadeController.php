<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Endereco;
use App\Models\Propriedade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropriedadeController extends Controller
{

    public function index($user_id){
        $propriedades = Propriedade::where('user_id',$user_id)->get();

        return response()->json($propriedades);
    }

    public function store(Request $request){
        $propriedade = new Propriedade();
        $endereco = new Endereco();

        $endereco->pais = $request->pais;
        $endereco->uf = $request->uf;
        $endereco->cidade = $request->cidade;
        $endereco->cep = $request->cep;
        $endereco->bairro = $request->bairro;
        $endereco->rua = $request->rua;
        $endereco->numero = $request->numero;
        $endereco->save();

        $propriedade->nome = $request->nome;
        $propriedade->user_id = auth()->user()->id;
        $propriedade->endereco_id = $endereco->id;
        $propriedade->save();

        return response()->json($propriedade);
    }

    public function update(Request $request){
        $propriedade = Propriedade::find($request->propriedade_id);
        $endereco = $propriedade->endereco;

        $endereco->pais = $request->pais;
        $endereco->uf = $request->uf;
        $endereco->cidade = $request->cidade;
        $endereco->cep = $request->cep;
        $endereco->bairro = $request->bairro;
        $endereco->rua = $request->rua;
        $endereco->numero = $request->numero;
        $endereco->update();

        $propriedade->nome = $request->nome;
        $propriedade->update();

        return response()->json($propriedade);
    }


}
