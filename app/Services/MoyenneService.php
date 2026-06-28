<?php

namespace App\Services;

use App\Models\Inscription;
use App\Models\Note;
use App\Models\Moyenne;
use App\Models\Conduite;
use Illuminate\Support\Facades\DB;

class MoyenneService
{
    public function calculerMoyennes(int $inscriptionId): void
    {
        $inscription = Inscription::with(['classe.matieres', 'annee.trimestres'])
            ->findOrFail($inscriptionId);

        $anneeId  = $inscription->annee_id;
        $classeId = $inscription->classe_id;

        // ✅ Coefficients depuis le pivot classe_matiere
        $coefficients = $inscription->classe->matieres
            ->pluck('coefficient', 'id');

        $notes = Note::with('matiere')
            ->where('inscription_id', $inscriptionId)
            ->where('annee_id', $anneeId)
            ->whereIn('matiere_id', $inscription->classe->matieres->pluck('id'))
            ->get();

        $trimestres = $inscription->annee->trimestres->pluck('id');

        foreach ($trimestres as $trimestreId) {

            $notesTrimestre = $notes->where('trimestre_id', $trimestreId);

            $notesValides = $notesTrimestre->filter(
                fn($n) => $n->moyenne_matiere !== null && $n->moyenne_matiere > 0
            );

            if ($notesValides->isEmpty()) {
                $moyenneTrimestre = 0;
                $scientifique     = 0;
                $litteraire       = 0;
            } else {

                // ✅ Coefficient depuis le pivot
                $somme = $notesValides->sum(fn($n) =>
                    $n->moyenne_matiere * ((float) ($coefficients[$n->matiere_id] ?? 1))
                );

                $totalCoeff = $notesValides->sum(fn($n) =>
                    (float) ($coefficients[$n->matiere_id] ?? 1)
                );

                // ✅ Conduite : seulement si saisie (non nulle)
                $noteConduite = Conduite::where([
                    'inscription_id' => $inscriptionId,
                    'annee_id'       => $anneeId,
                    'trimestre_id'   => $trimestreId,
                ])->value('note_conduite');

                if ($noteConduite !== null) {
                    $somme      += (float) $noteConduite;
                    $totalCoeff += 1;
                }

                $moyenneTrimestre = $totalCoeff > 0
                    ? round($somme / $totalCoeff, 2)
                    : 0;

                $scientifique = $this->calculCategorie($notesValides, 'scientifique', $coefficients);
                $litteraire   = $this->calculCategorie($notesValides, 'litteraire', $coefficients);
            }

            Moyenne::updateOrCreate(
                [
                    'inscription_id' => $inscriptionId,
                    'annee_id'       => $anneeId,
                    'trimestre_id'   => $trimestreId,
                ],
                [
                    'classe_id'             => $classeId,
                    'moyenne_trimestrielle' => $moyenneTrimestre,
                    'moyenne_scientifique'  => $scientifique,
                    'moyenne_litteraire'    => $litteraire,
                ]
            );
        }

        $this->calculerMoyenneAnnuelle($inscriptionId);
    }

    // ✅ Coefficients passés en paramètre depuis le pivot
    public function calculCategorie($notes, string $type, $coefficients = []): float
    {
        $notesCat = $notes->filter(
            fn($n) => optional($n->matiere)->type === $type
        );

        $somme = $notesCat->sum(fn($n) =>
            $n->moyenne_matiere * ((float) ($coefficients[$n->matiere_id] ?? 1))
        );

        $coef = $notesCat->sum(fn($n) =>
            (float) ($coefficients[$n->matiere_id] ?? 1)
        );

        return $coef > 0 ? round($somme / $coef, 2) : 0;
    }

    public function calculerMoyenneAnnuelle(int $inscriptionId): void
{
    $moyennes = Moyenne::where('inscription_id', $inscriptionId)
        ->whereIn('trimestre_id', [1, 2, 3])
        ->where('moyenne_trimestrielle', '>', 0)
        ->get();

    if ($moyennes->isEmpty()) {
        return;
    }

    $nbTrimestres = $moyennes->count(); // 1, 2 ou 3 trimestres réels

    // ✅ Diviseur = nombre de trimestres ayant une moyenne > 0
    // Cas 3 trimestres : somme / 3
    // Cas 2 trimestres : somme / 2  (pas de zéro pour le trimestre manquant)
    // Cas 1 trimestre  : somme / 1
    $moyenneAnnuelle = round(
        $moyennes->sum('moyenne_trimestrielle') / $nbTrimestres,
        2
    );

    Moyenne::where([
        'inscription_id' => $inscriptionId,
        'trimestre_id'   => 3,
    ])->update(['moyenne_annuelle' => $moyenneAnnuelle]);
}

    // ✅ Alias utilisé par RecalculerMoyennesListener
    public function mettreAJourMoyennesParInscription(int $inscriptionId): void
    {
        $this->calculerMoyennes($inscriptionId);
    }

   public function calculerClassementAnnuel(int $anneeId, int $classeId): void
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

        // ── Rangs trimestriels ─────────────────────────────────────────────
        $trimestres = Moyenne::where([
            'classe_id' => $classeId,
            'annee_id'  => $anneeId,
        ])->pluck('trimestre_id')->unique();

        foreach ($trimestres as $trimestreId) {

            $moyennes = Moyenne::with('inscription.eleve')
                ->where([
                    'classe_id'    => $classeId,
                    'annee_id'     => $anneeId,
                    'trimestre_id' => $trimestreId,
                ])
                ->whereNotNull('moyenne_trimestrielle')
                ->orderByDesc('moyenne_trimestrielle')
                ->get();

            $this->attribuerRangs($moyennes, 'rang_trimestre', 'moyenne_trimestrielle');
        }

        // ── Rangs annuels ──────────────────────────────────────────────────
        $moyennesAnnuelles = Moyenne::with('inscription.eleve')
            ->where([
                'classe_id'    => $classeId,
                'annee_id'     => $anneeId,
                'trimestre_id' => 3,
            ])
            ->whereNotNull('moyenne_annuelle')
            ->orderByDesc('moyenne_annuelle')
            ->get();

        $this->attribuerRangs($moyennesAnnuelles, 'rang_annuel', 'moyenne_annuelle');
    });
}

private function attribuerRangs($collection, string $champRang, string $champMoyenne): void
{
    $rang      = 1;
    $rangReel  = 1;
    $precedent = null;

    foreach ($collection as $item) {

        $moyenne = $item->$champMoyenne;

        if ($precedent !== null && bccomp((string)$moyenne, (string)$precedent, 2) !== 0) {
            $rangReel = $rang;
        }

        $sexe    = strtoupper($item->inscription->eleve->sexe ?? 'M');
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