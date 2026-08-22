<?php

namespace App\Livewire\Paiements;

use App\Models\Paiement;
use App\Models\PaiementAchat;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class PaiementsDuJour extends Component
{
    public string $date;

    public function mount(): void
    {
        $this->date = now()->toDateString();
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUALISATION
    |--------------------------------------------------------------------------
    */

    #[On('paiement-enregistre')]
    #[On('achat-enregistre')]
    public function actualiser(): void
    {
        //
    }

    /*
    |--------------------------------------------------------------------------
    | AFFICHAGE
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        $date = Carbon::parse($this->date);

        /*
        |--------------------------------------------------------------------------
        | PAIEMENTS CLASSIQUES
        |--------------------------------------------------------------------------
        */

        $paiementsClassiques = Paiement::query()
            ->with([
                'inscription.eleve',
                'inscription.classe',
                'inscription.annee',
                'frais',
            ])
            ->whereDate(
                'date_paiement',
                $date->toDateString()
            )
            ->get()
            ->map(function ($paiement) {

                return (object) [

                    'id' =>
                        'paiement-' . $paiement->id,

                    'reference' =>
                        $paiement->reference
                        ?? $paiement->numero_recu
                        ?? '-',

                    'eleve' =>
                        trim(
                            ($paiement->inscription?->eleve?->nom ?? '')
                            . ' '
                            . ($paiement->inscription?->eleve?->prenom ?? '')
                        ),

                    'classe' =>
                        $paiement->inscription?->classe?->nom
                        ?? '-',

                    'annee' =>
                        $paiement->inscription?->annee?->nom
                        ?? '-',

                    'description' =>
                        $paiement->frais?->nom
                        ?? 'Paiement',

                    'montant_verse' =>
                        (float) $paiement->montant_verse,

                    'mode_paiement' =>
                        $paiement->mode_paiement
                        ?? '-',

                    'date_paiement' =>
                        $paiement->date_paiement,

                    'created_at' =>
                        $paiement->created_at,

                    'type' =>
                        'PAIEMENT',
                ];
            });


        /*
        |--------------------------------------------------------------------------
        | ACHATS
        |--------------------------------------------------------------------------
        */

        $achats = PaiementAchat::query()
            ->with([
                'inscription.eleve',
                'inscription.classe',
                'inscription.annee',
                'frais',
                'categorieRecette',
            ])
            ->whereDate(
                'date_paiement',
                $date->toDateString()
            )
            ->get()
            ->map(function ($achat) {

                $description =
                    $achat->categorieRecette?->nom;

                if ($achat->frais?->nom) {

                    $description =
                        $description
                        ? $description . ' - ' . $achat->frais->nom
                        : $achat->frais->nom;
                }

                return (object) [

                    'id' =>
                        'achat-' . $achat->id,

                    'reference' =>
                        $achat->numero_recu
                        ?? '-',

                    'eleve' =>
                        trim(
                            (
                                $achat->inscription?->eleve?->nom
                                ?? $achat->nom
                                ?? ''
                            )
                            . ' '
                            .
                            (
                                $achat->inscription?->eleve?->prenom
                                ?? $achat->prenom
                                ?? ''
                            )
                        ),

                    'classe' =>
                        $achat->inscription?->classe?->nom
                        ?? '-',

                    'annee' =>
                        $achat->inscription?->annee?->nom
                        ?? '-',

                    'description' =>
                        $description ?? 'Achat',

                    'montant_verse' =>
                        (float) $achat->montant,

                    'mode_paiement' =>
                        $achat->mode_paiement
                        ?? '-',

                    'date_paiement' =>
                        $achat->date_paiement,

                    'created_at' =>
                        $achat->created_at,

                    'type' =>
                        'ACHAT',
                ];
            });


        /*
        |--------------------------------------------------------------------------
        | FUSION
        |--------------------------------------------------------------------------
        */

        $paiements = $paiementsClassiques
            ->concat($achats)
            ->sortBy(function ($paiement) {
                return $paiement->date_paiement
                    ?? $paiement->created_at;
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */

        $totalDuJour =
            $paiements->sum('montant_verse');


        return view(
            'livewire.paiements.paiements-du-jour',
            [
                'paiements' =>
                    $paiements,

                'date' =>
                    $date,

                'totalDuJour' =>
                    $totalDuJour,
            ]
        );
    }
}