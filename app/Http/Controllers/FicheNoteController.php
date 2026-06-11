<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annee;
use App\Models\Trimestre;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\Note;
use PDF;
//FICHE DE NOTES D'UNE MATIERE DANS UNE CLASSE
class FicheNoteController extends Controller
{
    /**
     * Formulaire de sélection 
     */
     public function index()
    {
         //$classes = Classe::orderByNiveau()->get();
        return view('fiches.index', [
            'annees' => Annee::orderByDesc('id')->get(),
            
        ]);
    }

    public function generer(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'annee_id'     => 'required|exists:annees,id',
            'trimestre_id' => 'required|exists:trimestres,id',
            'classe_id'    => 'required|exists:classes,id',
            'matiere_id'   => 'required|exists:matieres,id',
        ]);

        $annee     = Annee::findOrFail($request->annee_id);
        $trimestre = $annee->trimestres()->findOrFail($request->trimestre_id);
        $classe    = $annee->classes()->findOrFail($request->classe_id);
        $matiere   = $classe->matieres()->findOrFail($request->matiere_id);

        $coef = $matiere->pivot->coef;

        $eleves = Inscription::where('inscriptions.annee_id', $annee->id)
    ->where('inscriptions.classe_id', $classe->id)
    ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
    ->orderBy('eleves.nom')
    ->orderBy('eleves.prenom')
    ->select('eleves.*')
    ->get();

        $notes = Note::where([
                'annee_id'     => $annee->id,
                'trimestre_id' => $trimestre->id,
                'matiere_id'   => $matiere->id,
            ])->get()->keyBy('eleve_id');

        $resultats = [];
        foreach ($eleves as $eleve) {
            $n = $notes[$eleve->id] ?? null;

            $moy = $n
                ? collect([$n->devoir, $n->mcc, $n->composition])->filter()->avg()
                : null;

            $resultats[] = [
    'eleve'    => $eleve,
    'note'     => $n,
    'moy_epe'  => $n?->epe ?? null,
    'moyenne'  => $moy,
    'moy_coef' => $moy ? $moy * $coef : null,
];

        }

        $classement = collect($resultats)
            ->whereNotNull('moyenne')
            ->sortByDesc('moyenne')
            ->values();

        foreach ($classement as $i => $res) {
            $classement[$i]['rang'] = $i + 1;
        }

        return view('fiches.affichage', compact(
            'annee', 'trimestre', 'classe', 'matiere', 'coef',
            'resultats', 'classement'
        ));
    }
    /**
     * Export PDF
     */
    public function pdf(Request $request)
    {
        $request->validate([
            'annee_id'     => 'required',
            'trimestre_id' => 'required',
            'classe_id'    => 'required',
            'matiere_id'   => 'required',
        ]);

        // Réutilisation de la logique de génération
        $viewData = $this->generer($request)->getData();

        $pdf = PDF::loadView('fiches.pdf', (array) $viewData)
            ->setPaper('A4', 'landscape');

        return $pdf->download(
            'Fiche_notes_' .
            $viewData['classe']->nom . '_' .
            $viewData['matiere']->nom . '.pdf'
        );
    }


}
