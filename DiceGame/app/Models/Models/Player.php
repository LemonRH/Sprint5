<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'email', 'nickname',
    ];

    /**
     * relacion uno a muchos
     */
    public function games()
    {
        return $this->hasMany(Game::class);
    }
}
