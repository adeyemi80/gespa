<?php

namespace Database\Factories;
use App\Models\Classe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Matiere>
 */
class MatiereFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
    return [
        'nom' => $this->faker->word,
        'coefficient' => $this->faker->numberBetween(1, 5),
        'classe_id' => Classe::factory(),
    ];
    }
}
