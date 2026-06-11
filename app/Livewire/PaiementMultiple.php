<?php

namespace App\Livewire;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Inscription;
use App\Models\Paiement;
use Livewire\Component;
use Illuminate\Support\Str;

class PaiementMultiple extends Component
{
    // Filtres en cascade
    public $anneeId   = null;
    public $cycleId   = null;
    public $classeId  = null;

    // Listes dynamiques
    public $classes      = [];
    public $inscriptions = [];

    // Élève sélectionné
    public $inscriptionId    = null;
    public $fraisDisponibles = [];
    public $aucunFraisAffecte = false; 

    // Paiement
    public $modePaiement = '';
    public $datePaiement = '';
    public $montants     = []; // [frais_id => montant]

    // UI
    public $totalFrais     = 0;
    public $totalPaye      = 0;
    public $totalReste     = 0;
    public $successMessage = '';
    public $numeroLotGenere = null;

    public function mount()
    {
        $this->datePaiement = now()->format('Y-m-d');

        $anneeEnCours = $this->getAnneeEnCours();
        if ($anneeEnCours) {
            $this->anneeId = $anneeEnCours->id;
        }
    }

    private function getAnneeEnCours()
    {
        $annees = Annee::all();
        return Annee::where('en_cours', true)->first()
            ?? $annees->firstWhere('en_cours', 't')
            ?? $annees->firstWhere('id', 2);
    }

    // CYCLE → CLASSES
    public function updatedCycleId($value)
    {
        $this->classeId         = null;
        $this->inscriptionId    = null;
        $this->fraisDisponibles = [];
        $this->montants         = [];
        $this->resetTotaux();

        $this->classes = $value
            ? Classe::where('cycle_id', $value)->orderByNiveau()->get()->toArray()
            : [];

        $this->inscriptions = [];
    }

    // CLASSE → ÉLÈVES
    public function updatedClasseId($value)
    {
        $this->inscriptionId    = null;
        $this->fraisDisponibles = [];
        $this->montants         = [];
        $this->resetTotaux();

        $this->inscriptions = $value && $this->anneeId
            ? Inscription::with('eleve')
                ->where('inscriptions.classe_id', $value)
                ->where('inscriptions.annee_id', $this->anneeId)
                ->alphabetique()
                ->get()
                ->map(fn($i) => [
                    'id'  => $i->id,
                    'nom' => $i->eleve->nom . ' ' . $i->eleve->prenom,
                ])
                ->toArray()
            : [];
    }

    // ÉLÈVE → FRAIS
    public function updatedInscriptionId($value)
{
    $this->fraisDisponibles  = [];
    $this->montants          = [];
    $this->aucunFraisAffecte = false; // ← reset
    $this->resetTotaux();

    if (!$value) return;

    $inscription = Inscription::with(['inscriptionFrais.frais'])
        ->findOrFail($value);

    $tousLesFrais = $inscription->inscriptionFrais;

    // ← Aucun frais affecté à cette inscription
    if ($tousLesFrais->isEmpty()) {
        $this->aucunFraisAffecte = true;
        return;
    }

    $this->fraisDisponibles = $tousLesFrais
        ->filter(fn($if) => ($if->reste ?? 0) > 0)
        ->map(fn($if) => [
            'frais_id'      => $if->frais_id,
            'nom'           => $if->frais->nom ?? $if->frais->description ?? 'Frais',
            'montant_frais' => $if->montant_frais,
            'montant_paye'  => $if->montant_frais - $if->reste,
            'reste'         => $if->reste,
            'selectionne'   => false,
        ])
        ->values()
        ->toArray();
}
    // Cocher/décocher un frais
    public function toggleFrais($index)
    {
        $this->fraisDisponibles[$index]['selectionne'] =
            !$this->fraisDisponibles[$index]['selectionne'];

        $frais   = $this->fraisDisponibles[$index];
        $fraisId = $frais['frais_id'];

        if ($this->fraisDisponibles[$index]['selectionne']) {
            $this->montants[$fraisId] = $frais['reste'];
        } else {
            unset($this->montants[$fraisId]);
        }

        $this->calculerTotaux();
    }

    // Recalcul des totaux à chaque changement de montant
    public function updatedMontants()
    {
        $this->calculerTotaux();
    }

    private function calculerTotaux()
    {
        $selectionnes = collect($this->fraisDisponibles)
            ->filter(fn($f) => $f['selectionne']);

        $this->totalFrais = $selectionnes->sum('montant_frais');
        $this->totalPaye  = $selectionnes->sum('montant_paye');
        $this->totalReste = $selectionnes->sum('reste');
    }

    private function resetTotaux()
    {
        $this->totalFrais = 0;
        $this->totalPaye  = 0;
        $this->totalReste = 0;
    }

    // Total en cours de saisie
    public function getTotalASaisirProperty()
    {
        return collect($this->montants)->sum();
    }

    public function enregistrer()
    {
        $this->validate([
            'inscriptionId' => 'required|exists:inscriptions,id',
            'modePaiement'  => 'required|string',
            'datePaiement'  => 'required|date',
        ]);

        $fraisSelectionnes = collect($this->fraisDisponibles)
            ->filter(fn($f) => $f['selectionne']);

        if ($fraisSelectionnes->isEmpty()) {
            $this->addError('frais', 'Veuillez sélectionner au moins un frais.');
            return;
        }

        // Validation des montants
        foreach ($fraisSelectionnes as $f) {
            $montant = $this->montants[$f['frais_id']] ?? 0;
            if (!$montant || $montant <= 0) {
                $this->addError('frais', "Le montant pour « {$f['nom']} » est invalide.");
                return;
            }
            if ($montant > $f['reste']) {
                $this->addError('frais', "Le montant pour « {$f['nom']} » dépasse le reste dû ({$f['reste']} FCFA).");
                return;
            }
        }

        $numeroLot   = 'LOT-' . strtoupper(Str::random(8)) . '-' . now()->format('Ymd');
        $inscription = Inscription::with('frais')->findOrFail($this->inscriptionId);

        foreach ($fraisSelectionnes as $f) {
            $montant = $this->montants[$f['frais_id']];

            Paiement::create([
                'inscription_id' => $this->inscriptionId,
                'frais_id'       => $f['frais_id'],
                'montant_verse'  => $montant,
                'mode_paiement'  => $this->modePaiement,
                'date_paiement'  => $this->datePaiement,
                'numero_recu'    => $numeroLot,
            ]);

            $inscription->frais()->updateExistingPivot($f['frais_id'], [
                'reste' => max(0, $f['reste'] - $montant),
            ]);
        }

        $this->numeroLotGenere = $numeroLot;
        $this->successMessage  = 'Paiement enregistré avec succès !';
        $this->dispatch('masquerSucces');

        // ✅ Dispatcher le ticket AVANT le reset
        $this->dispatch('ouvrirTicket', numeroLot: $numeroLot);

        // Reset du formulaire (sauf filtres)
        $this->inscriptionId    = null;
        $this->fraisDisponibles = [];
        $this->montants         = [];
        $this->resetTotaux();
    }

    public function render()
    {
        return view('livewire.paiement-multiple', [
            'annees' => Annee::all(),
            'cycles' => Cycle::all(),
        ]);
    }
}