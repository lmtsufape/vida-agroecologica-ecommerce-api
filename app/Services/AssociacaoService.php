<?php

namespace App\Services;

use App\Interfaces\IAssociacaoService;
use App\Models\Associacao;
use App\Models\Contato;
use Illuminate\Support\Facades\DB;

class AssociacaoService implements IAssociacaoService {

    public function salvarAssociacao($dadosAssociacao) {

        DB::transaction(function () use ($dadosAssociacao) {

            $contato = Contato::create($dadosAssociacao);
        
            $dadosAssociacao["user_id"] = $dadosAssociacao["presidente"];
            $dadosAssociacao["contato_id"] = $contato->id;

            Associacao::create($dadosAssociacao);
        });
    }

    public function atualizarAssociacao($dadosAssociacao) {

        DB::transaction(function () use ($dadosAssociacao) {
            $associacao = Associacao::find($dadosAssociacao['associacao_id']);
            $contato = $associacao->contato;

            $contato->fill($dadosAssociacao);
            $associacao->fill($dadosAssociacao);

            $contato->update();
            $associacao->update();
        });
    }
}