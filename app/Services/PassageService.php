<?php

namespace App\Services;

use App\Models\Inscription;
use App\Models\Classe;
use App\Models\Annee;
use Illuminate\Support\Facades\DB;

class PassageService
{
    public static function executer(int $anneeActuelleId): void
    {
        DB::transaction(function () use ($anneeActuelleId) {

            $anneeActuelle = Annee::findOrFail($anneeActuelleId);

            $anneeSuivante = Annee::where('id', '>', $anneeActuelleId)
                ->orderBy('id')
                ->firstOrFail();

            $inscriptions = Inscription::where('annee_id', $anneeActuelleId)
                ->whereNotNull('moyenne_annuelle')
                ->get();

            foreach ($inscriptions as $inscription) {

                // 🔹 déterminer la décision
                $decision = $inscription->moyenne_annuelle >= 10
                    ? 'admis'
                    : 'redouble';

                $inscription->update(['decision' => $decision]);

                // 🔹 déterminer la classe cible
                if ($decision === 'admis') {
                    $classeCible = Classe::where(
                        'niveau',
                        $inscription->classe->niveau + 1
                    )->first();
                } else {
                    $classeCible = $inscription->classe;
                }

                if (!$classeCible) {
                    // classe terminale → pas de passage
                    continue;
                }

                // 🔹 créer l'inscription pour l'année suivante
                Inscription::firstOrCreate([
                    'eleve_id' => $inscription->eleve_id,
                    'annee_id' => $anneeSuivante->id,
                ], [
                    'classe_id' => $classeCible->id,
                ]);
            }
        });
    }
}
