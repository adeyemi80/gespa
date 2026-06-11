<?php

namespace Database\Factories;

use App\Models\Paren;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParenFactory extends Factory
{
    protected $model = Paren::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'telephone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'adresse' => $this->faker->address,
            // ajoute ici les autres colonnes nécessaires
        ];
    }
}
