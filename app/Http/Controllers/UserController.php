<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnderecoRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Endereco;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Access\AuthorizationException;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('nome', '!=', 'administrador');
        })->get();

        return $users;
    }

    public function getPresidents()
{
    $users = User::whereHas('roles', function ($query) {
        $query->whereIn('nome', ['presidente']);
    })->get();

    return $users;
}

    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();
        $this->authorize('create', [User::class, $validatedData['roles']]);

        DB::beginTransaction();
        $user = User::make($validatedData);
        $user->password = Hash::make($validatedData['password']);
        $user->ativo = true;
        $user->save();
        $user->enderecos()->create($validatedData);
        $user->contato()->create($validatedData);
        $user->roles()->sync($validatedData['roles']);
        DB::commit();

        //event(new Registered($user));

        return $user;
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('view', $user);

        return $user;
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $validatedData = $request->validated();
        $user = User::findOrFail($id);

        DB::beginTransaction();
        $user->update($validatedData);
        $user->contato->update($validatedData);
        DB::commit();

        return $user;
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);

        $user->delete();

        return null;
    }

    public function updateUserRoles(Request $request, $id)
    {
        $validatedData = $request->validate([
            'roles' => 'required|array|min:1',
            'roles.*' => 'integer|exists:roles,id'
        ]);

        $user = User::findOrFail($id);

        $this->authorize('updateUserRoles', [$user, $request->roles]);

        $user->roles()->sync($validatedData['roles']);

        return $user->roles;
    }

    # Endereços

    public function indexEndereco()
    {
        return Auth::user()->enderecos;
    }

    public function createNewEndereco(StoreEnderecoRequest $request)
    {
        $validatedData = $request->validated();

        $user = $request->user();
        $endereco = $user->enderecos()->create($validatedData);

        return $endereco;
    }

    public function updateEndereco(StoreEnderecoRequest $request, $id)
    {
        $validatedData = $request->validated();

        $endereco = Endereco::findOrFail($id);
        $this->authorize('update', $endereco);

        $endereco = $endereco->update($validatedData);

        return $endereco;
    }

    public function destroyEndereco($id)
    {
        $endereco = Endereco::findOrFail($id);
        $this->authorize('delete', $endereco);

        $endereco->delete();

        return null;
    }
}
