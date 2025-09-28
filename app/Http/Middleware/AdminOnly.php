<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user || strcasecmp((string)($user->role ?? ''), 'Admin') !== 0) {
            abort(403, 'Only administrators may access this resource.');
        }
        return $next($request);
    }
}


