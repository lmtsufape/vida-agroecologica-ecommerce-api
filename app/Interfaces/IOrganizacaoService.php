<?php

namespace App\Interfaces;

interface IOrganizacaoService {

    public function salvarOrganizacao($dadosOrganizacao);

    public function atualizarOrganizacao($dadosOrganizacao);
}