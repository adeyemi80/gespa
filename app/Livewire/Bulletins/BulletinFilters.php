<?php

namespace App\Livewire\Bulletins;

use Livewire\Component;
use App\Models\Annee;
use App\Models\Classe;
use App\Models\Trimestre;

class BulletinFilters extends Component
{
    public $anneeId;
    public $classeId;
    public $trimestreId;

    public function updated($field)
    {
        $this->dispatch('filtersUpdated', [
            'anneeId' => $this->anneeId,
            'classeId' => $this->classeId,
            'trimestreId' => $this->trimestreId,
        ]);
    }

    public function render()
    {
        return view('livewire.bulletins.bulletin-filters', [
            'annees' => Annee::all(),
            'classes' => Classe::all(),
            'trimestres' => Trimestre::all(),
        ]);
    }
}