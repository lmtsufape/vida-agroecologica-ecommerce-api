<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Requests\ApiEmailVerificationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends AuthController
{
    // Gereção e revogação de tokens de acesso
    public function token(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($request->device_name);

        return response()->json(['token' => $token->plainTextToken, 'user' => $user->only(['id', 'name', 'email'])], 200);
    }

    public function revokeToken(Request $request)
    {
        $request->validate([
            'device_name' => 'required',
        ]);

        $user = $request->user();
        $user->tokens()->where('name', $request->device_name)->delete();

        return response()->noContent();
    }

    // Verificação de email
    public function resendEmail(Request $request)
    {
        return response()->json(parent::resendEmail($request), 200);
    }

    public function sendResetEmail(Request $request)
    {
        return parent::sendResetEmail($request)
            ? response()->json(['message' => 'Email de redefinição de senha enviado com sucesso.'], 200)
            : response()->json(['message' => 'Falha ao enviar o email de redefinição de senha.'], 500);
    }

    public function user_logado(){
        $user = auth()->user();

        return response()->json(['usuario' => $user]);
    }
}
