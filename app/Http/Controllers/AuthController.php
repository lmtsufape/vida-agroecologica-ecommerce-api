<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    // Verificação de email
    public function resendEmail(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return ['message' => 'Verification link sent!'];
    }

    // Redefinição de senha
    public function sendResetEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT;
    }
}
