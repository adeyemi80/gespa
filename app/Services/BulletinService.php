<?php

namespace App\Services;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Conduite;
use App\Models\Note;
use App\Models\Moyenne;

class BulletinService
{
    public function genererBulletins(int $anneeId, int $classeId, int $trimestreId): array
    {
        $classe = Classe::with(['matieres'])->findOrFail($classeId);
        $annee = Annee::findOrFail($anneeId);

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
            $moyenne = $moyennes[$inscription->id] ?? null;
            $conduite = $conduites[$inscription->id] ?? null;

            $notesParMatiere = [];
            foreach ($classe->matieres as $matiere) {
                $note = $notes[$inscription->id][$matiere->id][0] ?? null;

                $notesParMatiere[$matiere->id] = $note ? [
                    'matiere_id' => $matiere->id,
                    'matiere_nom' => $matiere->nom ?? '',
                    'moyenne_interro' => $note->moyenne_interro ?? null,
                    'devoir1' => $note->devoir1 ?? null,
                    'devoir2' => $note->devoir2 ?? null,
                    'moyenne_matiere' => $note->moyenne_matiere ?? null,
                ] : null;
            }

            $bulletins->push([
                'inscription_id' => $inscription->id,
                'eleve_nom' => $inscription->eleve->nom ?? '',
                'eleve_prenom' => $inscription->eleve->prenom ?? '',
                'classe_id' => $classe->id,
                'classe_nom' => $classe->nom ?? '',
                'annee_id' => $annee->id,
                'annee_nom' => $annee->nom ?? '',
                'trimestre_id' => $trimestreId,
                'trimestre_nom' => (string) $trimestreId,
                'notes' => $notesParMatiere,
                'moyenne_trimestrielle' => $moyenne->moyenne_trimestrielle ?? 0,
                'moyenne_scientifique' => $moyenne->moyenne_scientifique ?? 0,
                'moyenne_litteraire' => $moyenne->moyenne_litteraire ?? 0,
                'moyenne_annuelle' => $moyenne->moyenne_annuelle ?? 0,
                'rang_trimestre' => $moyenne->rang_trimestre ?? '',
                'rang_annuel' => $moyenne->rang_annuel ?? '',
                'note_conduite' => $conduite->note_conduite ?? 0,
            ]);
        }

        return [
            'classe' => [
                'id' => $classe->id,
                'nom' => $classe->nom ?? '',
            ],
            'annee' => [
                'id' => $annee->id,
                'nom' => $annee->nom ?? '',
            ],
            'bulletins' => $bulletins->values()->toArray(),
        ];
    }
}