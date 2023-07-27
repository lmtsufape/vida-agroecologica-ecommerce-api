<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use function auth;
use function response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->roles->contains('nome', 'admin')) {
            return $next($request);
        }

        return response()->json('Not Authorized', 401);
    }
}
