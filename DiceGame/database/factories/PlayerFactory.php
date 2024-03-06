<?php

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    /**
     * Define el modelo de factory asociado.
     *
     * @var string
     */
    protected $model = Player::class;

    /**
     * Define el estado predeterminado del modelo.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nickname' => $this->faker->userName,
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
