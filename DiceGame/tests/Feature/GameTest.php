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
        $player = Player::factory()->create();

        $games = Game::factory()->count(3)->create(['user_id' => $player->id]);

        $response = $this->getJson('/api/players/' . $player->id . '/games');

        $response->assertStatus(200);

        $response->assertJsonCount(3); 
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
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/players/' . $user->id . '/games');

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'id',
            'dice1',
            'dice2',
            'is_won',
            'user_id',
            'created_at',
            'updated_at'
        ]);

        $this->assertDatabaseHas('games', [
            'user_id' => $user->id,
        ]);
    }

}
