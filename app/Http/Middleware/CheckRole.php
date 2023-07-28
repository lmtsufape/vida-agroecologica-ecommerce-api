<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // Check if the user has any of the specified roles
        if ($request->user()->hasAnyRoles($roles)) {
            return $next($request);
        }

        // Redirect or return an error response if the user doesn't have any of the required roles
        // For example, you can return a 403 Forbidden response
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
