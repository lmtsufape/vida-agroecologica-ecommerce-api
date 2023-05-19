<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function sendResetEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $response = Password::sendResetLink($request->only('email'));

        if ($response === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Email de redefinição de senha enviado com sucesso']);
        } else {
            return response()->json(['message' => 'Falha ao enviar o email de redefinição de senha'], 500);
        }
    }

    public function showResetForm(Request $request)
    {
        $email = $request->email;
        $token = $request->token;

        return view('auth.passwords.reset', compact('email', 'token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $response = Password::reset($request->only(
            'email', 'password', 'password_confirmation', 'token'
        ), function (User $user, String $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        });


        if ($response === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Senha redefinida com sucesso']);
        } else {
            return response()->json(['message' => 'Falha ao redefinir a senha'], 500);
        }
    }
}
