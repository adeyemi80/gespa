<?php

namespace App\Livewire\Bulletins;

use Livewire\Component;
use App\Models\Moyenne;
use Barryvdh\DomPDF\Facade\Pdf;

class BulletinPdfButton extends Component
{
    public $anneeId;
    public $classeId;
    public $trimestreId;

    protected $listeners = ['filtersUpdated' => 'updateFilters'];

    public function updateFilters($data)
    {
        $this->anneeId = $data['anneeId'];
        $this->classeId = $data['classeId'];
        $this->trimestreId = $data['trimestreId'];
    }

    public function generatePdf()
    {
        if (!$this->anneeId || !$this->classeId || !$this->trimestreId) {
            session()->flash('error', 'Veuillez sélectionner tous les filtres.');
            return;
        }

        $bulletins = Moyenne::with([
            'inscription.eleve',
            'inscription.classe.matieres',
            'annee',
            'trimestre'
        ])
            ->where('annee_id', $this->anneeId)
            ->where('classe_id', $this->classeId)
            ->where('trimestre_id', $this->trimestreId)
            ->get();

        if ($bulletins->isEmpty()) {
            session()->flash('error', 'Aucun bulletin trouvé.');
            return;
        }

        $afficherTiret = function($value) {
            return $value !== null && $value !== '' ? number_format($value, 2) : '-';
        };

        $pdf = Pdf::loadView('bulletins.pdf_classe', [
            'bulletins' => $bulletins,
            'afficherTiret' => $afficherTiret
        ]);

        $pdf->setPaper('A4', 'portrait');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'bulletins_classe_' . $this->classeId . '_T' . $this->trimestreId . '.pdf'
        );
    }

    public function render()
    {
        return view('livewire.bulletins.bulletin-pdf-button');
    }
}