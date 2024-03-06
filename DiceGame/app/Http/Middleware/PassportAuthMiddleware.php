<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PassportAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated using Passport
        if (Auth::guard('api')->check()) {
            return $next($request);
        }

        // If not authenticated, return an unauthorized response
        return response()->json(['error' => 'UnauthorizedPaco'], 401);
    }
}
