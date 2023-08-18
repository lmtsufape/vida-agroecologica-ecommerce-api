<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\UserController;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;

class WebUserController extends UserController
{
    public function index()
    {
        $users = parent::index();

        return view('admin.usuarios_index', compact('users'));
    }

    public function create()
    {
        return view('auth.register');
    }

    public function store(StoreUserRequest $request)
    {
        $user = parent::store($request);

        return redirect()->route('login')->with(['message' => 'Cadastro realizado!']);
    }

    public function show($id)
    {
        $user = parent::show($id);

        return response()->json(['user' => $user], 200);
    }

    public function edit()
    {
        //return view();
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
