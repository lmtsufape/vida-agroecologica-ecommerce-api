<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;

class UserController extends Controller
{

    public function index()
    {
        $users = User::all();

        return view('admin.usuarios_index', [
            'users' => $users,
        ]);
    }

    public function store() {

    }

    public function update() {

    }

}
