<?php

namespace Database\Factories;

use App\Models\Eleve;
use App\Models\Paren;
use App\Models\Classe;
use App\Models\Annee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EleveFactory extends Factory
{
    protected $model = Eleve::class;

    public function definition(): array
    {
         return [
        'nom' => $this->faker->lastName,
        'prenom' => $this->faker->firstName,
        'matricule' => $this->faker->unique()->numerify('MAT#####'),
        'sexe' => 'M',
        'date_naissance' => $this->faker->date(),
        'adresse' => $this->faker->address,
        'telephone' => $this->faker->phoneNumber,
        'email' => $this->faker->unique()->safeEmail,
        'lieu_naissance' => $this->faker->city,
        'paren_id' => Paren::factory(),  // <--- crée automatiquement un parent
        'classe_id' => Classe::factory(), // idem pour classe
        'annee_id' => Annee::factory(),   // idem pour année
    ];
    }
}
