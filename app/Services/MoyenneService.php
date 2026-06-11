<?php

namespace App\Services;

use App\Models\Inscription;
use App\Models\Note;
use App\Models\Moyenne;
use App\Models\Conduite;

class MoyenneService
{
    /**
     * Calcule et enregistre les moyennes d'une inscription
     */
    public function calculerMoyennes(int $inscriptionId): void
    {
        $inscription = Inscription::with(['classe.matieres', 'annee.trimestres'])
            ->findOrFail($inscriptionId);

        $anneeId  = $inscription->annee_id;
        $classeId = $inscription->classe_id;

        $notes = Note::with('matiere')
            ->where('inscription_id', $inscriptionId)
            ->where('annee_id', $anneeId)
            ->whereIn('matiere_id', $inscription->classe->matieres->pluck('id'))
            ->get();

        $trimestres = $inscription->annee->trimestres->pluck('id')->prepend(0);

        foreach ($trimestres as $trimestreId) {

            $notesTrimestre = $trimestreId === 0
                ? $notes
                : $notes->where('trimestre_id', $trimestreId);

            $notesValides = $notesTrimestre->filter(fn($n) =>
                $n->moyenne_matiere !== null && $n->moyenne_matiere > 0
            );

            if ($notesValides->isEmpty()) {
                $moyenne = 0;
                $scientifique = 0;
                $litteraire = 0;
            } else {

                $noteConduite = Conduite::where([
                    'inscription_id' => $inscriptionId,
                    'annee_id' => $anneeId,
                    'trimestre_id' => $trimestreId,
                ])->value('note_conduite') ?? 0;

                $somme = $notesValides->sum(fn($n) =>
                    $n->moyenne_matiere * ($n->matiere->coefficient ?? 1)
                );

                $coef = $notesValides->sum(fn($n) =>
                    $n->matiere->coefficient ?? 1
                );

                $moyenne = round(($somme + $noteConduite) / max($coef + 1, 1), 2);

                $scientifique = $this->calculCategorie($notesValides, 'scientifique');
                $litteraire   = $this->calculCategorie($notesValides, 'litteraire');
            }

            Moyenne::updateOrCreate(
                [
                    'inscription_id' => $inscriptionId,
                    'annee_id'       => $anneeId,
                    'trimestre_id'   => $trimestreId,
                ],
                [
                    'classe_id'             => $classeId,
                    'moyenne_trimestrielle' => $moyenne,
                    'moyenne_scientifique'  => $scientifique,
                    'moyenne_litteraire'    => $litteraire,
                ]
            );
        }

        $this->calculerMoyenneAnnuelle($inscriptionId);
    }

    /**
     * Moyenne par catégorie
     */
    public function calculCategorie($notes, string $type): float
    {
        $notesCat = $notes->filter(fn($n) =>
            optional($n->matiere)->type === $type
        );

        $somme = $notesCat->sum(fn($n) =>
            $n->moyenne_matiere * ($n->matiere->coefficient ?? 1)
        );

        $coef = $notesCat->sum(fn($n) =>
            $n->matiere->coefficient ?? 1
        );

        return $coef > 0 ? round($somme / $coef, 2) : 0;
    }

    /**
     * Moyenne annuelle
     */
    public function calculerMoyenneAnnuelle(int $inscriptionId): void
    {
        $moyennes = Moyenne::where('inscription_id', $inscriptionId)
            ->whereIn('trimestre_id', [1, 2, 3])
            ->where('moyenne_trimestrielle', '>', 0)
            ->get();

        if ($moyennes->isEmpty()) {
            return;
        }

        $moyenneAnnuelle = round(
            $moyennes->avg('moyenne_trimestrielle'),
            2
        );

        Moyenne::where([
            'inscription_id' => $inscriptionId,
            'trimestre_id'   => 3,
        ])->update([
            'moyenne_annuelle' => $moyenneAnnuelle
        ]);
    }
}