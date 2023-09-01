<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganizacaoRequest;
use App\Http\Requests\UpdateOrganizacaoRequest;
use App\Models\Associacao;
use App\Models\Bairro;
use App\Models\Cidade;
use App\Models\Contato;
use App\Models\Endereco;
use App\Models\Estado;
use App\Models\OrganizacaoControleSocial;
use Illuminate\Http\Request;

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
        $organizacao = OrganizacaoControleSocial::create($request->only('nome', 'cnpj', 'data_fundacao', 'associacao_id'));

        $cidadeName = $request->input('cidade');
        $estadoId = $request->input('estado_id');
        $estado = Estado::find($estadoId);
        
        // $cidade = Cidade::where('nome', $cidadeName)->first();
        // if (!$cidade) {
        //     $cidade = Cidade::create(['nome' => $cidadeName, 'estado_id' => $estado->id]);
        // }

        // $cidade->estado_id = $estado->id;
        // $cidade->save();

        if ($request->has('bairro')) {
            $bairro = Bairro::create([
                'nome' => $request->input('bairro'),
                'cidade_id' => $cidade->id,
            ]);
            $bairroId = $bairro->id;
        } else {
            $bairroId = $request->input('bairro_id');
        }

        $endereco = new Endereco();
        $endereco->rua = $request->input('rua');
        $endereco->numero = $request->input('numero');
        $endereco->cep = $request->input('cep');
        $endereco->bairro_id = $bairroId;
        $endereco->addressable_type = 'App\Models\OrganizacaoControleSocial';
        $endereco->addressable_id = $organizacao->id;
        $endereco->save();

        $organizacao->endereco()->save($endereco);

        $contato = new Contato();
        $contato->email = $request->input('email');
        $contato->telefone = $request->input('telefone');
        $contato->contactable_type = 'App\Models\OrganizacaoControleSocial';
        $contato->contactable_id = $organizacao->id;
        $contato->save();

        $organizacao->contato()->save($contato);

        $associacao = Associacao::findOrFail($request->input('associacao_id'));

        return response()->json(['associacao'=> $associacao]);
    }

    public function update(UpdateOrganizacaoRequest $request, $id)
    {
        $organizacao = OrganizacaoControleSocial::findOrFail($id);
        $organizacao->nome = $request->input('nome');
        $organizacao->cnpj = $request->input('cnpj');
        $organizacao->data_fundacao = $request->input('data_fundacao');
        $organizacao->save();

        $cidadeName = $request->input('cidade');
        $estadoId = $request->input('estado_id');
        $estado = Estado::find($estadoId);

        $cidade = Cidade::where('nome', $cidadeName)->first();
        if (!$cidade) {
            $cidade = Cidade::create(['nome' => $cidadeName, 'estado_id' => $estado->id]);
        }

        $cidade->estado_id = $estado->id;
        $cidade->save();

        if ($request->has('bairro')) {
            $bairroName = $request->input('bairro');
            $bairro = Bairro::where('nome', $bairroName)->where('cidade_id', $cidade->id)->first();
            if ($bairro) {
                $bairroId = $bairro->id;
            } else {
                $bairro = Bairro::create(['nome' => $bairroName, 'cidade_id' => $cidade->id]);
                $bairroId = $bairro->id;
            }
        } else {
            $bairroId = $request->input('bairro_id');
        }

        $endereco = $organizacao->endereco;
        $endereco->rua = $request->input('rua');
        $endereco->numero = $request->input('numero');
        $endereco->cep = $request->input('cep');
        $endereco->bairro_id = $bairroId;
        $endereco->save();

        $contato = $organizacao->contato;
        $contato->email = $request->input('email');
        $contato->telefone = $request->input('telefone');
        $contato->save();

        return response()->json(['Associacao' => $organizacao]);
    }

}
