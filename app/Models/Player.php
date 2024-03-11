<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Player extends Model
{
    use HasFactory;
    protected $fillable = [
        'email', 'nickname',
    ];

    /**
     * Relación uno a muchos
     */
    public function games()
    {
        return $this->hasMany(Game::class, 'user_id', 'id');
    }


    public function calculateSuccessRate()
{
    // Obtener el total de juegos del jugador
    $totalGames = $this->games()->count();

    // Si el jugador no ha jugado ningún juego, el porcentaje de éxito es 0
    if ($totalGames == 0) {
        return 0;
    }

    $totalWins = $this->games()->where('is_won', 1)->count();

    return ($totalWins / $totalGames) * 100;
}
}