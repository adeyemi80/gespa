<?php
// app/Livewire/BudgetSynthese.php

namespace App\Livewire\Recettes;

use App\Models\Annee;
use App\Services\BudgetSyntheseService;
use Livewire\Component;

class BudgetSynthese extends Component
{
    public ?int $anneeId = null;

    public function mount(): void
    {
        $this->anneeId = Annee::orderByDesc('id')->value('id');
    }

    public function render(BudgetSyntheseService $service)
    {
        return view('livewire.recettes.budget-synthese', $service->getDonnees($this->anneeId));
    }
}