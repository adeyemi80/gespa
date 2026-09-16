<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnneeClasseFrais;
use App\Models\InscriptionFrais;
use App\Models\Annee;
use App\Models\Classe;
use App\Models\Cycle;
use Illuminate\Support\Facades\DB;

class AnneeClasseFraisController extends Controller
{
    public function index(Request $request)
    {
        $query = AnneeClasseFrais::with([
            'annee',
            'classe.cycle',
            'frais'
        ]);

        // FILTRE ANNÉE
        if ($request->filled('annee_id')) {
            $query->where('annee_id', $request->annee_id);
        }

        // FILTRE CYCLE
        if ($request->filled('cycle_id')) {
            $query->whereHas('classe', function ($q) use ($request) {
                $q->where('cycle_id', $request->cycle_id);
            });
        }

        // FILTRE CLASSE
        if ($request->filled('classe_id')) {
            $query->where('classe_id', $request->classe_id);
        }

        // TRI
        $data = $query
            ->orderBy('annee_id')
            ->orderBy('classe_id')
            ->get();

        // DONNÉES POUR LES SELECTS
        $annees = Annee::orderBy('nom')->get();
        $cycles = Cycle::orderBy('nom')->get();
        $classes = Classe::orderBy('nom')->get();

        return view(
            'frais.annee_classe_frais.index',
            compact(
                'data',
                'annees',
                'cycles',
                'classes'
            )
        );
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'montant' => 'required|numeric|min:0',
    ]);

    DB::transaction(function () use ($request, $id) {

        // Tarif année + classe + frais
        $item = AnneeClasseFrais::findOrFail($id);

        $nouveauMontant = (float) $request->montant;

        /*
         * 1. Mise à jour du tarif dans annee_classe_frais
         */
        $item->update([
            'montant' => $nouveauMontant,
        ]);

        /*
         * 2. Récupération des élèves concernés
         *
         * inscription_frais possède inscription_id,
         * tandis que classe_id se trouve dans inscriptions.
         */
        $inscriptionFrais = InscriptionFrais::where('frais_id', $item->frais_id)
            ->where('annee_id', $item->annee_id)
            ->whereHas('inscription', function ($query) use ($item) {
                $query->where('classe_id', $item->classe_id);
            })
            ->get();

        /*
         * 3. Synchronisation des frais des élèves
         */
        foreach ($inscriptionFrais as $ligne) {

            // Ne jamais modifier le montant déjà payé
            $montantPaye = (float) ($ligne->montant_paye ?? 0);

            /*
             * Calcul du nouveau reste
             */
            $reste = max(
                0,
                $nouveauMontant - $montantPaye
            );

            /*
             * Détermination du statut avec
             * les valeurs AUTORISÉES par PostgreSQL.
             */
            if ($montantPaye <= 0) {
                $statut = 'non_payé';
            } elseif ($montantPaye < $nouveauMontant) {
                $statut = 'partiellement_payé';
            } else {
                $statut = 'soldé';
            }

            /*
             * Mise à jour de la ligne élève
             */
            $ligne->update([
                'montant_frais' => $nouveauMontant,
                'montant_total' => $nouveauMontant,
                'reste'        => $reste,
                'statut'       => $statut,
            ]);
        }
    });

    return back()->with(
        'success',
        'Montant mis à jour avec succès et frais des élèves synchronisés.'
    );
}
    public function destroy($id)
    {
        $item = AnneeClasseFrais::findOrFail($id);

        $item->delete();

        return back()->with(
            'success',
            'Ligne supprimée avec succès.'
        );
    }
}