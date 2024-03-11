<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
//use Laravel\Sanctum\HasApiTokens;
use App\Enums\Roles;
use Laravel\Passport\HasApiTokens;

class AuthController extends Controller
{
    /**
     * Registro de un nuevo usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => Roles::ADMIN, //asignamos el rol de usuario por defecto al registrar ¡¡¡¡¡¡¡¡¡¡¡NO FUNCIONA CORRECTAMENTE!!!!!!!!!!!!!!!!
        ]);
        $credentials = $request->only(['email', 'password']);
        if(Auth::attempt($credentials)) {
            $token = $user->createToken('Token Name')->accessToken;
        }

        return response()->json(['token' => $token], 201);
    }

    /**
     * Login de usuario existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $user = $request->user();
            $token = $user->createToken('auth_token')->accessToken;

            return response()->json([
                'token' => $token,
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role, 
            ], 200);
        }

        // Si las credenciales son inválidas, devuelve un error de autorización
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
