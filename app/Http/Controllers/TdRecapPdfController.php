<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annee;
use App\Models\Cycle;
use App\Models\Classe;
use App\Models\Eleve;
use App\Services\TdRecapService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class TdRecapPdfController extends Controller
{
    /* ---------------------------------------------------------------
     * PDF — élève unique
     * -------------------------------------------------------------- */
    public function export(TdRecapService $service)
    {
        $anneeId  = request('annee_id');
        $classeId = request('classe_id');
        $eleveId  = request('eleve_id');
        $mois     = (int) request('mois');

        $eleve  = Eleve::findOrFail($eleveId);
        $classe = Classe::findOrFail($classeId);
        $annee  = Annee::findOrFail($anneeId);

        $anneeDebut = (int) Carbon::parse($annee->debut)->year;

        $resultat = $service->recapMensuel(
            $eleve,
            $classe,
            $anneeId,
            $mois,
            $anneeDebut
        );

        $pdf = Pdf::loadView('pdf.td-recap', [
            'resultat' => $resultat,
            'annee'    => $annee,
            'moisNom'  => $service->nomMois($mois),
        ])->setPaper('a4', 'portrait');

        $nom = "recap_td_{$eleve->nom}_{$eleve->prenom}_{$mois}.pdf";

        return $pdf->download($nom);
    }

    /* ---------------------------------------------------------------
     * PDF — UNE classe (par mois ou par année)
     * -------------------------------------------------------------- */
    public function classe(Request $request, TdRecapService $service)
    {
        $request->validate([
            'annee_id'  => 'required|exists:annees,id',
            'classe_id' => 'required|exists:classes,id',
            'mois'      => $request->mode === 'mois'
                            ? 'required|integer|min:1|max:12'
                            : 'nullable',
        ]);

        $annee      = Annee::findOrFail($request->annee_id);
        $classe     = Classe::findOrFail($request->classe_id);
        $anneeDebut = (int) Carbon::parse($annee->debut)->year;
        $mode       = $request->mode ?? 'mois';

        $result = $service->recapPourClasse(
            $classe, $request->annee_id, $anneeDebut, $mode, (int) $request->mois
        );

        $pdf = Pdf::loadView('pdf.td-recap-classe', [
            'classe'  => $classe,
            'lignes'  => $result['lignes'],
            'totaux'  => $result['totaux'],
            'annee'   => $annee,
            'mode'    => $mode,
            'mois'    => $request->mois,
            'moisNom' => $service->nomMois((int) $request->mois),
        ])->setPaper('a4', 'portrait');

        $nom = "recap_td_classe_{$classe->niveau}.pdf";

        return $pdf->download($nom);
    }

    /* ---------------------------------------------------------------
     * PDF — TOUTES les classes (filtrées par cycle si sélectionné)
     * -------------------------------------------------------------- */
    public function toutesClasses(Request $request, TdRecapService $service)
    {
        $request->validate([
            'annee_id' => 'required|exists:annees,id',
            'mois'     => $request->mode === 'mois'
                           ? 'required|integer|min:1|max:12'
                           : 'nullable',
        ]);

        $annee      = Annee::findOrFail($request->annee_id);
        $anneeDebut = (int) Carbon::parse($annee->debut)->year;
        $mode       = $request->mode ?? 'mois';

        $query = Classe::orderByNiveau();
        if ($request->cycle_id) {
            $query->where('cycle_id', $request->cycle_id);
        }
        $toutesClasses = $query->get();

        $recapToutesClasses = [];
        foreach ($toutesClasses as $classe) {
            $result = $service->recapPourClasse(
                $classe, $request->annee_id, $anneeDebut, $mode, (int) $request->mois
            );
            if (!empty($result['lignes'])) {
                $recapToutesClasses[] = $result;
            }
        }

        $cycle = $request->cycle_id ? Cycle::find($request->cycle_id) : null;

        $pdf = Pdf::loadView('pdf.td-recap-toutes-classes', [
            'recapToutesClasses' => $recapToutesClasses,
            'annee'              => $annee,
            'cycle'              => $cycle,
            'mode'               => $mode,
            'mois'               => $request->mois,
            'moisNom'            => $service->nomMois((int) $request->mois),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('recap-toutes-classes.pdf');
    }
}