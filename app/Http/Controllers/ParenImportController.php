<?php

namespace App\Http\Controllers;

use App\Imports\ParenImport;
use App\Exports\ParensTemplateMultiFeuillesExport;
use App\Models\Paren;
use App\Models\User;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Annee;
use App\Models\Eleve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Illuminate\Support\Facades\Log;

class ParenImportController extends Controller
{
    /**
     * 🔵 Formulaire d'import
     */
    public function form()
    {
        $classes = Classe::orderByNiveau('ordre')->get();
        $annees = Annee::with('classes')->get();
        $cycles = Cycle::all();
        return view('parens.import.form', compact('classes', 'annees', 'cycles'));
    }

    /**
     * 🔵 Télécharger le modèle Excel
     */
    public function telechargerModele(Request $request)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'annee_id'  => 'required|exists:annees,id',
        ]);

        $classe = Classe::findOrFail($request->classe_id);
        $annee  = Annee::findOrFail($request->annee_id);

        return Excel::download(
            new ParensTemplateMultiFeuillesExport($classe->id, $annee->id),
            'modele_import_parents_'.$classe->nom.'_'.$annee->nom.'.xlsx'
        );
    }

    /**
     * 🔵 Prévisualisation (AUCUNE insertion)
     */
    public function previsualiser(Request $request)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'annee_id'  => 'required|exists:annees,id',
            'fichier'   => 'required|file|mimes:xlsx,csv',
        ]);

        $import = new ParenImport($request->classe_id, $request->annee_id);
        Excel::import($import, $request->file('fichier'));

        // ✅ Stockage JSON sécurisé
        Storage::put('temp/parens_valides.json', json_encode($import->valides, JSON_UNESCAPED_UNICODE));
        Storage::put('temp/parens_erreurs.json', json_encode($import->erreurs, JSON_UNESCAPED_UNICODE));

        return view('parens.import.previsualisation', [
            'valides' => $import->valides,
            'erreurs' => $import->erreurs,
            'classe_id' => $request->classe_id,
            'annee_id' => $request->annee_id,
        ]);
    }

    /**
     * ✅ IMPORT FINAL - VERSION PRODUCTION
     */
   public function validerImport()
{
    if (!Storage::exists('temp/parens_valides.json')) {
        return back()->with('error', 'Aucune donnée valide à importer.');
    }

    $valides = json_decode(Storage::get('temp/parens_valides.json'), true);

    foreach ($valides as $data) {

        DB::transaction(function () use ($data) {

            // 1️⃣ Trouver l'élève EXACT
            $eleve = Eleve::where('matricule', $data['matricule'])
                ->where('classe_id', $data['classe_id'])
                ->where('annee_id', $data['annee_id'])
                ->first();

            if (!$eleve) {
                \Log::warning('Import Parent : élève introuvable', $data);
                return;
            }

            // 2️⃣ Parent unique par téléphone
            $parent = Paren::firstOrCreate(
                ['telephone_parent' => $data['telephone_parent']],
                [
                    'nom_parent'     => $data['nom_parent'],
                    'prenom_parent'  => $data['prenom_parent'],
                    'adresse_parent' => $data['adresse_parent'],
                ]
            );

            // 3️⃣ Créer l'utilisateur parent si absent
            if (!$parent->user_id) {
                $user = User::create([
                    'name' => $parent->nom_parent.' '.$parent->prenom_parent,
                    'email' => $parent->telephone_parent.'@parent.local',
                    'password' => Hash::make('parent123'),
                    'role' => 'parent',
                    'telephone' => $parent->telephone_parent,
                    'must_change_password' => true,
                ]);

                $parent->update(['user_id' => $user->id]);
            }

            // 4️⃣ 🔥 LIAISON DÉFINITIVE
            $eleve->paren_id = $parent->id;
            $eleve->save(); // 🔥 plus fiable que update()
        });
    }

    Storage::delete([
        'temp/parens_valides.json',
        'temp/parens_erreurs.json',
    ]);

    return redirect()
        ->route('parens.import.form')
        ->with('success', 'Parents importés et associés aux élèves avec succès.');
}




}
