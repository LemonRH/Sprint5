<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'player_id', 'dice1', 'dice2', 'is_won',
    ];

    /**
     *define la relacion de pertenencia a un jugador
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}