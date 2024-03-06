<?php

use App\Models\Game;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_games_by_player_id()
    {
        // Crear un jugador en la base de datos
        $player = Player::factory()->create();

        // Crear algunas jugadas asociadas al jugador
        $games = Game::factory()->count(3)->create(['user_id' => $player->id]);

        // Realizar una solicitud GET a la ruta de listado de jugadas por jugador
        $response = $this->getJson('/api/players/' . $player->id . '/games');

        // Verificar que la solicitud haya tenido éxito (código de estado HTTP 200)
        $response->assertStatus(200);

        // Verificar que la respuesta contiene las jugadas asociadas al jugador
        $response->assertJsonCount(3); // Verificar que hay 3 jugadas en la respuesta
        $response->assertJsonStructure([
            '*' => [
                'id',
                'dice1',
                'dice2',
                'is_won',
                'user_id',
                'created_at',
                'updated_at',
            ]
        ]);
    }
    public function test_create_game_user()
    {
        // Authenticate a user
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Make a POST request to create a game
        $response = $this->postJson('/api/players/' . $user->id . '/games');

        // Assert that the response has status code 201 (Created)
        $response->assertStatus(201);

        // Assert that the response contains the correct JSON structure
        $response->assertJsonStructure([
            'id',
            'dice1',
            'dice2',
            'is_won',
            'user_id',
            'created_at',
            'updated_at'
        ]);

        // Assert that the game is stored in the database
        $this->assertDatabaseHas('games', [
            'user_id' => $user->id,
        ]);
    }

}
