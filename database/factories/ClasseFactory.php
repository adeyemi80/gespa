<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Classe;
use App\Models\Annee;

class ClasseFactory extends Factory
{
    protected $model = Classe::class;

    public function definition()
    {
        $niveaux = ['6ème', '5ème', '4ème', '3ème', '2nde', '1ère', 'Terminale'];

        return [
            'nom' => $this->faker->unique()->randomElement(['6ème A', '6ème B', '5ème A', '5ème B', '4ème A']),
            'niveau' => $this->faker->randomElement($niveaux),
            'annee_id' => Annee::factory(), // crée automatiquement une année associée si besoin
        ];
    }
}
