<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annee;
use App\Models\Trimestre;
use App\Models\Classe;
use App\Models\Enseignant;
use App\Models\Matiere;
use App\Models\Inscription;
use PDF;
//FICHE DE NOTES DE TOUTES LES MATIERES D'UNE CLASSE
class FicheController extends Controller
{
    /**
     * Formulaire pour générer les fiches
     */
    public function formulaire()
    {
        $annees = Annee::orderBy('id')->get();
        $trimestres = Trimestre::all();
        $classes = Classe::orderByNiveau()->get();

        return view('fiches.formulaire', compact('annees', 'trimestres', 'classes'));
    }

    /**
     * Générer toutes les fiches pour toutes les matières d’une classe
     * Affichage simple, sans calcul de notes
     */
    public function genererToutesMatieres(Request $request)
    {
        $request->validate([
            'annee_id' => 'required|exists:annees,id',
            'trimestre_id' => 'required|exists:trimestres,id',
            'classe_id' => 'required|exists:classes,id',
        ]);

        $annee = Annee::findOrFail($request->annee_id);
        $trimestre = Trimestre::findOrFail($request->trimestre_id);
        $classe = Classe::findOrFail($request->classe_id);

        // 🔹 Récupérer les inscriptions pour cette année et cette classe
           $inscriptions = Inscription::with('eleve')
    ->where('inscriptions.annee_id', $annee->id)
    ->where('inscriptions.classe_id', $classe->id)
    ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
    ->orderBy('eleves.nom', 'asc')
    ->orderBy('eleves.prenom', 'asc')
    ->select('inscriptions.*') // garde juste les colonnes d'inscriptions
    ->get();


        $eleves = $inscriptions->pluck('eleve');

        // 🔹 Récupérer toutes les matières de la classe
        $matieres = $classe->matieres()->get();

        $fiches = [];

        foreach ($matieres as $matiere) {
            $resultats = [];

            foreach ($eleves as $eleve) {
                $resultats[] = [
                    'eleve' => $eleve,
                    'matiere' => $matiere, // pour le coefficient ou autre info
                ];
            }

            $fiches[] = [
                'matiere' => $matiere,
                'resultats' => $resultats,
            ];
        }

        return view('fiches.toutes_matieres', compact('fiches', 'classe', 'trimestre', 'annee'));
    }
 // Assure-toi d'ajouter cette ligne en haut

public function exportPDF(Request $request)
{
   
    $annee = Annee::findOrFail($request->annee_id);
    $trimestre = Trimestre::findOrFail($request->trimestre_id);
    $classe = Classe::findOrFail($request->classe_id);
   // $enseignant = Enseignant::findOrFail($request->enseignant_id);

    // Récupérer les inscriptions et matières comme avant
    $inscriptions = Inscription::with('eleve')
        ->where('annee_id', $annee->id)
        ->where('classe_id', $classe->id)
        ->get();

    $eleves = $inscriptions->pluck('eleve');

  $matieres = $classe->matieres()->select('nom','coefficient')->get();

    $fiches = [];
    foreach ($matieres as $matiere) {
        $resultats = [];
        foreach ($eleves as $eleve) {
            $resultats[] = [
                'eleve' => $eleve,
                'matiere' => $matiere,
            ];
        }
        $fiches[] = [
            'matiere' => $matiere,
            'resultats' => $resultats,
        ];
    }

    $pdf = PDF::loadView('fiches.pdf_fiche', compact('fiches', 'classe', 'trimestre', 'annee'))
              ->setPaper('A4', 'portrait'); // 'landscape ou portrait';

    return $pdf->download("Fiches_{$classe->nom}_{$trimestre->nom}.pdf");
}


}
