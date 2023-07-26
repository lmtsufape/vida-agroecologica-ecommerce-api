<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiEmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $dados = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($dados)) {
            abort(401, 'Credenciais inválidas');
        }

        $user = $request->user();
        $token =  $user->createToken('auth_token');
        $usuario = [
            'id' => $user->id, 'token' => $token->plainTextToken, 'nome' => $user->name,
            'email' => $user->email, 'role' => $user->roles
        ];

        return response()->json(['user' => $usuario], 200);
    }

    public function token(Request $request)
    {
        $dados = $request->only(['email', 'password']);

        if (!Auth::attempt($dados)) {
            abort(401, 'Credenciais inválidas');
        }

        $user = $request->user();
        $token =  $user->createToken('auth_token')->plainTextToken;

        return response()->json($token, 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json(['message' => 'usuário deslogado'], 200);
    }

    public function verificarEmail(ApiEmailVerificationRequest $request)
    {
        $request->fulfill();

        return view('auth.emailVerified');
    }

    public function reenviarEmail(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent!'], 200);
    }
}
