<?php

namespace App\Services;

use App\Interfaces\IOrganizacaoService;
use App\Models\Contato;
use App\Models\Endereco;
use App\Models\OrganizacaoControleSocial;
use Illuminate\Support\Facades\DB;

class OrganizacaoService implements IOrganizacaoService {

    public function salvarOrganizacao($dadosOrganizacao) {

        DB::transaction(function () use ($dadosOrganizacao) {

            $contato = Contato::create($dadosOrganizacao);
            $endereco = Endereco::create($dadosOrganizacao);
        
            $dadosOrganizacao["endereco_id"] = $endereco->id;
            $dadosOrganizacao["contato_id"] = $contato->id;

            OrganizacaoControleSocial::create($dadosOrganizacao);
        });
    }

    public function atualizarOrganizacao($dadosOrganizacao) {
        $ocs = OrganizacaoControleSocial::find($dadosOrganizacao["ocs_id"]);

        DB::transaction(function () use ($dadosOrganizacao, $ocs) {
            $contato = $ocs->contato;
            $endereco = $ocs->endereco;

            $contato->fill($dadosOrganizacao);
            $endereco->fill($dadosOrganizacao);
            $ocs->fill($dadosOrganizacao);

            $contato->update();
            $endereco->update();
            $ocs->update();
        });

        return $ocs->associacao_id;
    }
}