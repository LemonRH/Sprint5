<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    /**
     * El nombre del modelo asociado a la fábrica.
     *
     * @var string
     */
    protected $model = Game::class;

    /**
     * Define el estado predeterminado del modelo.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => function () {
                return \App\Models\Player::factory()->create()->id;
            },
            'dice1' => $this->faker->numberBetween(1, 6), // Valor aleatorio entre 1 y 6
            'dice2' => $this->faker->numberBetween(1, 6), // Valor aleatorio entre 1 y 6
            'is_won' => $this->faker->boolean, // Genera un valor booleano aleatorio
            // Puedes agregar otros atributos aquí si es necesario
        ];
    }
}
