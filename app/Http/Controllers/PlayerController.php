<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Enums\Roles;

$userRole = Roles::USER;
$adminRole = Roles::ADMIN;
class PlayerController extends Controller
{
    //crea un nuevo jugador
    public function store(Request $request)
{
    // Verificar si el usuario ya tiene un jugador registrado
    $existingPlayer = Player::where('id', auth()->id())->first();

    // Si el usuario ya tiene un jugador registrado, devolver un mensaje de error
    if ($existingPlayer) {
        return response()->json(['error' => 'El usuario ya tiene un jugador registrado'], 400);
    }

    // Validar los datos de entrada
    $request->validate([
        'email' => 'required|email|unique:players',
        'nickname' => 'nullable|unique:players',
    ]);
    
    if ($request->has('nickname')) {
        $existingNickname = Player::where('nickname', $request->nickname)->first();
        if ($existingNickname) {
            return response()->json(['error' => 'El apodo ya está en uso'], 400);
        }
    }

    // Crear un nuevo jugador
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
        // Obtener todos los jugadores
        $players = Player::all();

        // Iterar sobre cada jugador y calcular su porcentaje medio de éxito
        foreach ($players as $player) {
            $player->success_rate = $player->calculateSuccessRate();
        }

        // Devolver la respuesta JSON con los jugadores y su porcentaje medio de éxito
        return response()->json($players);
    }
    //elimina todas las tiradas de un jugador
    public function destroyGames($id)
    {
        // Encuentra al jugador por su ID
        $player = Player::findOrFail($id);
    
        // Elimina todas las tiradas asociadas al jugador
        $player->games()->delete();
    
        // Devuelve una respuesta adecuada
        return response()->json(['message' => 'Player games deleted successfully'], 200);
    }

    // Obtiene el listado de todas las tiradas de un jugador
    public function games($id)
    {
        $player = Player::findOrFail($id);
        $games = $player->games()->get(); // Suponiendo que haya una relación entre Player y Game
        return response()->json($games);
    }
    public function ranking()
    {
        // Obtener todos los jugadores con su porcentaje de éxito
        $players = Player::all();
    
        // Calcular el porcentaje medio de victorias de todos los jugadores
        $totalPlayers = count($players);
        $totalSuccessRate = 0;
    
        foreach ($players as $player) {
            $totalSuccessRate += $player->calculateSuccessRate();
        }
    
        $averageSuccessRate = $totalSuccessRate / $totalPlayers;
    
        // Ordenar los jugadores por su porcentaje de éxito
        $ranking = $players->sortByDesc(function ($player) {
            return $player->calculateSuccessRate();
        });
    
        // Construir la estructura de datos para la respuesta JSON
        $rankingData = [];
        foreach ($ranking as $player) {
            $rankingData[] = [
                'id' => $player->id,
                'nickname' => $player->nickname,
                'success_rate' => $player->calculateSuccessRate()
            ];
        }
    
        return response()->json([
            'average_success_rate' => $averageSuccessRate,
            'ranking' => $rankingData
        ]);
    }
public function getLoser()
{
    // Obtener todos los jugadores
    $players = Player::all();

    // Si no hay jugadores, devolver un mensaje apropiado
    if ($players->isEmpty()) {
        return response()->json(['message' => 'No hay jugadores disponibles'], 404);
    }

    // Obtener el jugador con el peor porcentaje de éxito
    $loser = $players->sortBy(function ($player) {
        return $player->calculateSuccessRate();
    })->first();

    // Obtener el porcentaje de éxito del jugador perdedor
    $loserSuccessRate = $loser->calculateSuccessRate();

    // Devolver la respuesta JSON con el jugador perdedor y su porcentaje de éxito
    return response()->json([
        'player' => $loser,
        'success_rate' => $loserSuccessRate
    ]);
}
public function getWinner()
{
    // Obtener todos los jugadores
    $players = Player::all();

    // Si no hay jugadores, devolver un mensaje apropiado
    if ($players->isEmpty()) {
        return response()->json(['message' => 'No hay jugadores disponibles'], 404);
    }

    // Obtener el jugador con el mejor porcentaje de éxito
    $winner = $players->sortByDesc(function ($player) {
        return $player->calculateSuccessRate();
    })->first();

    // Obtener el porcentaje de éxito del jugador ganador
    $winnerSuccessRate = $winner->calculateSuccessRate();

    // Devolver la respuesta JSON con el jugador ganador y su porcentaje de éxito
    return response()->json([
        'player' => $winner,
        'success_rate' => $winnerSuccessRate
    ]);
}
}
