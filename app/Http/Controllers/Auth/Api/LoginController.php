<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Separação do necessario para a verificação das credenciais
        $credentials = $request->only('email', 'password');

        // Verificação de credenciais
        if (!auth()->attempt($credentials)) {
            abort(401, 'Credenciais inválidas');
        }

        // Criação do token de acesso
        $token = auth()->user()->createToken('auth_token');


        // Retorno do token
        return response()->json([
            'data' => [
                'token' => $token->plainTextToken,
                'nome' => auth()->user()->nome
            ]
        ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json('Saimo, tamo fora');
    }
}
