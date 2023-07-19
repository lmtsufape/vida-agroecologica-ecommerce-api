<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Associacao;
use App\Models\OrganizacaoControleSocial;
use App\Models\Contato;
use App\Models\Endereco;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class OrganizacaoControleSocialController extends Controller
{
    public function index($associacao_id){
        $lista_ocs = OrganizacaoControleSocial::where('associacao_id',$associacao_id)->get();

        return response()->json($lista_ocs);
    }

    public function store(Request $request){
        $ocs = new OrganizacaoControleSocial();
        $contato = new Contato();
        $endereco = new Endereco();

        $contato->email = $request->email;
        $contato->telefone = $request->telefone;
        $contato->save();

        $endereco->pais = $request->pais;
        $endereco->uf = $request->uf;
        $endereco->cidade = $request->cidade;
        $endereco->cep = $request->cep;
        $endereco->bairro = $request->bairro;
        $endereco->rua = $request->rua;
        $endereco->numero = $request->numero;
        $endereco->save();

        $ocs->nome = $request->nome;
        $ocs->representante = $request->representante;
        $ocs->cnpj = $request->cnpj;
        $ocs->data_fundacao = $request->data_fundacao;
        $ocs->contato_id = $contato->id;
        $ocs->endereco_id = $endereco->id;
        $ocs->associacao_id = $request->associacao_id;
        $ocs->save();

        return response()->json($ocs);
    }

    public function update(Request $request){
        $ocs = OrganizacaoControleSocial::find($request->ocs_id);
        $contato = $ocs->contato;
        $endereco = $ocs->endereco;

        $contato->email = $request->email;
        $contato->telefone = $request->telefone;
        $contato->update();

        $endereco->pais = $request->pais;
        $endereco->uf = $request->uf;
        $endereco->cidade = $request->cidade;
        $endereco->cep = $request->cep;
        $endereco->bairro = $request->bairro;
        $endereco->rua = $request->rua;
        $endereco->numero = $request->numero;
        $endereco->update();

        $ocs->nome = $request->nome;
        $ocs->representante = $request->representante;
        $ocs->cnpj = $request->cnpj;
        $ocs->data_fundacao = $request->data_fundacao;
        $ocs->update();

        return response()->json($ocs);
    }

}
