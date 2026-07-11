<?php

namespace App\Http\Controllers;

use App\Models\Versement;
use App\Models\Investissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class VersementController extends Controller
{
    /**
     * Liste des versements
     */
    public function index(Request $request)
    {
        $query = Versement::with([
            'investissement.investisseur'
        ]);

        // Recherche
        if ($request->filled('search')) {

            $search = $request->search;

            $query->whereHas(
                'investissement.investisseur',
                function ($q) use ($search) {

                    $q->where('nom', 'ILIKE', "%{$search}%")
                      ->orWhere('prenom', 'ILIKE', "%{$search}%")
                      ->orWhere('telephone', 'ILIKE', "%{$search}%");
                }
            );
        }


        // Filtre investissement
        if ($request->filled('investissement_id')) {

            $query->where(
                'investissement_id',
                $request->investissement_id
            );
        }


        // Filtre période
        if ($request->filled('date_debut')) {

            $query->whereDate(
                'date_versement',
                '>=',
                $request->date_debut
            );
        }


        if ($request->filled('date_fin')) {

            $query->whereDate(
                'date_versement',
                '<=',
                $request->date_fin
            );
        }


        $versements = $query
            ->orderByDesc('date_versement')
            ->paginate(15);


        $investissements = Investissement::with('investisseur')
            ->where('statut', 'actif')
            ->get();


        return view(
            'investissements.versements.index',
            compact(
                'versements',
                'investissements'
            )
        );
    }

    /**
     * Formulaire d'enregistrement
     */
    public function create()
    {

        $investissements = Investissement::with('investisseur')
            ->where('statut', 'actif')
            ->get();


        return view(
            'investissements.versements.create',
            compact('investissements')
        );
    }

    /**
     * Enregistrer un versement
     */
    public function store(Request $request)
    {

        $validated = $request->validate([

            'investissement_id'
                => 'required|exists:investissements,id',

            'date_versement'
                => 'required|date',

            'montant'
                => 'required|numeric|min:1',

            'mode_paiement'
                => 'nullable|string|max:100',

            'reference'
                => 'nullable|string|max:100',

            'observation'
                => 'nullable|string',

        ]);



        DB::transaction(function () use ($validated) {


            Versement::create($validated);


            /*
             Possibilité future :
             mise à jour automatique
             du capital investi
            */

        });



        return redirect()
            ->route('versements.index')
            ->with(
                'success',
                'Versement enregistré avec succès.'
            );
    }



    /**
     * Affichage détail
     */
    public function show(Versement $versement)
    {

        $versement->load([
            'investissement.investisseur'
        ]);


        return view(
            'investissements.versements.show',
            compact('versement')
        );
    }




    /**
     * Formulaire modification
     */
    public function edit(Versement $versement)
    {

        $investissements = Investissement::with('investisseur')
            ->where('statut', 'actif')
            ->get();


        return view(
            'investissements.versements.edit',
            compact(
                'versement',
                'investissements'
            )
        );
    }





    /**
     * Mise à jour
     */
    public function update(
        Request $request,
        Versement $versement
    )
    {

        $validated = $request->validate([

            'investissement_id'
                => 'required|exists:investissements,id',

            'date_versement'
                => 'required|date',

            'montant'
                => 'required|numeric|min:1',

            'mode_paiement'
                => 'nullable|string|max:100',

            'reference'
                => 'nullable|string|max:100',

            'observation'
                => 'nullable|string',

        ]);



        DB::transaction(function () use (
            $versement,
            $validated
        ) {


            $versement->update($validated);

        });



        return redirect()
            ->route('versements.index')
            ->with(
                'success',
                'Versement modifié avec succès.'
            );
    }





    /**
     * Suppression
     */
    public function destroy(
        Versement $versement
    )
    {


        DB::transaction(function () use ($versement) {


            $versement->delete();


        });



        return redirect()
            ->route('versements.index')
            ->with(
                'success',
                'Versement supprimé avec succès.'
            );
    }





    /**
     * Export PDF des versements, groupés par investisseur
     * avec un sous-total par investisseur et un total général.
     *
     * Filtres optionnels (repris de index() + période) :
     * - search (nom/prénom/téléphone investisseur)
     * - investissement_id
     * - date_debut / date_fin (sur date_versement)
     */
    public function exportPdf(Request $request)
    {
         
        $query = Versement::with([
            'investissement.investisseur'
        ]);


        if ($request->filled('search')) {

            $search = $request->search;

            $query->whereHas(
                'investissement.investisseur',
                function ($q) use ($search) {

                    $q->where('nom', 'ILIKE', "%{$search}%")
                      ->orWhere('prenom', 'ILIKE', "%{$search}%")
                      ->orWhere('telephone', 'ILIKE', "%{$search}%");
                }
            );
        }


        if ($request->filled('investissement_id')) {

            $query->where(
                'investissement_id',
                $request->investissement_id
            );
        }


        if ($request->filled('date_debut')) {

            $query->whereDate(
                'date_versement',
                '>=',
                $request->date_debut
            );
        }


        if ($request->filled('date_fin')) {

            $query->whereDate(
                'date_versement',
                '<=',
                $request->date_fin
            );
        }


        $versements = $query
            ->orderBy('date_versement')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Regroupement par investisseur
        |--------------------------------------------------------------------------
        | Chaque groupe contient : l'investisseur, ses versements,
        | et le sous-total correspondant.
        */

        $groupes = $versements
            ->groupBy(function ($versement) {

                return $versement->investissement->investisseur->id
                    ?? 'inconnu';

            })
            ->map(function ($versementsDuGroupe) {

                $investisseur = $versementsDuGroupe
                    ->first()
                    ->investissement
                    ->investisseur
                    ?? null;

                return [

                    'investisseur' => $investisseur,

                    'versements' => $versementsDuGroupe,

                    'sous_total' => $versementsDuGroupe->sum('montant'),

                ];

            })
            ->sortBy(function ($groupe) {

                return $groupe['investisseur']->nom ?? 'zzz';

            });


        $totalGeneral = $versements->sum('montant');


        $periode = [

            'debut' => $request->filled('date_debut')
                ? \Carbon\Carbon::parse($request->date_debut)
                : null,

            'fin' => $request->filled('date_fin')
                ? \Carbon\Carbon::parse($request->date_fin)
                : null,

        ];


        $pdf = Pdf::loadView(
            'investissements.versements.pdf',
            compact(
                'groupes',
                'totalGeneral',
                'periode'
            )
        )->setPaper('a4', 'portrait');


        $nomFichier = 'versements-'.now()->format('Y-m-d_His').'.pdf';
       
        // return view('investissements.versements.pdf', compact('groupes', 'totalGeneral', 'periode'));

        return $pdf->download($nomFichier);

    }

}