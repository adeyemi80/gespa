<?php

namespace App\Livewire\Bulletins;

use Livewire\Component;
use App\Models\Moyenne;
use Livewire\Attributes\Computed;

class BulletinCard extends Component
{
    public ?int $inscriptionId = null;
    public ?int $trimestreId = null;

    public function mount($inscriptionId = null, $trimestreId = null): void
    {
        $this->inscriptionId = $inscriptionId !== null ? (int) $inscriptionId : null;
        $this->trimestreId = $trimestreId !== null ? (int) $trimestreId : null;
    }

    #[Computed]
    public function bulletin()
    {
        if (!$this->inscriptionId || !$this->trimestreId) {
            return null;
        }

        return Moyenne::with([
                'inscription.eleve',
                'inscription.classe',
                'trimestre',
            ])
            ->where('inscription_id', $this->inscriptionId)
            ->where('trimestre_id', $this->trimestreId)
            ->first();
    }

    public function render()
    {
        return view('livewire.bulletins.bulletin-card');
    }
}