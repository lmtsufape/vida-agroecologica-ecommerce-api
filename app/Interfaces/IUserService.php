<?php

namespace App\Interfaces;

interface IUserService {

    public function salvarUsuario($dadosUsuario);

    public function atualizarUsuario($dadosUsuario);
}