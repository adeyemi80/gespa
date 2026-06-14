<?php

namespace App\Http\Controllers;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Eleve;
use App\Services\TdRecapService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class TdRecapPdfController extends Controller
{
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

        $moisNoms = [
            1  => 'Janvier',  2  => 'Février',   3  => 'Mars',
            4  => 'Avril',    5  => 'Mai',        10 => 'Octobre',
            11 => 'Novembre', 12 => 'Décembre',
        ];

        $pdf = Pdf::loadView('pdf.td-recap', [
            'resultat' => $resultat,
            'annee'    => $annee,
            'moisNom'  => $moisNoms[$mois] ?? $mois,
        ])->setPaper('a4', 'portrait');

        $nom = "recap_td_{$eleve->nom}_{$eleve->prenom}_{$mois}.pdf";

        return $pdf->download($nom);
    }
}