<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnderecoRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\EnderecoService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $EnderecoService;

    public function __construct(EnderecoService $EnderecoService)
    {
        $this->EnderecoService = $EnderecoService;
    }

    public function index()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('nome', '!=', 'administrador');
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
        $user->save();
        $user->enderecos()->save($this->EnderecoService->Criar($request));
        $user->contato()->create($validatedData);
        $user->roles()->sync($validatedData['roles']);
        DB::commit();

        event(new Registered($user));

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

        $user->roles()->sync($validatedData);

        return true;
    }

    public function criarEndereco(StoreEnderecoRequest $request)
    {
        $user = $request->user();
        $user->enderecos()->save($this->EnderecoService->Criar($request));

        return true;
    }
}
