<?php

namespace App\Services;

use App\Interfaces\IUserService;
use App\Models\Contato;
use App\Models\Endereco;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService implements IUserService {

    public function salvarUsuario($dadosUsuario) {

        DB::transaction(function () use ($dadosUsuario) {

            $contato = Contato::create($dadosUsuario);
            $endereco = Endereco::create($dadosUsuario);
        
            $dadosUsuario["password"] = Hash::make("password");
            $dadosUsuario["endereco_id"] = $endereco->id;
            $dadosUsuario["contato_id"] = $contato->id;

            User::create($dadosUsuario);
        });
    }

    public function atualizarUsuario($dadosUsuario) {

        DB::transaction(function () use ($dadosUsuario) {
            
            $usuario = User::find($dadosUsuario["usuario_id"]);
            $contato = Contato::find($usuario->contato_id);
            $endereco = Endereco::find($usuario->endereco_id);

            $usuario->fill($dadosUsuario);
            $contato->fill($dadosUsuario);
            $endereco->fill($dadosUsuario);

            $contato->update();
            $endereco->update();
            $usuario->update();
        });
    }
}