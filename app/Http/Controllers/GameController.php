<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class GameController extends Controller
{
    // metodo para mostrar todos los juegos
    public function index()
    {
        $games = Game::all();
        return response()->json($games);
    }

    //metodo para mostrar un juego específico
    public function show($id)
    {
        $game = Game::findOrFail($id);
        return response()->json($game);
    }

    //nuevo jugador dados random
    public function store(Request $request)
{
    //verificacion de autenticacion
    if (Auth::check()) {
        //obtencion de id
        $userId = Auth::id();
    } else {
        // Manejar el caso de usuario no autenticado
        $userId = null; 
    }

    //random dices
    $dice1 = rand(1, 6);
    $dice2 = rand(1, 6);

    $sum = $dice1 + $dice2;

    //jugador gana
    $isWon = $sum == 7 ? true : false;

    // Guardar el juego en la base de datos
    $game = Game::create([
        'dice1' => $dice1,
        'dice2' => $dice2,
        'is_won' => $isWon,
        'user_id' => $userId, 
    ]);

    return response()->json($game, 201);
}
    //metodo para actualizar un juego existente
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'dice1' => 'sometimes|integer|min:1|max:6',
            'dice2' => 'sometimes|integer|min:1|max:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $game = Game::find($id);
        if (!$game) {
            return response()->json(['message' => 'Game not found'], 404);
        }

        //actualizar los atributos del juego según los datos proporcionados en la solicitud
        $game->update($request->all());

        return response()->json($game, 200);
    }

    // Método para eliminar un juego
    public function destroy($id)
    {
        $game = Game::find($id);
        if (!$game) {
            return response()->json(['message' => 'Game not found'], 404);
        }

        // Eliminar el juego de la base de datos
        $game->delete();

        return response()->json(null, 204);
    }
}