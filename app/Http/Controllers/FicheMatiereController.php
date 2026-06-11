<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annee;
use App\Models\Trimestre;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Inscription;
use App\Models\Note;
use Barryvdh\DomPDF\Facade\Pdf;

class FicheMatiereController extends Controller
{
    public function telecharger(Request $request)
    {
        $request->validate([
            'annee_id'     => 'required|exists:annees,id',
            'trimestre_id' => 'required|exists:trimestres,id',
            'classe_id'    => 'required|exists:classes,id',
            'matiere_id'   => 'required|exists:matieres,id',
        ]);

        $annee     = Annee::findOrFail($request->annee_id);
        $trimestre = Trimestre::findOrFail($request->trimestre_id);
        $classe    = Classe::findOrFail($request->classe_id);
        $matiere   = Matiere::findOrFail($request->matiere_id);
        $coef      = $matiere->coefficient;

        $eleves = Inscription::where('inscriptions.annee_id', $request->annee_id)
            ->where('inscriptions.classe_id', $request->classe_id)
            ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
            ->orderBy('eleves.nom')
            ->orderBy('eleves.prenom')
            ->select('eleves.*')
            ->get();

        $notes = Note::where([
            'annee_id'     => $request->annee_id,
            'trimestre_id' => $request->trimestre_id,
            'matiere_id'   => $request->matiere_id,
        ])->get()->keyBy('eleve_id');

        $resultats = [];
        foreach ($eleves as $eleve) {
            $note = $notes[$eleve->id] ?? null;
            $moy  = $note
                ? collect([$note->devoir, $note->mcc, $note->composition])
                    ->filter()->avg()
                : null;

            $resultats[] = [
                'eleve'    => $eleve,
                'note'     => $note,
                'moy_epe'  => $note?->epe ?? null,
                'moyenne'  => $moy,
                'moy_coef' => $moy ? round($moy * $coef, 2) : null,
                'rang'     => '',
            ];
        }

        $pdf = Pdf::loadView('fiches.pdf_une_matiere', compact(
            'annee', 'trimestre', 'classe', 'matiere', 'coef', 'resultats'
        ))->setPaper('A4', 'landscape');

        return $pdf->download("Fiche_{$classe->nom}_{$matiere->nom}_{$trimestre->nom}.pdf");
    }
}