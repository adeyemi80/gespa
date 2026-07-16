<?php

namespace App\Livewire\Paiements;

use App\Models\Paiement;
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

    /**
     * Écoute l'événement émis par PaiementMultiple après un enregistrement réussi.
     * Le simple fait que cette méthode s'exécute déclenche un nouveau render()
     * avec des données fraîches — pas besoin de logique supplémentaire ici.
     */
    #[On('paiement-enregistre')]
    public function actualiser(): void
    {
        //
    }

    public function render()
    {
        $date = Carbon::parse($this->date);

        $paiements = Paiement::whereDate('created_at', $date->toDateString())
            ->orderBy('created_at')
            ->get();

        $totalDuJour = $paiements->sum('montant_verse');

        return view('livewire.paiements.paiements-du-jour', [
            'paiements' => $paiements,
            'date' => $date,
            'totalDuJour' => $totalDuJour,
        ]);
    }
}