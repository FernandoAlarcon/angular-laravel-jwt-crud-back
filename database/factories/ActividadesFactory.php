<?php

namespace Database\Factories;

use App\Models\Actividades;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory; 

class ActividadesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Actividades::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->name,
            'usuario' => User::all()->random()->id,
            'hora' => 15
        ];
    }
}
