<?php

namespace App\Http\Controllers;

use App\Models\Investisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InvestisseurController extends Controller
{
    /**
     * Liste des investisseurs
     */
    public function index(Request $request)
    {
        $search = $request->input('search');


        $investisseurs = Investisseur::withCount('investissements')
            ->when($search, function ($query, $search) {

                $query->where(function ($q) use ($search) {

                    $q->where('nom', 'ILIKE', "%{$search}%")
                      ->orWhere('prenom', 'ILIKE', "%{$search}%")
                      ->orWhere('telephone', 'ILIKE', "%{$search}%")
                      ->orWhere('email', 'ILIKE', "%{$search}%");

                });

            })
            ->orderBy('nom')
            ->paginate(15)
            ->withQueryString();


        return view(
            'investissements.investisseurs.index',
            compact('investisseurs')
        );
    }



    /**
     * Formulaire de création
     */
    public function create()
    {
        return view(
            'investissements.investisseurs.create'
        );
    }



    /**
     * Enregistrement d'un investisseur
     */
    public function store(Request $request)
    {

        $validated = $request->validate([

            'nom' => [
                'required',
                'string',
                'max:100'
            ],

            'prenom' => [
                'nullable',
                'string',
                'max:100'
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:30'
            ],

            'email' => [
                'nullable',
                'email',
                'max:150',
                'unique:investisseurs,email'
            ],

            'adresse' => [
                'nullable',
                'string'
            ],

            'profession' => [
                'nullable',
                'string',
                'max:150'
            ],

        ]);



        DB::transaction(function () use ($validated) {

            Investisseur::create($validated);

        });



        return redirect()
            ->route('investisseurs.index')
            ->with(
                'success',
                'Investisseur enregistré avec succès.'
            );

    }





    /**
     * Affichage détail investisseur
     */
    public function show(Investisseur $investisseur)
    {

        $investisseur->load([

            'investissements.versements',

            'investissements.retraitsCapital'

        ]);



        return view(
            'investissements.investisseurs.show',
            compact('investisseur')
        );

    }





    /**
     * Formulaire modification
     */
    public function edit(Investisseur $investisseur)
    {

        return view(
            'investissements.investisseurs.edit',
            compact('investisseur')
        );

    }





    /**
     * Mise à jour
     */
    public function update(
        Request $request,
        Investisseur $investisseur
    )
    {


        $validated = $request->validate([


            'nom' => [
                'required',
                'string',
                'max:100'
            ],


            'prenom' => [
                'nullable',
                'string',
                'max:100'
            ],


            'telephone' => [
                'nullable',
                'string',
                'max:30'
            ],


            'email' => [

                'nullable',

                'email',

                'max:150',

                Rule::unique('investisseurs','email')
                    ->ignore($investisseur->id)

            ],


            'adresse' => [
                'nullable',
                'string'
            ],


            'profession' => [
                'nullable',
                'string',
                'max:150'
            ],


        ]);



        DB::transaction(function () use (
            $investisseur,
            $validated
        ) {


            $investisseur->update($validated);


        });



        return redirect()
            ->route('investisseurs.index')
            ->with(
                'success',
                'Investisseur modifié avec succès.'
            );

    }





    /**
     * Suppression
     */
    public function destroy(
        Investisseur $investisseur
    )
    {


        /*
        |--------------------------------------------------------------------------
        | Protection :
        | empêcher suppression si investissement existant
        |--------------------------------------------------------------------------
        */


        if ($investisseur->investissements()->exists()) {


            return redirect()
                ->route('investisseurs.index')
                ->with(
                    'error',
                    'Impossible de supprimer cet investisseur car il possède des investissements.'
                );

        }



        DB::transaction(function () use ($investisseur) {


            $investisseur->delete();


        });



        return redirect()
            ->route('investisseurs.index')
            ->with(
                'success',
                'Investisseur supprimé avec succès.'
            );

    }

}