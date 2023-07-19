<?php

namespace App\Interfaces;

interface IAssociacaoService {

    public function salvarAssociacao($dadosAssociacao);

    public function atualizarAssociacao($dadosAssociacao);
}