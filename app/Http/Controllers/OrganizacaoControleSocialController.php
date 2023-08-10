<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganizacaoRequest;
use App\Http\Requests\UpdateOrganizacaoRequest;
use App\Interfaces\IOrganizacaoService;
use App\Models\Associacao;
use App\Models\OrganizacaoControleSocial;
use Illuminate\Support\Facades\DB;

class OrganizacaoControleSocialController extends Controller
{

    private $organiacaoService;

    public function __construct(IOrganizacaoService $organiacaoService)
    {
        $this->organiacaoService = $organiacaoService;
    }

    public function index($associacao_id){
        $lista_ocs = OrganizacaoControleSocial::where('associacao_id',$associacao_id)->get();
        $associacao = Associacao::find($associacao_id);

        return view('organizacaoControleSocial.index', compact('lista_ocs', 'associacao'));
    }

    public function store(StoreOrganizacaoRequest $request){
        $this->organiacaoService->salvarOrganizacao($request->validated());
        return redirect(route('ocs.index',['associacao_id' => $request->associacao_id]))->with('sucesso', 'Organização cadastrada com sucesso!');
    }

    public function update(UpdateOrganizacaoRequest $request){
        $associacaoId = $this->organiacaoService->atualizarOrganizacao($request->validated());
        return redirect(route('ocs.index',['associacao_id' => $associacaoId]))->with('sucesso', 'Organização atualizada com sucesso!');
    }

    public function show()
    {
        //fazer um join para pegar todos os usuario d euma ocs
        $usuarios = DB::table('users');
    }
}
