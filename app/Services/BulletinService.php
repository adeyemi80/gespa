<?php
namespace App\Services;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Conduite;
use App\Models\Note;
use App\Models\Moyenne;

class BulletinService
{
    // Coefficient de la conduite (ajustez selon vos règles pédagogiques)
    private const COEFF_CONDUITE = 1;

    public function genererBulletins(int $anneeId, int $classeId, int $trimestreId): array
    {
        $classe = Classe::with(['matieres'])->findOrFail($classeId);
        $annee  = Annee::findOrFail($anneeId);

        $inscriptions = $classe->inscriptions()
            ->where('annee_id', $anneeId)
            ->with('eleve')
            ->get();

        $inscriptionIds = $inscriptions->pluck('id');

        $notes = Note::whereIn('inscription_id', $inscriptionIds)
            ->where('trimestre_id', $trimestreId)
            ->get()
            ->groupBy(['inscription_id', 'matiere_id']);

        $moyennes = Moyenne::whereIn('inscription_id', $inscriptionIds)
            ->where('trimestre_id', $trimestreId)
            ->get()
            ->keyBy('inscription_id');

        $conduites = Conduite::whereIn('inscription_id', $inscriptionIds)
            ->where('trimestre_id', $trimestreId)
            ->get()
            ->keyBy('inscription_id');

        $bulletins = collect();

        foreach ($inscriptions as $inscription) {
            $moyenne  = $moyennes[$inscription->id]  ?? null;
            $conduite = $conduites[$inscription->id] ?? null;
            $noteConduite = $conduite->note_conduite ?? null;

            // ── Notes par matière ──────────────────────────────────────────
            $notesParMatiere = [];
            $somme           = 0.0;
            $totalCoeff      = 0.0;

            foreach ($classe->matieres as $matiere) {
                $note  = $notes[$inscription->id][$matiere->id][0] ?? null;
                $coeff = $matiere->pivot->coefficient ?? 1;
                $moyenneMatiere = $note->moyenne_matiere ?? null;

                $notesParMatiere[$matiere->id] = $note ? [
                    'matiere_id'      => $matiere->id,
                    'matiere_nom'     => $matiere->nom ?? '',
                    'moyenne_interro' => $note->moyenne_interro ?? null,
                    'devoir1'         => $note->devoir1 ?? null,
                    'devoir2'         => $note->devoir2 ?? null,
                    'moyenne_matiere' => $moyenneMatiere,
                ] : null;

                // N'inclure dans la moyenne que les matières avec une note saisie
                if ($moyenneMatiere !== null) {
                    $somme      += (float) $moyenneMatiere * (float) $coeff;
                    $totalCoeff += (float) $coeff;
                }
            }

            // ── Intégrer la conduite dans la moyenne ───────────────────────
            if ($noteConduite !== null) {
                $somme      += (float) $noteConduite * self::COEFF_CONDUITE;
                $totalCoeff += self::COEFF_CONDUITE;
            }

            // ── Moyenne trimestrielle recalculée dynamiquement ─────────────
            $moyenneTrimestre = $totalCoeff > 0
                ? round($somme / $totalCoeff, 2)
                : 0;

            // ── Persister la valeur recalculée ─────────────────────────────
            if ($moyenne) {
                $moyenne->update(['moyenne_trimestrielle' => $moyenneTrimestre]);
            }

            // ── Moyenne annuelle recalculée au T3 ──────────────────────────
            $moyenneAnnuelle = $moyenne->moyenne_annuelle ?? 0;

            if ($trimestreId == 3) {
                $t1 = (float) (Moyenne::where('inscription_id', $inscription->id)
                    ->where('trimestre_id', 1)
                    ->value('moyenne_trimestrielle') ?? 0);

                $t2 = (float) (Moyenne::where('inscription_id', $inscription->id)
                    ->where('trimestre_id', 2)
                    ->value('moyenne_trimestrielle') ?? 0);

                $moyenneAnnuelle = round(($t1 + $t2 + $moyenneTrimestre) / 3, 2);

                if ($moyenne) {
                    $moyenne->update(['moyenne_annuelle' => $moyenneAnnuelle]);
                }
            }

            $bulletins->push([
                'inscription_id'        => $inscription->id,
                'eleve_nom'             => $inscription->eleve->nom    ?? '',
                'eleve_prenom'          => $inscription->eleve->prenom ?? '',
                'classe_id'             => $classe->id,
                'classe_nom'            => $classe->nom ?? '',
                'annee_id'              => $annee->id,
                'annee_nom'             => $annee->nom ?? '',
                'trimestre_id'          => $trimestreId,
                'trimestre_nom'         => (string) $trimestreId,
                'notes'                 => $notesParMatiere,
                'moyenne_trimestrielle' => $moyenneTrimestre,   // ← recalculée
                'moyenne_scientifique'  => $moyenne->moyenne_scientifique ?? 0,
                'moyenne_litteraire'    => $moyenne->moyenne_litteraire   ?? 0,
                'moyenne_annuelle'      => $moyenneAnnuelle,    // ← recalculée au T3
                'rang_trimestre'        => $moyenne->rang_trimestre ?? '',
                'rang_annuel'           => $moyenne->rang_annuel   ?? '',
                'note_conduite'         => $noteConduite ?? 0,
            ]);
        }

        return [
            'classe'    => ['id' => $classe->id, 'nom' => $classe->nom ?? ''],
            'annee'     => ['id' => $annee->id,  'nom' => $annee->nom  ?? ''],
            'bulletins' => $bulletins->values()->toArray(),
        ];
    }
}