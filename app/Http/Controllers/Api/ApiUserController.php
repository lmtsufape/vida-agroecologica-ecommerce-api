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

    public function updateUserRoles(Request $request, $id){
        $roles = parent::updateUserRoles($request, $id);
        return response()->json(['roles' => $roles], 200);
        
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
    
        $newRoles = collect($request->roles)->sort()->values();
        $currentRoles = $user->roles->pluck('id')->sort()->values();

    
        if ($newRoles->diff($currentRoles)->isNotEmpty() || $currentRoles->diff($newRoles)->isNotEmpty()) {
            $this->updateUserRoles($request, $id);
        }
    
        return response()->json(['user' => $user->refresh()]);
    }
    

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json($user);
    }

}
