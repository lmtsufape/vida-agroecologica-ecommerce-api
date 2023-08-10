<?php

namespace App\Services;

use App\Interfaces\IOrganizacaoService;
use App\Models\Contato;
use App\Models\Endereco;
use App\Models\OrganizacaoControleSocial;
use App\Models\Bairro;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrganizacaoService implements IOrganizacaoService {

    public function salvarOrganizacao($dadosOrganizacao) {

        DB::transaction(function () use ($dadosOrganizacao) {

            $userId = auth()->user()->id;

            $bairro = Bairro::firstOrCreate(['nome' => $dadosOrganizacao['bairro']]);
            $dadosOrganizacao['bairro_id'] = $bairro->id;
            //fix: padronizar nome
            $dadosOrganizacao['estado'] = $dadosOrganizacao['uf'];

            $contato = Contato::create($dadosOrganizacao);
            $dadosOrganizacao['contato_id'] = $contato->id;
            $dadosOrganizacao['user_id'] = $userId;

            $organizacao = OrganizacaoControleSocial::create($dadosOrganizacao);

            $endereco = new Endereco($dadosOrganizacao);
            $organizacao->endereco()->save($endereco);

            $organizacao->save();
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
