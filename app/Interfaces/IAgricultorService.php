<?php

namespace App\Interfaces;

interface IAgricultorService {

    public function index();

    public function vincularAgricultorOrganizacao($agricultoId, $organizacaoId);
}