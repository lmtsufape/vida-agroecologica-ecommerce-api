<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\OrganizacaoControleSocial;
use App\Models\Associacao;
use App\Models\Endereco;
use App\Models\Contato;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOrganizacaoRequest;

class OrganizacaoControleSocialController extends Controller
{
    public function index(Request $request, $associacao_id)
    {
        $lista_ocs = OrganizacaoControleSocial::where('associacao_id', $associacao_id)->get();
        // dd($lista_ocs);
        return view('admin.ocs_index', compact('lista_ocs', 'associacao_id'));
    }

    public function store(StoreOrganizacaoRequest $request) {

    $associacaoId = $request->input('associacao_id');
    $data = $request->validated();
    $organizacao = OrganizacaoControleSocial::create($data);

    $contato = new Contato([
        'email' => $request->input('email'),
        'telefone' => $request->input('telefone'),
    ]);

    $endereco = new Endereco([
        'rua' => $request->input('rua'),
        'cep' => $request->input('cep'),
        'numero' => $request->input('numero'),
        'cidade' => $request->input('cidade'),
        'estado' => $request->input('estado'),
        'pais' => $request->input('pais'),
        'bairro' => $request->input('bairro')
    ]);

    $organizacao->contato()->save($contato);
    $organizacao->endereco()->save($endereco);

    $organizacao->save();

    dd($organizacao);

    return redirect()->route('ocs.index', ['associacao_id' => $associacaoId]);
}
    public function update(StoreOrganizacaoRequest $request)
    {
        $data = $request->validated();
        $ocs = $request->input('ocs_id');
        dd($ocs);

        $organizacao = OrganizacaoControleSocial::findOrFail($data['id']);
        $organizacao->update($data);

        return redirect()->route('ocs.index', ['associacao_id' => $data['associacao_id']]);
    }
}
