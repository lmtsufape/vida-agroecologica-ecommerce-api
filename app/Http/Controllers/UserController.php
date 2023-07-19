<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Interfaces\IUserService;

class UserController extends Controller
{

    private $userService;

    public function __construct(IUserService $userService) {
        $this->userService = $userService;
    }

    public function store(StoreUsuarioRequest $request)
    {
        $this->userService->salvarUsuario($request->validated());
        return redirect(route('usuarios.index'))->with('sucesso', 'Usuário cadastrado com sucesso com senha padrão password!');
    }

    public function update(UpdateUsuarioRequest $request)
    {
        $this->userService->atualizarUsuario($request->validated());
        return redirect(route('usuarios.index'))->with('sucesso', 'Usuário atualizado com sucesso!');
    }
}
