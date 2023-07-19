<?php

namespace App\Http\Controllers;

use App\Http\Requests\VinculaAgricultoOrganizacaoRequest;
use App\Interfaces\IAgricultorService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AgricultorController extends Controller
{

    private $agricultoService;

    public function __construct(IAgricultorService $agricultoService) {
        $this->agricultoService = $agricultoService;
    }

    public function agricultoresIndex(){
        try {
            $agricultorInfo = $this->agricultoService->index();
            return view('agricultor.agricultores_index', ['agricultores' => $agricultorInfo[0], "organizacoes" => $agricultorInfo[1]]);
        } catch(Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function vincularAgricultoOrganizacao(VinculaAgricultoOrganizacaoRequest $request) {
        try {
            $dados = $request->validated();
            $this->agricultoService->vincularAgricultorOrganizacao($dados['agricultor_id'], $dados['organizacao_id']);
            return redirect(route('agricultores.index'))->with('sucesso', 'Organização vincula com sucesso!');
        } catch(Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
