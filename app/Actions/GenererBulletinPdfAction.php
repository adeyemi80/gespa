<?php

namespace App\Actions;

use App\Models\Annee;
use App\Models\Classe;
use App\Services\MoyenneService;
use Barryvdh\DomPDF\Facade\Pdf;

class GenererBulletinPdfAction
{
    public function __construct(
        protected MoyenneService $moyenneService
    ) {}

    public function execute(int $anneeId, int $classeId, int $trimestreId)
    {
        $annee = Annee::findOrFail($anneeId);
        $classe = Classe::with('matieres')->findOrFail($classeId);

        // recalcul sécurité avant PDF
        $this->moyenneService->calculerClassementAnnuel($anneeId, $classeId);

        $bulletins = app(CalculerBulletinAction::class)
            ->getBulletinsClasse($classeId, $anneeId, $trimestreId);

        $pdf = Pdf::loadView('bulletins.pdf_classe', [
            'bulletins' => $bulletins,
            'annee' => $annee,
            'classe' => $classe,
            'trimestre_id' => $trimestreId,
        ]);

        return $pdf->stream("bulletins_classe_{$classe->nom}.pdf");
    }
}