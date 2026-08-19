<?php

namespace App\Livewire\Paiements;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Inscription;
use App\Models\Paiement;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Services\ThermalPrinterService;
use App\Services\RecuThermiqueService;
use Illuminate\Support\Facades\Log;

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

    $fraisSelectionnes = collect(
        $this->fraisDisponibles
    )->filter(
        fn($f) => $f['selectionne']
    );

    if ($fraisSelectionnes->isEmpty()) {

        $this->addError(
            'frais',
            'Veuillez sélectionner au moins un frais.'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Validation des montants
    |--------------------------------------------------------------------------
    */

    foreach ($fraisSelectionnes as $f) {

        $montant =
            $this->montants[$f['frais_id']] ?? 0;


        if (!$montant || $montant <= 0) {

            $this->addError(
                'frais',
                "Le montant pour « {$f['nom']} » est invalide."
            );

            return;
        }


        if ($montant > $f['reste']) {

            $this->addError(
                'frais',
                "Le montant pour « {$f['nom']} » dépasse le reste dû ({$f['reste']} FCFA)."
            );

            return;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Numéro du lot / reçu
    |--------------------------------------------------------------------------
    */

    $numeroLot =
        'LOT-' .
        strtoupper(Str::random(8)) .
        '-' .
        now()->format('Ymd');


    $inscription = Inscription::with(
        'frais'
    )->findOrFail(
        $this->inscriptionId
    );


    /*
    |--------------------------------------------------------------------------
    | ENREGISTREMENT
    |--------------------------------------------------------------------------
    */

    foreach ($fraisSelectionnes as $f) {

        $montant =
            $this->montants[$f['frais_id']];


        Paiement::create([

            'inscription_id' =>
                $this->inscriptionId,

            'frais_id' =>
                $f['frais_id'],

            'montant_verse' =>
                $montant,

            'mode_paiement' =>
                $this->modePaiement,

            'date_paiement' =>
                $this->datePaiement,

            'numero_recu' =>
                $numeroLot,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Mise à jour du reste
        |--------------------------------------------------------------------------
        */

        $inscription->frais()
            ->updateExistingPivot(
                $f['frais_id'],
                [
                    'reste' =>
                        max(
                            0,
                            $f['reste'] - $montant
                        ),
                ]
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SUCCÈS
    |--------------------------------------------------------------------------
    */

    $this->numeroLotGenere =
        $numeroLot;

    $this->successMessage =
        'Paiement enregistré avec succès !';


    /*
    |--------------------------------------------------------------------------
    | IMPRESSION AUTOMATIQUE
    |--------------------------------------------------------------------------
    */

    try {

        $this->imprimerRecu(
            $numeroLot
        );

        $this->dispatch(
            'impressionReussie',
            numeroLot: $numeroLot
        );

    } catch (\Throwable $e) {

        Log::error(
            'Erreur impression reçu',
            [
                'numeroLot' =>
                    $numeroLot,

                'message' =>
                    $e->getMessage(),
            ]
        );

        $this->dispatch(
            'impressionEchouee',
            numeroLot: $numeroLot,
            message: $e->getMessage()
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Événements existants
    |--------------------------------------------------------------------------
    */

    $this->dispatch(
        'masquerSucces'
    );

    $this->dispatch(
        'paiement-enregistre'
    );


    /*
    |--------------------------------------------------------------------------
    | Ouvrir le reçu dans GESPA
    |--------------------------------------------------------------------------
    */

    $this->dispatch(
        'ouvrirTicket',
        numeroLot: $numeroLot
    );


    /*
    |--------------------------------------------------------------------------
    | RESET
    |--------------------------------------------------------------------------
    */

    $this->inscriptionId =
        null;

    $this->fraisDisponibles =
        [];

    $this->montants =
        [];

    $this->resetTotaux();
}
private function imprimerRecu(string $numeroLot): void
{
    $paiements = Paiement::with([
        'inscription.eleve',
        'inscription.classe',
        'inscription.annee',
        'frais',
    ])
    ->where('numero_recu', $numeroLot)
    ->get();

    if ($paiements->isEmpty()) {
        throw new \RuntimeException(
            "Aucun paiement trouvé pour {$numeroLot}"
        );
    }

    $paiement = $paiements->first();

    $inscription = $paiement->inscription;

    if (!$inscription) {
        throw new \RuntimeException(
            'Inscription introuvable.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Détails du paiement
    |--------------------------------------------------------------------------
    */

    $details = $paiements->map(function ($paiement) {

        return [
            'nom' =>
                $paiement->frais->nom
                ?? $paiement->frais->description
                ?? 'Frais',

            'montant' =>
                $paiement->montant_verse ?? 0,
        ];

    })->toArray();


    /*
    |--------------------------------------------------------------------------
    | Total de CE reçu
    |--------------------------------------------------------------------------
    */

    $totalRecu = $paiements->sum(
        'montant_verse'
    );


    /*
    |--------------------------------------------------------------------------
    | Total payé par l'élève
    |--------------------------------------------------------------------------
    */

    $totalPaye = Paiement::where(
        'inscription_id',
        $inscription->id
    )->sum('montant_verse');


    /*
    |--------------------------------------------------------------------------
    | Total des frais
    |--------------------------------------------------------------------------
    */

    $totalFrais = $inscription->frais->sum(
        function ($frais) {
            return $frais->pivot->montant_frais ?? 0;
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Reste
    |--------------------------------------------------------------------------
    */

    $reste = max(
        0,
        $totalFrais - $totalPaye
    );


    /*
    |--------------------------------------------------------------------------
    | Statut
    |--------------------------------------------------------------------------
    */

    if ($reste <= 0) {

        $statut = 'SOLDE';

    } elseif ($totalPaye > 0) {

        $statut = 'PARTIEL';

    } else {

        $statut = 'NON PAYE';
    }


    /*
    |--------------------------------------------------------------------------
    | Génération du ticket
    |--------------------------------------------------------------------------
    */

    $serviceRecu = new RecuThermiqueService();

    $ticket = $serviceRecu->generate([

        'numero' => $numeroLot,

        'date' => $paiement->date_paiement
            ? \Carbon\Carbon::parse(
                $paiement->date_paiement
            )->format('d/m/Y')
            : now()->format('d/m/Y'),

        'eleve' =>
            $inscription->eleve
            ? $inscription->eleve->nom .
              ' ' .
              $inscription->eleve->prenom
            : '-',

        'matricule' =>
            $inscription->eleve->matricule ?? '-',

        'classe' =>
            $inscription->classe->nom ?? '-',

        'annee' =>
            $inscription->annee->nom ?? '-',

        'details' =>
            $details,

        'total_recu' =>
            $totalRecu,

        'total_paye' =>
            $totalPaye,

        'total_frais' =>
            $totalFrais,

        'reste' =>
            $reste,

        'statut' =>
            $statut,

        'mode_paiement' =>
            $paiement->mode_paiement ?? '-',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Envoi à la Xprinter
    |--------------------------------------------------------------------------
    */

    $printer = new ThermalPrinterService(
        'Xprinter_POS58'
    );

    $result = $printer->print($ticket);


    /*
    |--------------------------------------------------------------------------
    | Journalisation
    |--------------------------------------------------------------------------
    */

    Log::info(
        'Reçu imprimé automatiquement',
        [
            'numero_recu' => $numeroLot,
            'imprimante' => 'Xprinter_POS58',
            'resultat' => $result,
        ]
    );
}
    public function render()
    {
        return view('livewire.paiements.paiement-multiple', [
            'annees' => Annee::all(),
            'cycles' => Cycle::all(),
        ]);
    }
}