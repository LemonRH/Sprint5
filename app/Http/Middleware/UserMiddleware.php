<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\Roles;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario está autenticado
        if (Auth::check()) {
            // Verificar si el usuario tiene el rol de usuario
            if ($request->user()->role === Roles::USER) {
                return $next($request);
            }
        }

        // Si el usuario no está autenticado o no tiene el rol de usuario, devolver un error de autorización
        return response()->json(['error' => 'Unauthorized. Solo los usuarios pueden acceder a esta acción.'], 401);
    }
}
