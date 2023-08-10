<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssociacaoRequest;
use App\Http\Requests\UpdateAssociacaoRequest;
use App\Interfaces\IAssociacaoService;

class AssociacaoController extends Controller
{

    private $associacaoService;

    public function __construct(IAssociacaoService $associacaoService) {
        $this->associacaoService = $associacaoService;
    }

    public function store(StoreAssociacaoRequest $request){
        $this->associacaoService->salvarAssociacao($request->validated());
        return redirect(route('associacoes.index'))->with('sucesso', 'Associação cadastrada com sucesso!');
    }

    public function update(UpdateAssociacaoRequest $request){
        $this->associacaoService->atualizarAssociacao($request->validated());
        return redirect(route('associacoes.index'))->with('sucesso', 'Associação atualizada com sucesso!');
    }
}
