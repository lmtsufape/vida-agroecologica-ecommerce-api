<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller{

    public function login(Request $request)
    {
        $dados = $request->only(['email', 'password']);
        if(!Auth::attempt($dados))
        {
            return response()->json(['erro' => 'E-mail ou senha inválidos'], 406);
        }

        $user = Auth::user();
        $token =  $user->createToken('verified');
        $usuario = ['id' => $user->id, 'token' => $token->plainTextToken, 'nome' => $user->name,
        'email' => $user->email, 'papel' => $user->papel_type];

        return response()->json(['user' => $usuario], 200);
    }

    public function token(Request $request)
    {
        $dados = $request->only(['email', 'password']);
        if(!Auth::attempt($dados))
        {
            return response()->json(['erro' => 'E-mail ou senha inválidos'], 406);
        }
        $user = Auth::user();
        $token =  $user->createToken('verified')->plainTextToken;

        return response()->json($token, 200);
    }



}
