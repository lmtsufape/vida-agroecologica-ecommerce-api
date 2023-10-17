<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\UserController;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;

class ApiUserController extends UserController
{
    public function index()
    {
        $users = parent::index();

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

        return response()->json(['user' => $user], 200);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = parent::update($request, $id);

        return response()->json(['user' => $user]);
    }

    public function destroy($id)
    {
        parent::destroy($id);

        return response()->noContent();
    }

    public function updateUserRoles(Request $request, $id)
    {
        parent::updateUserRoles($request, $id);

        return response()->json(['message' => 'Roles alteradas com sucesso.'], 200);
    }
}
