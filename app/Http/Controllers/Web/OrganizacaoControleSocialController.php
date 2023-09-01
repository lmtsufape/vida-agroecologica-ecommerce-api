<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganizacaoRequest;
use App\Http\Requests\UpdateOrganizacaoRequest;
use App\Models\Associacao;
use App\Models\Contato;
use App\Models\Endereco;
use App\Models\Estado;
use App\Models\OrganizacaoControleSocial;
use Illuminate\Support\Facades\DB;


class OrganizacaoControleSocialController extends Controller
{
    public function index($associacao_id)
    {
        $listaOcs = OrganizacaoControleSocial::where('associacao_id', $associacao_id)->get();
        $listaEstados = Estado::all();

        return response()->json(['Lista OCS'=>$listaOcs, 'Lista Estados' => $listaEstados]);
    }

    public function store(StoreOrganizacaoRequest $request)
    {
        DB::beginTransaction();
        $organizacao = OrganizacaoControleSocial::create($request->only('nome', 'cnpj', 'data_fundacao', 'associacao_id'));
        $organizacao->contato()->create($request->only('email', 'telefone'));
        $organizacao->endereco()->create($request->only('rua', 'cep', 'numero', 'bairro_id'));

        $associacao = Associacao::findOrFail($request->associacao_id);
        DB::commit();
        
        return response()->json(['associacao'=> $associacao->load('organizacoescontrolesocial.endereco')]);
    }

    public function update(UpdateOrganizacaoRequest $request, $id)
    {
        $organizacao = OrganizacaoControleSocial::findOrFail($id);
        $organizacao->update($request->only('nome','cnpj','data_fundacao'));
        $organizacao->endereco()->update($request->only('rua','numero','cep','bairro_id'));
        $organizacao->contato()->udpate($request->only('email','telefone'));

        return response()->json(['Organização' => $organizacao]);
    }
}
