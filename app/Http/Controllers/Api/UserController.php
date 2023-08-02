<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json(['users' => $users], 200);
    }

    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();
        $this->authorize('create', [User::class, $validatedData['roles']]);

        DB::beginTransaction();
        $user = User::make($validatedData);
        $user->password = Hash::make($validatedData['password']);
        $user->save();
        $user->contato()->create($validatedData);
        $user->roles()->sync($validatedData['roles']);
        DB::commit();

        event(new Registered($user));

        return response()->json(['user' => $user], 201);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json(['user' => $user], 200);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $validatedData = $request->validated();
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        DB::beginTransaction();
        $user->update($validatedData);
        $user->contato->update($validatedData);
        DB::commit();

        return response()->json(['user' => $user], 200);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);
        $user->delete();

        return response()->noContent();
    }

    public function setUserRoles(Request $request, $id)
    {
        $validatedData = $request->validate([
            'roles' => 'required|array|min:1',
            'roles.*' => 'integer|exists:roles,id'
        ]);

        $user = User::findOrFail($id);
        $user->roles()->sync($validatedData);

        return response()->json(['success' => 'Roles de usuário atualizadas.'], 200);
    }
}
