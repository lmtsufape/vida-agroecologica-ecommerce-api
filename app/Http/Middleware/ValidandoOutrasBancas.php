<?php

namespace App\Http\Middleware;

use App\Models\Banca;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidandoOutrasBancas
{

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $bancas = Banca::where('produtor_id', '=', $user->papel_id)->get();
        if (count($bancas) > 0) {
            return response()->json(
                [
                    'error' => [
                        'message' => 'Já existe uma banca criada.'
                    ]
                ],
                400
            );
        } else {
            return $next($request);
        }
    }
}
