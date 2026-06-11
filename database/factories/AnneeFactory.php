<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Annee;

class AnneeFactory extends Factory
{
    protected $model = Annee::class;

    public function definition()
    {
        return [
            'nom' => $this->faker->unique()->year() . '-' . ($this->faker->year() + 1),
            'debut' => $this->faker->date(),
            'fin' => $this->faker->date(),
            'en_cours' => $this->faker->boolean(20), // 20% de chance que ce soit "en cours"
        ];
    }
}
