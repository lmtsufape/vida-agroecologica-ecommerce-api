<?php

namespace App\Services;

use App\Interfaces\IAgricultorService;
use App\Models\OrganizacaoControleSocial;
use App\Models\User;

class AgricultorService implements IAgricultorService {

    public function index() {
        $agricultores = User::with("organizacao")->where('tipo_usuario_id', 3)->get();
        $organizacoes = OrganizacaoControleSocial::all();
        return [$agricultores, $organizacoes];
    }

    public function vincularAgricultorOrganizacao($agricultoId, $organizacaoId) {
        $agricultor = User::find($agricultoId);
        $agricultor->organizacao_controle_social_id = $organizacaoId;
        $agricultor->update();
    }
}