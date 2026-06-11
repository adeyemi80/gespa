<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Scolarite>
 */
class ScolariteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'inscription' => fake()->sentence(1, true),
            'classe' => fake()->sentence(1, true),          
            'montant' => fake()->sentence(1, true),
            'mpaye' => fake()->sentence(1, true),
            'reste' => fake()->sentence(1, true),
        ];
    }
}
