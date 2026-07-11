<?php

namespace App\Http\Controllers;

use App\Models\PaiementBenefice;
use App\Models\Repartition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaiementBeneficeController extends Controller
{

    /**
     * Liste des paiements de bénéfices
     */
    public function index(Request $request)
    {

        $paiements = PaiementBenefice::with([
            'repartition.investissement.investisseur',
            'repartition.benefice'
        ])
        ->latest('date_paiement')
        ->paginate(20);


        return view(
            'investissements.paiements_benefices.index',
            compact('paiements')
        );
    }



    /**
     * Formulaire d'enregistrement
     */
   /**
 * Formulaire d'enregistrement
 */
public function create()
{

    $repartitions = Repartition::with([
        'investissement.investisseur',
        'benefice'
    ])
    ->withSum('paiements as total_paye', 'montant')
    ->get()
    ->filter(function ($repartition) {

        $totalPaye = $repartition->total_paye ?? 0;

        return $repartition->montant > $totalPaye;

    })
    ->values();


    return view(
        'investissements.paiements_benefices.create',
        compact('repartitions')
    );
}


    /**
     * Enregistrer un paiement
     */
    public function store(Request $request)
    {

        $request->validate([

            'repartition_id'
                => 'required|exists:repartitions,id',

            'date_paiement'
                => 'required|date',

            'montant'
                => 'required|numeric|min:1',

            'mode_paiement'
                => 'required|string|max:100',

            'reference'
                => 'nullable|string|max:100',

            'observation'
                => 'nullable|string',

        ]);



        DB::transaction(function () use ($request) {


            $repartition = Repartition::findOrFail(
                $request->repartition_id
            );


            /*
            Montant déjà payé
            */

            $dejaPaye = $repartition
                ->paiements()
                ->sum('montant');



            /*
            Reste à payer
            */

            $reste = $repartition->montant - $dejaPaye;



            if($request->montant > $reste){

                abort(
                    422,
                    "Le montant dépasse le bénéfice restant à payer."
                );

            }



            PaiementBenefice::create([

                'repartition_id'
                    => $request->repartition_id,

                'date_paiement'
                    => $request->date_paiement,

                'montant'
                    => $request->montant,

                'mode_paiement'
                    => $request->mode_paiement,

                'reference'
                    => $request->reference,

                'observation'
                    => $request->observation,

            ]);


        });



        return redirect()

            ->route('paiements-benefices.index')

            ->with(
                'success',
                'Paiement du bénéfice enregistré avec succès.'
            );

    }





    /**
     * Détails d'un paiement
     */
    public function show(
        PaiementBenefice $paiementBenefice
    )
    {

        $paiementBenefice->load([

            'repartition.investissement.investisseur',

            'repartition.benefice'

        ]);


        return view(
            'investissements.paiements_benefices.show',
            compact('paiementBenefice')
        );

    }





    /**
     * Formulaire modification
     */
    public function edit(
        PaiementBenefice $paiementBenefice
    )
    {

        $repartitions = Repartition::with([

            'investissement.investisseur',

            'benefice'

        ])
        ->get();


        return view(
            'investissements.paiements_benefices.edit',
            compact(
                'paiementBenefice',
                'repartitions'
            )
        );

    }





    /**
     * Mise à jour
     */
    public function update(
        Request $request,
        PaiementBenefice $paiementBenefice
    )
    {


        $request->validate([

            'repartition_id'
                => 'required|exists:repartitions,id',

            'date_paiement'
                => 'required|date',

            'montant'
                => 'required|numeric|min:1',

            'mode_paiement'
                => 'required|string|max:100',

            'reference'
                => 'nullable|string|max:100',

            'observation'
                => 'nullable|string',

        ]);




        DB::transaction(function () use (
            $request,
            $paiementBenefice
        ){


            $repartition = Repartition::findOrFail(
                $request->repartition_id
            );



            $dejaPaye = $repartition
                ->paiements()
                ->where(
                    'id',
                    '!=',
                    $paiementBenefice->id
                )
                ->sum('montant');



            $reste = $repartition->montant - $dejaPaye;



            if($request->montant > $reste){

                abort(
                    422,
                    "Le montant dépasse le reste disponible."
                );

            }



            $paiementBenefice->update([

                'repartition_id'
                    => $request->repartition_id,

                'date_paiement'
                    => $request->date_paiement,

                'montant'
                    => $request->montant,

                'mode_paiement'
                    => $request->mode_paiement,

                'reference'
                    => $request->reference,

                'observation'
                    => $request->observation,

            ]);



        });



        return redirect()

            ->route('paiements-benefices.index')

            ->with(
                'success',
                'Paiement modifié avec succès.'
            );

    }





    /**
     * Suppression
     */
    public function destroy(
        PaiementBenefice $paiementBenefice
    )
    {

        $paiementBenefice->delete();


        return redirect()

            ->route('paiements-benefices.index')

            ->with(
                'success',
                'Paiement supprimé avec succès.'
            );

    }

}