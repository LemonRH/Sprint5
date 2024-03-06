<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Player; 
use App\Models\Game; 

use Database\Factories\PlayerFactory;

class PlayerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test para verificar la modificación del nombre del jugador.
     *
     * @return void
     */
    public function test_update_player_name()
    {

        $player = Player::factory()->create();

        $newName = $this->faker->name;

        $response = $this->putJson('/api/players/' . $player->id, ['nickname' => $newName]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('players', [
            'id' => $player->id,
            'nickname' => $newName,
        ]);
    }
    
    public function test_create_player()
    {
        // Generar datos de jugador
        $playerData = [
            'nickname' => $this->faker->userName,
            'email' => $this->faker->unique()->safeEmail,
        ];
    
        $response = $this->postJson('/api/players', $playerData);
    
        $response->assertStatus(201);
    
        $this->assertDatabaseHas('players', $playerData);
    }
    public function test_destroy_player_games()
    {
        // Crear un jugador en la base de datos
        $player = Player::factory()->create();
    
        $games = Game::factory()->count(3)->create(['user_id' => $player->id]);
    
        $response = $this->deleteJson('/api/players/' . $player->id . '/games');
    
        $response->assertStatus(200);
    
        // Verificar que todas las tiradas asociadas al jugador hayan sido eliminadas
        $this->assertCount(0, $player->games()->get());
    
        $response->assertJson(['message' => 'Player games deleted successfully']);
    }

    public function test_get_players_with_success_rate()
    {
        Player::factory()->count(3)->create();

        $response = $this->getJson('/api/players');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => [
                'id',
                'email',
                'nickname',
                'created_at',
                'updated_at',
                'success_rate', 
            ]
        ]);
    }
    public function test_player_ranking()
    {
        $players = Player::factory()->count(5)->create();

        $totalSuccessRate = 0;
        foreach ($players as $player) {
            $totalSuccessRate += $player->calculateSuccessRate();
        }
        $averageSuccessRate = $totalSuccessRate / count($players);

        $response = $this->getJson('/api/players/ranking');

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'average_success_rate' => $averageSuccessRate
        ]);

        $response->assertJsonStructure([
            'ranking' => [
                '*' => [
                    'id',
                    'nickname',
                    'success_rate'
                ]
            ]
        ]);
    }

    public function test_player_loser()
    {
        $players = Player::factory()->count(5)->create();

        $response = $this->getJson('/api/players/ranking/loser');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'player' => [
                'id',
                'nickname',
                'email', 
            ],
            'success_rate'
        ]);
    }

    public function test_no_players_available_loser()
    {
        Player::truncate();

        $response = $this->getJson('/api/players/ranking/loser');

        $response->assertStatus(404);

        $response->assertJson([
            'message' => 'No hay jugadores disponibles'
        ]);
    }

    public function test_player_winner()
    {
        $players = Player::factory()->count(5)->create();

        $response = $this->getJson('/api/players/ranking/winner');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'player' => [
                'id',
                'nickname',
                'email', 
            ],
            'success_rate'
        ]);
    }

    public function test_no_players_available_winner()
    {
        Player::truncate();

        $response = $this->getJson('/api/players/ranking/winner');

        $response->assertStatus(404);

        $response->assertJson([
            'message' => 'No hay jugadores disponibles'
        ]);
    }
}
