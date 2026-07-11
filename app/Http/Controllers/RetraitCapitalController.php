<?php

namespace App\Http\Controllers;

use App\Models\RetraitCapital;
use App\Models\Investissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RetraitCapitalController extends Controller
{
    /**
     * Liste des retraits de capital
     */
    public function index(Request $request)
    {
        $query = RetraitCapital::with([
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


        $retraits = $query
            ->orderByDesc('date_retrait')
            ->paginate(15);


        return view(
            'investissements.retraits_capital.index',
            compact('retraits')
        );
    }



    /**
     * Formulaire ajout retrait
     */
    public function create()
    {

        $investissements = Investissement::with(
            'investisseur'
        )
        ->where('statut','actif')
        ->orderBy('id','desc')
        ->get();


        return view(
            'investissements.retraits_capital.create',
            compact('investissements')
        );
    }




    /**
     * Enregistrement d'un retrait
     */
    public function store(Request $request)
    {

        $validated = $request->validate([

            'investissement_id'
                => 'required|exists:investissements,id',

            'date_retrait'
                => 'required|date',

            'montant'
                => 'required|numeric|min:1',

            'mode_retrait'
                => 'nullable|string|max:100',

            'reference'
                => 'nullable|string|max:100',

            'motif'
                => 'nullable|string',

            'observation'
                => 'nullable|string',

        ]);



        DB::transaction(function () use ($validated) {


            $investissement = Investissement::findOrFail(
                $validated['investissement_id']
            );


            /*
            |--------------------------------------------------------------------------
            | Vérification du capital disponible
            |--------------------------------------------------------------------------
            */


            $totalVersements = $investissement
                ->versements()
                ->sum('montant');


            $totalRetraits = $investissement
                ->retraitsCapital()
                ->sum('montant');


            $capitalDisponible =
                $totalVersements - $totalRetraits;



            if($validated['montant'] > $capitalDisponible)
            {
                 throw \Illuminate\Validation\ValidationException::withMessages([
                 'montant' => 'Le montant du retrait dépasse le capital disponible.',
                 ]);
            }



            RetraitCapital::create($validated);


        });



        return redirect()
            ->route('retraits-capital.index')
            ->with(
                'success',
                'Retrait de capital enregistré avec succès.'
            );
    }




    /**
     * Affichage détail
     */
    public function show(
        RetraitCapital $retraitCapital
    )
    {

        $retraitCapital->load([
            'investissement.investisseur'
        ]);


        return view(
            'investissements.retraits_capital.show',
            compact('retraitCapital')
        );
    }





    /**
     * Formulaire modification
     */
    public function edit(
        RetraitCapital $retraitCapital
    )
    {

        $investissements = Investissement::with(
            'investisseur'
        )
        ->orderBy('id','desc')
        ->get();



        return view(
            'investissements.retraits_capital.edit',
            compact(
                'retraitCapital',
                'investissements'
            )
        );
    }





    /**
     * Mise à jour
     */
    public function update(
        Request $request,
        RetraitCapital $retraitCapital
    )
    {


        $validated = $request->validate([

            'investissement_id'
                => 'required|exists:investissements,id',

            'date_retrait'
                => 'required|date',

            'montant'
                => 'required|numeric|min:1',

            'mode_retrait'
                => 'nullable|string|max:100',

            'reference'
                => 'nullable|string|max:100',

            'motif'
                => 'nullable|string',

            'observation'
                => 'nullable|string',

        ]);



        DB::transaction(function () use (
            $validated,
            $retraitCapital
        ) {


            $investissement = Investissement::findOrFail(
                $validated['investissement_id']
            );


            /*
             * On exclut le retrait actuel
             */

            $totalVersements =
                $investissement
                ->versements()
                ->sum('montant');


            $totalRetraits =
                $investissement
                ->retraitsCapital()
                ->where(
                    'id',
                    '!=',
                    $retraitCapital->id
                )
                ->sum('montant');



            $disponible =
                $totalVersements - $totalRetraits;



            if($validated['montant'] > $disponible)
            {
                abort(
                    422,
                    "Capital insuffisant pour ce retrait."
                );
            }



            $retraitCapital->update(
                $validated
            );


        });



        return redirect()
            ->route('investissements.retraits-capital.index')
            ->with(
                'success',
                'Retrait modifié avec succès.'
            );

    }





    /**
     * Suppression
     */
    public function destroy(
        RetraitCapital $retraitCapital
    )
    {

        $retraitCapital->delete();


        return redirect()
            ->route('retraits-capital.index')
            ->with(
                'success',
                'Retrait supprimé avec succès.'
            );

    }
}