<?php

namespace App\Actions;

use App\Models\Inscription;
use App\Models\Moyenne;
use App\Models\Note;
use App\Models\Conduite;
use App\Models\Classe;

class CalculerBulletinAction
{
    public function execute(int $inscriptionId, int $trimestreId, array $statsClasse = []): array
    {
        $inscription = Inscription::with([
            'eleve',
            'classe.matieres',
            'annee'
        ])->findOrFail($inscriptionId);

        $moyenne = Moyenne::where('inscription_id', $inscriptionId)
            ->where('trimestre_id', $trimestreId)
            ->first();

        // Charger les notes avec les détails
        $notesBrutes = Note::where('inscription_id', $inscriptionId)
            ->where('trimestre_id', $trimestreId)
            ->with('matiere')
            ->get();

        // Regrouper par matière
        $notes = $notesBrutes->groupBy('matiere_id')->map(function($group) {
            return [
                'moyenne_interro' => $group->avg('moyenne_interro') ?? null,
                'devoir1'         => $group->first()->devoir1 ?? null,
                'devoir2'         => $group->first()->devoir2 ?? null,
                'moyenne_matiere' => $group->first()->moyenne_matiere ?? null,
                'appreciation'    => $this->getAppreciationMatiere($group->first()->moyenne_matiere ?? null),
            ];
        })->toArray();

        $conduite = Conduite::where('inscription_id', $inscriptionId)
            ->where('trimestre_id', $trimestreId)
            ->first();

        $moyenneTrimestre = $moyenne->moyenne_trimestrielle ?? 0;
        $moyenneAnnuelle  = $moyenne->moyenne_annuelle ?? null;

        // Calculer les moyennes T1, T2, T3 pour le 3ème trimestre
        $moyenneT1 = null;
        $moyenneT2 = null;
        $moyenneT3 = null;

        if ($trimestreId == 3) {
            $moyenneT1 = Moyenne::where('inscription_id', $inscriptionId)
                ->where('trimestre_id', 1)
                ->value('moyenne_trimestrielle');

            $moyenneT2 = Moyenne::where('inscription_id', $inscriptionId)
                ->where('trimestre_id', 2)
                ->value('moyenne_trimestrielle');

            $moyenneT3 = $moyenneTrimestre;
        }

        // Déterminer la classe supérieure
        $classeSuperieure = null;
        if ($moyenneAnnuelle >= 10) {
            $ordreActuel    = $inscription->classe->ordre;
            $classeSuivante = Classe::where('ordre', $ordreActuel + 1)
                ->where('cycle_id', $inscription->classe->cycle_id)
                ->first();

            if ($classeSuivante) {
                $classeSuperieure = $classeSuivante->nom;
            }
        }

        // Mention : moyenne_annuelle au T3, moyenne_trimestrielle aux T1 et T2
        $moyennePourMention = $trimestreId == 3 ? ($moyenneAnnuelle ?? 0) : $moyenneTrimestre;

        return [
            'inscription' => $inscription,
            'classe'      => $inscription->classe,
            'annee'       => $inscription->annee,
            'trimestre'   => $inscription->annee->trimestres->firstWhere('id', $trimestreId),

            'notes' => $notes,

            'noteConduite'         => $conduite->note_conduite ?? 0,
            'appreciationConduite' => $conduite->appreciation ?? $this->getAppreciationConduite($conduite->note_conduite ?? 0),

            'moyenne_trimestre'    => $moyenneTrimestre,
            'moyenne_scientifique' => $moyenne->moyenne_scientifique ?? 0,
            'moyenne_litteraire'   => $moyenne->moyenne_litteraire ?? 0,

            'moyenne_annuelle' => $moyenneAnnuelle,

            'rang_trimestre' => $moyenne->rang_trimestre ?? null,
            'rang_annuel'    => $moyenne->rang_annuel ?? null,

            'mention'      => $this->getMention($moyennePourMention, $trimestreId),
            'appreciation' => $this->getAppreciationGenerale($moyenneTrimestre, $moyenneAnnuelle, $trimestreId),

            'moyenneT1' => $moyenneT1,
            'moyenneT2' => $moyenneT2,
            'moyenneT3' => $moyenneT3,

            'decision' => $moyenneAnnuelle >= 10
                ? 'Passe en ' . ($classeSuperieure ?? 'classe supérieure')
                : 'Redouble',

            'classe_superieure' => $classeSuperieure,

            // Stats de classe (calculées en dehors ou passées en paramètre)
            'total_eleves'      => $statsClasse['total_eleves'] ?? null,
            'plusFaibleMoyenne' => $statsClasse['plusFaibleMoyenne'] ?? null,
            'plusForteMoyenne'  => $statsClasse['plusForteMoyenne'] ?? null,
        ];
    }

    /**
     * T1 & T2 → moyenne_trimestrielle
     * T3      → moyenne_annuelle
     */
    private function getMention(float $moyenne, int $trimestreId): string
    {
        return match (true) {
            $moyenne >= 16 => 'FÉLICITATION',
            $moyenne >= 14 => 'TABLEAU D\'HONNEUR',
            $moyenne >= 12 => 'ENCOURAGEMENT',
            $moyenne >= 10 => 'AVERTISSEMENT',
            default        => 'BLAME'
        };
    }

    private function getAppreciationGenerale($moyenneTrimestre = null, $moyenneAnnuelle = null, $trimestreId = null)
    {
        if ($trimestreId == 3) {
            $moyenne = $moyenneAnnuelle;
        } else {
            $moyenne = $moyenneTrimestre;
        }

        if ($moyenne === null) {
            return '-';
        }

        return match (true) {
            $moyenne >= 18 => 'Élève Travailleur, Travail Excellent',
            $moyenne >= 16 => 'Élève Très Bon, Travail Très Bien',
            $moyenne >= 14 => 'Élève Bon, Travail Bien',
            $moyenne >= 12 => 'Élève Correct, Travail Assez Bien',
            $moyenne >= 10 => 'Élève Moyen, Travail Passable',
            default        => 'Élève Faible, Travail Insuffisant'
        };
    }

    private function getAppreciationMatiere($moyenne_matiere)
    {
        if ($moyenne_matiere === null) return '-';

        return match (true) {
            $moyenne_matiere >= 18 => 'Excellent',
            $moyenne_matiere >= 16 => 'Très Bien',
            $moyenne_matiere >= 14 => 'Bien',
            $moyenne_matiere >= 12 => 'Assez Bien',
            $moyenne_matiere >= 10 => 'Passable',
            $moyenne_matiere >= 8  => 'Insuffisant',
            $moyenne_matiere >= 6  => 'Faible',
            $moyenne_matiere >= 4  => 'Très Faible',
            default                => 'Médiocre'
        };
    }

    private function getAppreciationConduite($noteConduite)
    {
        if ($noteConduite === null) return '-';

        return match (true) {
            $noteConduite >= 16 => 'Élève d\'une très bonne conduite',
            $noteConduite >= 14 => 'Élève d\'une bonne conduite',
            $noteConduite >= 12 => 'Élève soumis(e) et attentif(ve)',
            $noteConduite >= 10 => 'Élève correct',
            default             => 'Élève nécessitant attention'
        };
    }

    public function getBulletinsClasse(int $classeId, int $anneeId, int $trimestreId): array
    {
        $inscriptions = Inscription::where('classe_id', $classeId)
            ->where('annee_id', $anneeId)
            ->get();

        // Calculer les stats de classe
        $moyennesClasse = Moyenne::whereIn('inscription_id', $inscriptions->pluck('id'))
            ->where('trimestre_id', $trimestreId)
            ->pluck('moyenne_trimestrielle')
            ->filter()
            ->map(fn($m) => (float)$m);

        $statsClasse = [
            'total_eleves'      => $inscriptions->count(),
            'plusFaibleMoyenne' => $moyennesClasse->isNotEmpty() ? $moyennesClasse->min() : null,
            'plusForteMoyenne'  => $moyennesClasse->isNotEmpty() ? $moyennesClasse->max() : null,
        ];

        $data = [];

        foreach ($inscriptions as $inscription) {
            $bulletin = $this->execute($inscription->id, $trimestreId, $statsClasse);
            $data[]   = $bulletin;
        }

        return $data;
    }
}