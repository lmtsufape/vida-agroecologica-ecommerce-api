<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Requests\ApiEmailVerificationRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class WebAuthController extends AuthController
{
    // Login e logout
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Verificação de email
    public function showEmailNotice()
    {
        return view('auth.email-notice');
    }

    public function resendEmail(Request $request)
    {
        return back()->with(parent::reenviarEmail($request));
    }

    public function verifyEmail(ApiEmailVerificationRequest $request)
    {
        $request->fulfill();

        return view('auth.email-verified');
    }

    public function showResetPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetEmail(Request $request)
    {
        return parent::sendResetEmail($request)
            ? back()->with(['message' => 'Email de redefinição de senha enviado com sucesso.'])
            : back()->withErrors(['message' => 'Falha ao enviar o email de redefinição de senha.']);
    }

    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return view('auth.reset-password-result', ['sucesso' => $status === Password::PASSWORD_RESET]);
    }
}
