<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Epreuve>
 */
class EpreuveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'trimestre' => fake()->sentence(1, true), 
            'matiere' => fake()->sentence(1, true), 
            'nature' => fake()->sentence(1, true), 
            'file' => fake()->sentence(10, true), 
        ];
    }
}
