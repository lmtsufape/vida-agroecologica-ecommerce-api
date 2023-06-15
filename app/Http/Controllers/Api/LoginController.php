<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiEmailVerificationRequest;
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
        $token =  $user->createToken('autenticar');
        $usuario = ['id' => $user->id, 'token' => $token->plainTextToken, 'nome' => $user->name,
        'email' => $user->email, 'papel' => $user->papel_type, 'papel_id' => $user->papel_id];

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
        $token =  $user->createToken('autenticar')->plainTextToken;

        return response()->json($token, 200);
    }

    public function verificarEmail(ApiEmailVerificationRequest $request)
    {
        $request->fulfill();
 
        return view('auth.emailVerified');
    }

    public function reenviarEmail(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent!']);
    }
}
