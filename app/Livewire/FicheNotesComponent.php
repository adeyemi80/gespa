<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Annee;
use App\Models\Trimestre;
use App\Models\Classe;
use App\Models\Inscription;
use Barryvdh\DomPDF\Facade\Pdf;
/**GENERATION DES FICHES DE NOTES D'UNE CLASSE */
class FicheNotesComponent extends Component
{
    public $annee_id     = '';
    public $trimestre_id = '';
    public $classe_id    = '';

    public $annees     = [];
    public $trimestres = [];
    public $classes    = [];

    public $fiches    = [];
    public $annee     = null;
    public $trimestre = null;
    public $classe    = null;

    public function mount()
    {
        $this->annees     = Annee::orderBy('id')->get();
        $this->trimestres = Trimestre::all();
        $this->classes    = Classe::where('cycle_id', 3)
                                  ->orderByNiveau()
                                  ->get();
    }

    public function generer()
    {
        $this->validate([
            'annee_id'     => 'required|exists:annees,id',
            'trimestre_id' => 'required|exists:trimestres,id',
            'classe_id'    => 'required|exists:classes,id',
        ]);

        $this->annee     = Annee::findOrFail($this->annee_id);
        $this->trimestre = Trimestre::findOrFail($this->trimestre_id);
        $this->classe    = Classe::findOrFail($this->classe_id);

        $inscriptions = Inscription::with('eleve')
            ->where('inscriptions.annee_id', $this->annee_id)
            ->where('inscriptions.classe_id', $this->classe_id)
            ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
            ->orderBy('eleves.nom', 'asc')
            ->orderBy('eleves.prenom', 'asc')
            ->select('inscriptions.*')
            ->get();

        $eleves   = $inscriptions->pluck('eleve');
        $matieres = $this->classe->matieres()->get();

        $this->fiches = [];

        foreach ($matieres as $matiere) {
            $resultats = [];
            foreach ($eleves as $eleve) {
                $resultats[] = [
                    'eleve'   => $eleve,
                    'matiere' => $matiere,
                ];
            }
            $this->fiches[] = [
                'matiere'   => $matiere,
                'resultats' => $resultats,
            ];
        }
    }

    public function exportPdf()
    {
        $this->validate([
            'annee_id'     => 'required|exists:annees,id',
            'trimestre_id' => 'required|exists:trimestres,id',
            'classe_id'    => 'required|exists:classes,id',
        ]);

        $annee     = Annee::findOrFail($this->annee_id);
        $trimestre = Trimestre::findOrFail($this->trimestre_id);
        $classe    = Classe::findOrFail($this->classe_id);

        $inscriptions = Inscription::with('eleve')
            ->where('inscriptions.annee_id', $this->annee_id)
            ->where('inscriptions.classe_id', $this->classe_id)
            ->join('eleves', 'inscriptions.eleve_id', '=', 'eleves.id')
            ->orderBy('eleves.nom', 'asc')
            ->orderBy('eleves.prenom', 'asc')
            ->select('inscriptions.*')
            ->get();

        $eleves   = $inscriptions->pluck('eleve');
        $matieres = $classe->matieres()->get();

        $fiches = [];
        foreach ($matieres as $matiere) {
            $resultats = [];
            foreach ($eleves as $eleve) {
                $resultats[] = [
                    'eleve'   => $eleve,
                    'matiere' => $matiere,
                ];
            }
            $fiches[] = [
                'matiere'   => $matiere,
                'resultats' => $resultats,
            ];
        }

        $pdf = Pdf::loadView('fiches.pdf_fiche', compact('fiches', 'classe', 'trimestre', 'annee'))
                  ->setPaper('A4', 'portrait');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            "Fiches_{$classe->nom}_{$trimestre->nom}.pdf"
        );
    }

    public function render()
    {
        return view('livewire.fiche-notes-component');
    }
}