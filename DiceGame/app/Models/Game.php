<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    protected $fillable = ['dice1', 'dice2', 'is_won', 'user_id']; 


    /**
     *define la relacion de pertenencia a un jugador
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'user_id');
    }
}