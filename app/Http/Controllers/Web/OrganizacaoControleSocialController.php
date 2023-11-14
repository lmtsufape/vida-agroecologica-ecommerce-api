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
    public function index()
    {
        $listaOcs = OrganizacaoControleSocial::with('associacao', 'endereco', 'contato', 'endereco.bairro')->get();

        return response()->json(['ocs'=>$listaOcs]);
    }

    public function show($id)
    {
        $ocs = OrganizacaoControleSocial::with('associacao', 'endereco', 'contato', 'endereco.bairro')->findOrFail($id);
        return response()->json(['ocs' => $ocs]);
    }

    public function store(StoreOrganizacaoRequest $request)
    {
        DB::beginTransaction();
        $organizacao = OrganizacaoControleSocial::create($request->only('nome', 'cnpj', 'associacao_id', 'user_id'));
        $organizacao->contato()->create($request->only('email', 'telefone'));
        $organizacao->endereco()->create($request->only('rua', 'cep', 'numero', 'bairro_id', 'complemento'));
        $organizacao->agricultores()->sync($request->input('agricultores_id'));

        DB::commit();
        return response()->json(['organizacao' => $organizacao->load(['agricultores', 'contato', 'endereco', 'associacao'])]);

       //return response()->json(['associacao'=> $associacao->load('organizacoescontrolesocial.endereco')]);
    }

    public function update(UpdateOrganizacaoRequest $request, $id)
    {
        $organizacao = OrganizacaoControleSocial::where('id', $id)->first();

        if (!$organizacao) {
            return response()->json(['message' => 'OCS não encontrada.'], 404);
        }
        $organizacao = OrganizacaoControleSocial::findOrFail($id);
        $organizacao->update($request->only('nome','cnpj'));
        $organizacao->endereco()->update($request->only('rua','numero','cep','bairro_id'));
        $organizacao->contato->update($request->only('email', 'telefone'));
        $organizacao->agricultores()->sync($request->input('agricultores_id'));

        return response()->json(['organizacao' => $organizacao->load(['agricultores', 'contato', 'endereco', 'associacao'])]);

    }
    public function destroy($id)
    {
        $organizacao = OrganizacaoControleSocial::findOrFail($id);

        $organizacao->delete();

        return response()->json($organizacao);
    }
}


// $associacao = Associacao::where('id', $id)->first();

// if (!$associacao) {
//     return response()->json(['message' => 'Associação não encontrada.'], 404);
// }

// $associacao->update($request->only('nome', 'data_fundacao'));
// $associacao->contato()->update($request->except('_token', 'nome', 'data_fundacao','presidentes_id', 'secretarios_id', 'cep', 'rua', 'numero', 'bairro_id'));
// $associacao->endereco()->update($request->except('_token', 'nome', 'data_fundacao','presidentes_id', 'secretarios_id', 'email','telefone'));

// $associacao->presidentes()->sync($request->input('presidentes_id'));
// $associacao->secretarios()->sync($request->input('secretarios_id'));

// return response()->json(['associacao' => $associacao->load(['presidentes', 'contato', 'secretarios', 'endereco'])]);
