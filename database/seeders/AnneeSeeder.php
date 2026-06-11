<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Annee;

class AnneeSeeder extends Seeder
{
    public function run(): void
    {
        $annees = [
            [
                'nom' => '2022-2023',
                'debut' => '2022-09-01',
                'fin' => '2023-07-01',
                'en_cours' => false,
            ],
            [
                'nom' => '2023-2024',
                'debut' => '2023-09-01',
                'fin' => '2024-07-01',
                'en_cours' => false,
            ],
            [
                'nom' => '2024-2025',
                'debut' => '2024-09-01',
                'fin' => '2025-07-01',
                'en_cours' => true,
            ],
        ];

        foreach ($annees as $data) {
            Annee::updateOrCreate(
                ['nom' => $data['nom']],
                $data
            );
        }
    }
}
