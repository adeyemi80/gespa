<?php

namespace App\Services;

use App\Models\Moyenne;
use Illuminate\Support\Facades\DB;

class ClassementService
{
    /**
     * Recalcule les rangs d'une classe
     */
    public function recalculerClasse(int $classeId, int $anneeId): void
    {
        DB::transaction(function () use ($classeId, $anneeId) {

            // Reset rangs
            Moyenne::where([
                'classe_id' => $classeId,
                'annee_id'  => $anneeId,
            ])->update([
                'rang_trimestre' => null,
                'rang_annuel'    => null,
            ]);

            // ======================
            // RANGS TRIMESTRIELS
            // ======================
            $trimestres = Moyenne::where([
                'classe_id' => $classeId,
                'annee_id'  => $anneeId,
            ])->pluck('trimestre_id')->unique();

            foreach ($trimestres as $trimestreId) {

                $moyennes = Moyenne::with('inscription.eleve')
                    ->where([
                        'classe_id' => $classeId,
                        'annee_id'  => $anneeId,
                        'trimestre_id' => $trimestreId,
                    ])
                    ->whereNotNull('moyenne_trimestrielle')
                    ->orderByDesc('moyenne_trimestrielle')
                    ->get();

                $this->attribuerRangs(
                    $moyennes,
                    'rang_trimestre',
                    'moyenne_trimestrielle'
                );
            }

            // ======================
            // RANGS ANNUELS
            // ======================
            $moyennesAnnuelles = Moyenne::with('inscription.eleve')
                ->where([
                    'classe_id' => $classeId,
                    'annee_id'  => $anneeId,
                    'trimestre_id' => 3,
                ])
                ->whereNotNull('moyenne_annuelle')
                ->orderByDesc('moyenne_annuelle')
                ->get();

            $this->attribuerRangs(
                $moyennesAnnuelles,
                'rang_annuel',
                'moyenne_annuelle'
            );
        });
    }

    /**
     * Attribution des rangs
     */
    private function attribuerRangs($collection, string $champRang, string $champMoyenne): void
    {
        $rang = 1;
        $rangReel = 1;
        $precedent = null;

        foreach ($collection as $item) {

            $moyenne = $item->$champMoyenne;

            if ($precedent !== null && bccomp((string)$moyenne, (string)$precedent, 2) !== 0) {
                $rangReel = $rang;
            }

            $sexe = strtoupper($item->inscription->eleve->sexe ?? 'M');

            $suffixe = $rangReel === 1
                ? ($sexe === 'F' ? 'ère' : 'er')
                : 'ème';

            $item->$champRang = $rangReel . $suffixe;
            $item->save();

            $precedent = $moyenne;
            $rang++;
        }
    }
}