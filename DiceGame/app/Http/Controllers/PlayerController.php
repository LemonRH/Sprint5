<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;

class PlayerController extends Controller
{
    //crea un nuevo jugador
    public function store(Request $request)
    {
        //validar los datos de entrada
        $request->validate([
            'email' => 'required|email|unique:players',
            'nickname' => 'nullable|unique:players',
        ]);

        //crear un nuevo jugador
        $player = new Player();
        $player->email = $request->email;
        $player->nickname = $request->nickname ?? 'Anónimo';
        $player->save();

        return response()->json(['message' => 'Jugador creado exitosamente'], 201);
    }

    //modifica el nombre de un jugador existente
    public function update(Request $request, $id)
    {
        //buscar el jugador por su ID
        $player = Player::findOrFail($id);

        //validar los datos de entrada
        $request->validate([
            'nickname' => 'nullable|unique:players,nickname,'.$id,
        ]);

        //actualizar el nombre del jugador
        $player->nickname = $request->nickname ?? $player->nickname;
        $player->save();

        return response()->json(['message' => 'Nombre de jugador actualizado exitosamente']);
    }

    //obtiene el listado de todos los jugadores con su porcentaje medio de éxito
    public function index()
    {
        $players = Player::all();

        //calcular el porcentaje medio de éxito de cada jugador
        foreach ($players as $player) {
            $player->success_rate = $player->calculateSuccessRate(); // Implementa esta función en el modelo Player
        }

        return response()->json($players);
    }

    //elimina todas las tiradas de un jugador
    public function destroyGames($id)
    {
        $player = Player::findOrFail($id);
        $player->games()->delete(); 
        return response()->json(['message' => 'Tiradas del jugador eliminadas']);
    }

    // Obtiene el listado de todas las tiradas de un jugador
    public function games($id)
    {
        $player = Player::findOrFail($id);
        $games = $player->games()->get(); // Suponiendo que haya una relación entre Player y Game
        return response()->json($games);
    }
}
