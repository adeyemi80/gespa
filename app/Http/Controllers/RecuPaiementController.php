<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Inscription;
use Barryvdh\DomPDF\Facade\Pdf;

class RecuPaiementController extends Controller
{
    public function ticket($numeroLot)
    {
        $paiements = Paiement::with([
            'inscription.eleve',
            'inscription.classe',
            'inscription.annee',
            'inscription.frais',
            'frais',
        ])->where('numero_recu', $numeroLot)->get();

        if ($paiements->isEmpty()) {
            abort(404, "Aucun paiement trouvé pour ce reçu.");
        }

        $paiement = $paiements->first();
        $inscription = $paiement->inscription;

        if (!$inscription) {
            abort(500, "Inscription non trouvée pour ce paiement.");
        }

        $details = $paiements->map(fn($p) => [
            'nom'     => $p->frais->nom ?? $p->frais->description ?? 'Frais inconnu',
            'montant' => $p->montant_verse ?? 0,
        ]);

        $total_ce_recu = $paiements->sum('montant_verse');

        $total_paye = Paiement::where('inscription_id', $inscription->id)
            ->sum('montant_verse');

        $total_frais = $inscription->frais->sum('pivot.montant_frais');
        $reste       = $inscription->frais->sum('pivot.reste');

        $statut = match(true) {
            $reste == 0    => 'Soldé',
            $total_paye > 0 => 'Partiellement payé',
            default        => 'Non payé',
        };

        // Rendu HTML pour impression directe (pas PDF)
        return view('paiements.ticket-multiple', compact(
            'paiement',
            'paiements',
            'inscription',
            'details',
            'total_ce_recu',
            'total_paye',
            'total_frais',
            'reste',
            'statut',
            'numeroLot',
        ));
    }
}