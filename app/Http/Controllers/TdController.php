<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Inscription;
use App\Models\Frais;
use App\Models\TdSession;
use App\Models\TdParticipation;
use App\Models\TdPaiement;
use Barryvdh\DomPDF\Facade\Pdf;

class TdController extends Controller
{
    /* =====================================================
        1. ACCUEIL GESTION TD
    ===================================================== */
   public function index()
{
    $annee = Annee::where('en_cours', true)->first();

    if (!$annee) {
        return redirect()->back()->with('error', 'Aucune année en cours définie.');
    }

    $classes = $annee->classes;

    return view('td.index', [
        'annee' => $annee,
        'date' => now()->format('d/m/Y'),
        'classes' => $classes
    ]);
}

    /* =====================================================
        2. CHARGER LES INSCRIPTIONS D’UNE CLASSE
    ===================================================== */
    public function chargerClasse(Request $request)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id'
        ]); 

        $annee = Annee::where('en_cours', true)->firstOrFail();

        $inscriptions = Inscription::with('eleve')
            ->where('classe_id', $request->classe_id)
            ->where('annee_id', $annee->id)
            ->get();

        return view('td.participation', compact('inscriptions'));
    }

    /* =====================================================
        3. ENREGISTRER LES PARTICIPANTS DU JOUR
    ===================================================== */
   public function enregistrerParticipants(Request $request)
{
    $request->validate([
        'classe_id'    => 'required|exists:classes,id',
        'participants' => 'required|array'
    ]);

    $annee = Annee::where('en_cours', true)->firstOrFail();

    DB::transaction(function () use ($request, $annee) {

        // Session du jour
        $session = TdSession::firstOrCreate([
            'classe_id' => $request->classe_id,
            'annee_id'  => $annee->id,
            'date_td'   => now()->toDateString(),
        ]);

        // 🔥 ON SUPPRIME UNIQUEMENT LES PARTICIPATIONS DU JOUR
        TdParticipation::where('td_session_id', $session->id)->delete();

        // ✅ ON RECRÉE DES PARTICIPATIONS PROPRES POUR AUJOURD’HUI
        foreach ($request->participants as $inscription_id) {
            TdParticipation::create([
                'td_session_id' => $session->id,
                'inscription_id' => $inscription_id
            ]);
        }
    });

    return redirect()
        ->route('td.paiements', $request->classe_id)
        ->with('success', 'Participants du jour enregistrés.');
}

    /* =====================================================
        4. AFFICHER PARTICIPANTS + FRAIS TD
    ===================================================== */
   public function paiements($classe_id)
{
    // Année en cours
    $annee = Annee::where('en_cours', true)->firstOrFail();

    // Session TD du jour
    $session = TdSession::where([
        'classe_id' => $classe_id,
        'annee_id'  => $annee->id,
        'date_td'   => now()->toDateString()
    ])->firstOrFail();

    // 🔒 Construction fiable des frais TD
    $fraisTd = collect();

    foreach (['seance', 'mois', 'annee'] as $type) {

        $frais = Frais::whereHas('classes', function ($q) use ($classe_id) {
                $q->where('classes.id', $classe_id);
            })
            ->whereHas('annees', function ($q) use ($annee) {
                $q->where('annees.id', $annee->id);
            })
            ->where('nom', 'like', "%$type%")
            ->first();

        if ($frais) {
            $fraisTd->put($type, $frais);
        }
    }

    // Participants du jour
    $participants = TdParticipation::with('inscription.eleve')
        ->where('td_session_id', $session->id)
        ->get();

    return view('td.paiements', [
        'participants' => $participants,
        'session'      => $session,
        'fraisTd'      => $fraisTd
    ]);
}

    /* =====================================================
        5. ENREGISTRER LES PAIEMENTS TD
        - séance : chaque jour
        - mois : une seule fois par mois
        - annee : une seule fois par année
    ===================================================== */
  public function enregistrerPaiements(Request $request)
{
    $request->validate([
        'paiements' => 'required|array',
        'types' => 'required|array'
    ]);

    $annee = Annee::where('en_cours', true)->firstOrFail();

    DB::transaction(function () use ($request, $annee) {

        foreach ($request->paiements as $participationId => $etat) {

            if ($etat != 1) continue;

            $participation = TdParticipation::with('td_session')->find($participationId);
            if (!$participation) continue;

            $type = $request->types[$participationId] ?? null;
            if (!in_array($type, ['seance', 'mois', 'annee'])) continue;

            $session = $participation->td_session;

            $frais = Frais::whereHas('classes', fn($q) =>
                    $q->where('classes.id', $session->classe_id)
                )
                ->whereHas('annees', fn($q) =>
                    $q->where('annees.id', $session->annee_id)
                )
                ->where('nom', 'like', "%$type%")
                ->first();

            if (!$frais) continue;

            // 🔒 règles anti-doublons
            $query = TdPaiement::where('type_frais', $type)
                ->whereHas('participation', fn($q) =>
                    $q->where('inscription_id', $participation->inscription_id)
                );

            if ($type === 'mois') {
                $query->whereMonth('created_at', now()->month);
            }

            if ($type === 'annee') {
                $query->whereHas('participation.td_session', fn($q) =>
                    $q->where('annee_id', $session->annee_id)
                );
            }

            if ($query->exists()) continue;

            TdPaiement::create([
                'td_participation_id' => $participation->id,
                'montant' => $frais->montant,
                'type_frais' => $type,
                'paye' => true
            ]);
        }
    });

    return redirect()->route('td.index')
        ->with('success', 'Paiements TD enregistrés avec succès.');
}


    /* =====================================================
        6. SITUATION CUMULÉE PAR ÉLÈVE
    ===================================================== */
    public function situationEleve($inscription_id)
    {
        $seance = TdPaiement::where('type_frais', 'seance')
            ->whereHas('participation', fn($q) =>
                $q->where('inscription_id', $inscription_id)
            )->sum('montant');

        $mois = TdPaiement::where('type_frais', 'mois')
            ->whereHas('participation', fn($q) =>
                $q->where('inscription_id', $inscription_id)
            )->sum('montant');

        return response()->json([
            'total_seance' => $seance,
            'total_mois' => $mois,
            'total_general' => $seance + $mois
        ]);
    }


public function exportPdf($classe_id)
{
    $annee = Annee::where('en_cours', true)->firstOrFail();

    $session = TdSession::where([
        'classe_id' => $classe_id,
        'annee_id' => $annee->id,
        'date_td' => now()->toDateString()
    ])->firstOrFail();

    $participations = TdParticipation::with([
        'inscription.eleve',
        'paiements'
    ])->where('td_session_id', $session->id)->get();

    $data = [];

    foreach ($participations as $p) {
        $data[] = [
            'nom' => $p->inscription->eleve->nom . ' ' . $p->inscription->eleve->prenom,
            'total_paye' => $p->paiements->where('paye', true)->sum('montant'),
            'total_non_paye' => $p->paiements->where('paye', false)->sum('montant'),
        ];
    }

    $pdf = Pdf::loadView('td.export_pdf', compact('data'))
        ->setPaper('A4', 'portrait');

    return $pdf->stream('paiements_td.pdf');
}


}
