<?php

namespace App\Services;

use App\Models\Annee;
use App\Models\Inscription;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ArriereFraisService
{
    /**
     * Retourne l'année scolaire précédente.
     */
    public function anneePrecedente(Annee $annee): ?Annee
    {
        return Annee::query()
            ->where('id', '<', $annee->id)
            ->orderByDesc('id')
            ->first();
    }

    /**
     * Calcule le montant total des arriérés d'un élève.
     *
     * Exemples :
     * [1]       = Scolarité
     * [1, 2]    = Scolarité + Inscription
     * [1, 4, 5] = Scolarité + Uniforme + Tenue de sport
     */
    public function montantArriere(
        int $eleveId,
        Annee $annee,
        array $categoriesIds = [1, 17]
    ): float {
        $details = $this->detailsArriere(
            $eleveId,
            $annee,
            $categoriesIds
        );

        return (float) $details->sum(
            fn ($ligne) => (float) $ligne->reste
        );
    }

    /**
     * Retourne le détail des arriérés impayés de l'année précédente.
     *
     * IMPORTANT :
     * L'identifiant "id" correspond à inscription_frais.id.
     * C'est cet identifiant qu'il faut utiliser pour mettre à jour
     * la ligne d'arriéré lors du paiement.
     */
    public function detailsArriere(
        int $eleveId,
        Annee $annee,
        array $categoriesIds = [1, 17]
    ): Collection {
        $anneePrecedente = $this->anneePrecedente($annee);

        if (!$anneePrecedente || empty($categoriesIds)) {
            return collect();
        }

        $inscriptionIds = Inscription::query()
            ->where('eleve_id', $eleveId)
            ->where('annee_id', $anneePrecedente->id)
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
                // Identifiant de la ligne à régler
                'inscription_frais.id as id',

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

    /**
     * Récupère un arriéré précis à partir de son identifiant.
     *
     * L'identifiant fourni doit être inscription_frais.id,
     * et non frais.id ou inscriptions.id.
     */
    public function trouverArriere(int $inscriptionFraisId): ?object
    {
        return DB::table('inscription_frais')
            ->where('id', $inscriptionFraisId)
            ->where('reste', '>', 0)
            ->first();
    }
}