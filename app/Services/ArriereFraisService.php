<?php

namespace App\Services;

use App\Models\Annee;
use App\Models\Inscription;
use Illuminate\Support\Facades\DB;

class ArriereFraisService
{
    /**
     * Retourne l'année scolaire précédente.
     */
    public function anneePrecedente(Annee $annee): ?Annee
    {
        return Annee::where('id', '<', $annee->id)
            ->orderByDesc('id')
            ->first();
    }

    /**
     * Calcule l'arriéré d'une ou plusieurs catégories.
     *
     * Exemple :
     * [1]       = uniquement Scolarité
     * [1, 2]    = Scolarité + Inscription
     * [1, 4, 5] = Scolarité + Uniforme + Tenue de sport
     */
    public function montantArriere(
        int $eleveId,
        Annee $annee,
        array $categoriesIds = [1, 17]
    ): float {
        $anneePrecedente = $this->anneePrecedente($annee);

        if (!$anneePrecedente) {
            return 0;
        }

        $inscriptionIds = Inscription::where(
            'eleve_id',
            $eleveId
        )
            ->where(
                'annee_id',
                $anneePrecedente->id
            )
            ->pluck('id');

        if ($inscriptionIds->isEmpty()) {
            return 0;
        }

        return (float) DB::table('inscription_frais')
            ->join(
                'frais',
                'frais.id',
                '=',
                'inscription_frais.frais_id'
            )
            ->whereIn(
                'inscription_frais.inscription_id',
                $inscriptionIds
            )
            ->where(
                'inscription_frais.annee_id',
                $anneePrecedente->id
            )
            ->whereIn(
                'frais.categorie_recette_id',
                $categoriesIds
            )
            ->where(
                'inscription_frais.reste',
                '>',
                0
            )
            ->sum('inscription_frais.reste');
    }

    /**
     * Retourne le détail des arriérés.
     */
    public function detailsArriere(
        int $eleveId,
        Annee $annee,
        array $categoriesIds = [1]
    ) {
        $anneePrecedente = $this->anneePrecedente($annee);

        if (!$anneePrecedente) {
            return collect();
        }

        $inscriptionIds = Inscription::where(
            'eleve_id',
            $eleveId
        )
            ->where(
                'annee_id',
                $anneePrecedente->id
            )
            ->pluck('id');

        if ($inscriptionIds->isEmpty()) {
            return collect();
        }

        return DB::table('inscription_frais')
            ->join(
                'frais',
                'frais.id',
                '=',
                'inscription_frais.frais_id'
            )
            ->leftJoin(
    'categories_recettes',
    'categories_recettes.id',
    '=',
    'frais.categorie_recette_id'
)
            ->whereIn(
                'inscription_frais.inscription_id',
                $inscriptionIds
            )
            ->where(
                'inscription_frais.annee_id',
                $anneePrecedente->id
            )
            ->whereIn(
                'frais.categorie_recette_id',
                $categoriesIds
            )
            ->where(
                'inscription_frais.reste',
                '>',
                0
            )
            ->select([
                'inscription_frais.id',
                'inscription_frais.inscription_id',
                'inscription_frais.frais_id',
                'inscription_frais.annee_id',
                'inscription_frais.montant_frais',
                'inscription_frais.montant_paye',
                'inscription_frais.reste',
                'inscription_frais.statut',

                'frais.nom as frais_nom',

                'categories_recettes.id as categorie_id',
                'categories_recettes.code as categorie_code',
                'categories_recettes.nom as categorie_nom',
            ])
            ->orderBy('categories_recettes.nom')
            ->orderBy('frais.nom')
            ->get();
    }
}