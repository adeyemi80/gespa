<?php

namespace App\Livewire\Bulletins;

use Livewire\Component;
use App\Models\Moyenne;

class BulletinTable extends Component
{
    public $anneeId;
    public $classeId;
    public $trimestreId;

    protected $listeners = [
        'filtersUpdated' => 'updateFilters'
    ];

    public function updateFilters($data)
    {
        $this->anneeId = $data['anneeId'] ?? null;
        $this->classeId = $data['classeId'] ?? null;
        $this->trimestreId = $data['trimestreId'] ?? null;
    }

    public function getBulletinsProperty()
    {
        if (!$this->anneeId || !$this->classeId || !$this->trimestreId) {
            return collect();
        }

        return Moyenne::with(['inscription.eleve', 'inscription.classe'])
            ->where('annee_id', $this->anneeId)
            ->where('classe_id', $this->classeId)
            ->where('trimestre_id', $this->trimestreId)
            ->orderByDesc('moyenne_trimestrielle')
            ->get();
    }

    public function render()
    {
        return view('livewire.bulletins.bulletin-table', [
            'bulletins' => $this->bulletins
        ]);
    }
}