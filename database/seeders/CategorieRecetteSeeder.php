<?php

namespace Database\Seeders;

use App\Models\CategorieRecette;
use Illuminate\Database\Seeder;

class CategorieRecetteSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['code' => 'SCO',    'nom' => 'Scolarité'],
            ['code' => 'INS',    'nom' => "Inscription"],
            ['code' => 'REI',  'nom' => 'Réinscription'],
            ['code' => 'UNI',    'nom' => 'Uniforme'],
            ['code' => 'SPO',   'nom' => 'Tenue de sport'],
            ['code' => 'BEP',    'nom' => 'Dossier de BEPC'],
            ['code' => 'BAC',     'nom' => 'Dossier de BAC'],
            ['code' => 'LAC', 'nom' => 'Lacoste uniforme'],
            ['code' => 'SEJ',  'nom' => 'Séjour'],
            ['code' => 'SOR',  'nom' => 'Sortie pédagogique'],
            ['code' => 'NOE',    'nom' => 'Fête Noël'],
            ['code' => 'TRA',  'nom' => 'Transport'],
            ['code' => 'ASS',   'nom' => 'Assurance Scolaire'],
            ['code' => 'INF',    'nom' => 'Informatique'],
            ['code' => 'TES', 'nom' => "Test d'entrée"],
        ];

        foreach ($categories as $categorie) {
            CategorieRecette::updateOrCreate(
                ['code' => $categorie['code']],
                ['nom' => $categorie['nom'], 'actif' => true]
            );
        }
    }
}