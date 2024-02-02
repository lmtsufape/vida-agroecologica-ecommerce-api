<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\UserController;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Models\User;

class ApiUserController extends UserController
{
    public function index()
    {
        $users = parent::index();
        $users->load('roles');
        return response()->json(['users' => $users], 200);
    }

    public function getPresidents()
    {
        $users = parent::getPresidents();

        return response()->json(['users' => $users], 200);
    }

    public function store(StoreUserRequest $request)
    {
        $user = parent::store($request);

        return response()->json(['user' => $user], 201);
    }

    public function show($id)
    {
        $user = parent::show($id);

        $user->load(['roles', 'contato']);

        return response()->json(['user' => $user], 200);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = parent::update($request, $id);

        $roleIds = $request->roles;
        $user->roles()->sync($roleIds);

        return response()->json(['user' => $user]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json($user);
    }

}
