<?php

namespace App\Services;

use App\Interfaces\IAgricultorService;
use App\Models\OrganizacaoControleSocial;
use App\Models\User;

class AgricultorService implements IAgricultorService {

    public function index() {
        $agricultores = User::with('organizacao')->whereHas('roles', function ($query) {
            $query->where('nome', 'agricultor');
        })->get();

        $organizacoes = OrganizacaoControleSocial::all();
        return [$agricultores, $organizacoes];
    }


    public function vincularAgricultorOrganizacao($agricultoId, $organizacaoId) {
        $agricultor = User::find($agricultoId);
        $agricultor->organizacao_id = $organizacaoId;
        $agricultor->save();
    }
}
