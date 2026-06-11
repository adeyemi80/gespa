<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Annee;
use App\Models\Classe;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Exemple d’appel correct avec factory :
        Annee::factory()
            ->has(Classe::factory()->count(4))
            ->count(10)
            ->create();

        // Ou pour appeler un seeder :
        $this->call([
            AnneeSeeder::class,
            //ClasseSeeder::class,
        ]);
    }
}
