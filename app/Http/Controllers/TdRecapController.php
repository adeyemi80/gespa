<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annee;
use App\Models\Classe;
use App\Models\Inscription;
use App\Models\Eleve;
use App\Services\TdRecapService;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TdRecapController extends Controller
{
    protected TdRecapService $service;

    public function __construct(TdRecapService $service)
    {
        $this->service = $service;
    }

    /* ── PDF élève unique ── */
    public function pdf(Request $request)
    {
        $request->validate([
            'annee_id'  => 'required|exists:annees,id',
            'classe_id' => 'required|exists:classes,id',
            'eleve_id'  => 'required|exists:eleves,id',
            'mois'      => 'required|integer|min:1|max:12',
        ]);

        $eleve      = Eleve::findOrFail($request->eleve_id);
        $classe     = Classe::findOrFail($request->classe_id);
        $annee      = Annee::findOrFail($request->annee_id);
        $anneeDebut = (int) Carbon::parse($annee->debut)->year;

        $resultat = $this->service->recapMensuel(
            $eleve, $classe,
            $request->annee_id,
            (int) $request->mois,
            $anneeDebut
        );

        $pdf = Pdf::loadView('pdf.td-recap-eleve', compact('resultat', 'annee'));

        return $pdf->stream("recap-td-{$eleve->nom}-mois{$request->mois}.pdf");
    }

    /* ── PDF récap UNE classe ── */
    public function pdfClasse(Request $request)
    {
        $request->validate([
            'annee_id'  => 'required|exists:annees,id',
            'classe_id' => 'required|exists:classes,id',
            'mode'      => 'required|in:mois,annee',
            'mois'      => 'required_if:mode,mois|nullable|integer|min:1|max:12',
        ]);

        $classe     = Classe::findOrFail($request->classe_id);
        $annee      = Annee::findOrFail($request->annee_id);
        $anneeDebut = (int) Carbon::parse($annee->debut)->year;

        $bloc = $this->buildLignesClasse($classe, $annee, $anneeDebut, $request->mode, $request->mois);

        $pdf = Pdf::loadView('pdf.td-recap-classe', [
            'lignes'  => $bloc['lignes'],
            'totaux'  => $bloc['totaux'],
            'classe'  => $classe,
            'annee'   => $annee,
            'request' => $request,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("recap-td-{$classe->niveau}-{$request->mode}.pdf");
    }

    /* ── PDF récap TOUTES les classes ── */
    public function pdfToutesClasses(Request $request)
    {
        $request->validate([
            'annee_id' => 'required|exists:annees,id',
            'mode'     => 'required|in:mois,annee',
            'mois'     => 'required_if:mode,mois|nullable|integer|min:1|max:12',
            'cycle_id' => 'nullable|exists:cycles,id',
        ]);

        $annee      = Annee::findOrFail($request->annee_id);
        $anneeDebut = (int) Carbon::parse($annee->debut)->year;

        $query = Classe::orderByNiveau();
        if ($request->cycle_id) {
            $query->where('cycle_id', $request->cycle_id);
        }
        $classes = $query->get();

        $blocs = [];
        foreach ($classes as $classe) {
            $bloc = $this->buildLignesClasse($classe, $annee, $anneeDebut, $request->mode, $request->mois);
            if (!empty($bloc['lignes'])) {
                $blocs[] = array_merge(['classe' => $classe], $bloc);
            }
        }

        $pdf = Pdf::loadView('pdf.td-recap-toutes-classes', [
            'blocs'   => $blocs,
            'annee'   => $annee,
            'request' => $request,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("recap-td-toutes-classes-{$request->mode}.pdf");
    }

    /* ── Méthode commune : construire lignes + totaux pour une classe ── */
    private function buildLignesClasse(
        Classe $classe,
        Annee $annee,
        int $anneeDebut,
        string $mode,
        ?int $mois
    ): array {

        $inscriptions = Inscription::with('eleve')
            ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
            ->where('inscriptions.annee_id', $annee->id)
            ->where('inscriptions.classe_id', $classe->id)
            ->orderBy('eleves.nom')->orderBy('eleves.prenom')
            ->select('inscriptions.*', 'eleves.nom', 'eleves.prenom')
            ->get();

        $lignes  = [];
        $totNbTd = $totDu = $totPaye = $totReste = 0;

        foreach ($inscriptions as $insc) {
            $eleve = Eleve::findOrFail($insc->eleve_id);

            if ($mode === 'mois') {
                $recap = $this->service->recapMensuel(
                    $eleve, $classe, $annee->id, (int) $mois, $anneeDebut
                );
                $nbTd  = (int)   ($recap['nb_td']               ?? 0);
                $du    = (float) ($recap['montant_du_cumule']    ?? 0);
                $paye  = (float) ($recap['montant_paye_cumule']  ?? 0);
                $reste = (float) ($recap['reste_a_payer_cumule'] ?? 0);
            } else {
                $recap = $this->service->recapAnnuel(
                    $eleve, $classe, $annee->id, $anneeDebut
                );
                $nbTd  = (int)   ($recap['nb_td']        ?? 0);
                $du    = (float) ($recap['montant_du']    ?? 0);
                $paye  = (float) ($recap['montant_paye']  ?? 0);
                $reste = (float) ($recap['reste_a_payer'] ?? 0);
            }

            $lignes[] = [
                'nom'    => $insc->nom,
                'prenom' => $insc->prenom,
                'nb_td'  => $nbTd,
                'du'     => $du,
                'paye'   => $paye,
                'reste'  => $reste,
            ];

            $totNbTd  += $nbTd;
            $totDu    += $du;
            $totPaye  += $paye;
            $totReste += $reste;
        }

        return [
            'lignes' => $lignes,
            'totaux' => [
                'nb_td' => $totNbTd,
                'du'    => $totDu,
                'paye'  => $totPaye,
                'reste' => $totReste,
            ],
        ];
    }
}