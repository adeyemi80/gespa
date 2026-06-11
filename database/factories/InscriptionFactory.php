<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inscription>
 */
class InscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->sentence(1, true), 
            'sexe' => fake()->sentence(1, true),           
            'date' => fake()->date, 
            'lieu' => fake()->sentence(1, true), 
            'nationalite' => fake()->sentence(1, true),  
            'telephone' => fake()->phoneNumber(),          
            'frais' => fake()->sentence(1, true),
            
            
        ];
    }
}
