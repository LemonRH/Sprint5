<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Facades\Validator;


class GameController extends Controller
{
    //metodo mostrar juegos
    public function index()
    {
        $games = Game::all();
        return response()->json($games);
    }

    //metodo mostrar un juego concreto
    public function show($id)
    {
        $game = Game::findOrFail($id);
        return response()->json($game);
    }

    //creacion nuevo juego
    public function store(Request $request)
    {
        //reglas de validación
        $validator = Validator::make($request->all(), [
            'dice1' => 'required|integer|min:1|max:6',
            'dice2' => 'required|integer|min:1|max:6',
        ]);

        //verifica si hay errores de validación
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        $game = new Game();
        //valores aleatorios y jugador ganador
        $dice1 = rand(1, 6);
        $dice2 = rand(1, 6);
        $sum = $dice1 + $dice2;
        $isWon = $sum == 7 ? true : false;
    
        //asigna los valores a los atributos del juego
        $game->dice1 = $dice1;
        $game->dice2 = $dice2;
        $game->is_won = $isWon;
    
        //guarda el juego en la base de datos
        $game->save();
    
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
    
        //actualiza los atributos del juego según los datos proporcionados en la solicitud
        $game->update($request->all());
    
        return response()->json($game, 200);
    }

    //metodo para eliminar un juego 
    public function destroy($id)
    {
        $game = Game::find($id);
        if (!$game) {
            return response()->json(['message' => 'Game not found'], 404);
        }
    
        //elimina el juego de la base de datos
        $game->delete();
    
        return response()->json(null, 204);
    }
}
