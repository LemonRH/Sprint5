<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\Roles;

class AdminController extends Controller
{
    /**
     * Método para realizar una acción de administración.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminAction(Request $request)
    {
        // Verificar si el usuario actual tiene el rol de administrador
        if ($request->user()->role !== Roles::ADMIN) {
            return response()->json(['error' => 'Unauthorized. Solo los administradores pueden acceder a esta acción.'], 401);
        }


        return response()->json(['message' => 'Acción de administración completada con éxito.'], 200);
    }
}
