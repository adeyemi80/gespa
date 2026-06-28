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

    // ── Charger les notes avec les détails ──────────────────────────────────
    $notesBrutes = Note::where('inscription_id', $inscriptionId)
        ->where('trimestre_id', $trimestreId)
        ->with('matiere')
        ->get();

    // ── Regrouper par matière ───────────────────────────────────────────────
    $notes = $notesBrutes->groupBy('matiere_id')->map(function ($group) {
        return [
            'moyenne_interro' => $group->avg('moyenne_interro') ?? null,
            'devoir1'         => $group->first()->devoir1 ?? null,
            'devoir2'         => $group->first()->devoir2 ?? null,
            'moyenne_matiere' => $group->first()->moyenne_matiere ?? null,
            'appreciation'    => $this->getAppreciationMatiere($group->first()->moyenne_matiere ?? null),
        ];
    })->toArray();

    // ── Conduite ────────────────────────────────────────────────────────────
    $conduite     = Conduite::where('inscription_id', $inscriptionId)
        ->where('trimestre_id', $trimestreId)
        ->first();
    $noteConduite = $conduite->note_conduite ?? null;

    // ── RECALCUL DYNAMIQUE de la moyenne trimestrielle ──────────────────────
    //
    //  Formule : Σ(moyenne_matiere × coeff) + (note_conduite × coeff_conduite)
    //            ─────────────────────────────────────────────────────────────
    //                       Σ(coeff) + coeff_conduite
    //
    $somme      = 0.0;
    $totalCoeff = 0.0;

    foreach ($inscription->classe->matieres as $matiere) {
        // Récupère le coefficient depuis la table pivot (classe_matiere)
       $coeff = $matiere->coefficient ?? 1;
        $notMatiere  = $notes[$matiere->id]['moyenne_matiere'] ?? null;

        // Ignore les matières sans aucune note saisie
        if ($notMatiere === null) {
            continue;
        }

        $somme      += (float) $notMatiere * (float) $coeff;
        $totalCoeff += (float) $coeff;
    }

    // Intégrer la conduite dans la moyenne (coefficient paramétrable)
    $coeffConduite = 1; // ← ajustez selon vos règles pédagogiques
    if ($noteConduite !== null) {
        $somme      += (float) $noteConduite * $coeffConduite;
        $totalCoeff += $coeffConduite;
    }

    // Moyenne calculée dynamiquement (jamais lue depuis la BD)
    $moyenneTrimestre = $totalCoeff > 0
        ? round($somme / $totalCoeff, 2)
        : 0;

    // ── Persister la valeur recalculée pour le rang ─────────────────────────
    if ($moyenne) {
        $moyenne->update(['moyenne_trimestrielle' => $moyenneTrimestre]);
    }

    // ── Moyenne annuelle ────────────────────────────────────────────────────
    $moyenneAnnuelle = $moyenne->moyenne_annuelle ?? null;

    // ── Moyennes T1 / T2 / T3 (affichage au 3ème trimestre) ────────────────
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

              // ✅ On collecte uniquement les trimestres ayant une vraie valeur
$trimestres = array_filter([
    $moyenneT1,
    $moyenneT2,
    $moyenneTrimestre,  // T3 courant — toujours présent ici
], fn($v) => $v !== null && (float)$v > 0);

if (!empty($trimestres)) {
    $moyenneAnnuelle = round(
        array_sum($trimestres) / count($trimestres),
        2
    );

    if ($moyenne) {
        $moyenne->update(['moyenne_annuelle' => $moyenneAnnuelle]);
    }
}
    }

    // ── Classe supérieure ───────────────────────────────────────────────────
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

    // ── Mention (T1/T2 → trimestrielle, T3 → annuelle) ─────────────────────
    $moyennePourMention = $trimestreId == 3
        ? ($moyenneAnnuelle ?? 0)
        : $moyenneTrimestre;

    // ── Retour ──────────────────────────────────────────────────────────────
    return [
        'inscription' => $inscription,
        'classe'      => $inscription->classe,
        'annee'       => $inscription->annee,
        'trimestre'   => $inscription->annee->trimestres->firstWhere('id', $trimestreId),

        'notes' => $notes,

        'noteConduite'         => $noteConduite ?? 0,
        'appreciationConduite' => $conduite->appreciation
            ?? $this->getAppreciationConduite($noteConduite ?? 0),

        'moyenne_trimestre'    => $moyenneTrimestre,   // ← valeur recalculée
        'moyenne_scientifique' => $moyenne->moyenne_scientifique ?? 0,
        'moyenne_litteraire'   => $moyenne->moyenne_litteraire   ?? 0,

        'moyenne_annuelle' => $moyenneAnnuelle,

        'rang_trimestre' => $moyenne->rang_trimestre ?? null,
        'rang_annuel'    => $moyenne->rang_annuel    ?? null,

        'mention'      => $this->getMention($moyennePourMention, $trimestreId),
        'appreciation' => $this->getAppreciationGenerale($moyenneTrimestre, $moyenneAnnuelle, $trimestreId),

        'moyenneT1' => $moyenneT1,
        'moyenneT2' => $moyenneT2,
        'moyenneT3' => $moyenneT3,

        'decision' => ($moyenneAnnuelle ?? 0) >= 10
            ? 'Passe en ' . ($classeSuperieure ?? 'classe supérieure')
            : 'Redouble',

        'classe_superieure' => $classeSuperieure,

        'total_eleves'      => $statsClasse['total_eleves']      ?? null,
        'plusFaibleMoyenne' => $statsClasse['plusFaibleMoyenne'] ?? null,
        'plusForteMoyenne'  => $statsClasse['plusForteMoyenne']  ?? null,
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