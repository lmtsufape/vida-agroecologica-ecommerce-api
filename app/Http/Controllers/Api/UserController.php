<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();
        $user = User::make($validatedData);
        $user->password = Hash::make($validatedData['password']);
        $user->save();
        $user->contato()->create($validatedData);
        DB::commit();

        return $user;
    }
}
