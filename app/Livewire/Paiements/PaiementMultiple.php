<?php

namespace App\Livewire\Paiements;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Inscription;
use App\Models\Paiement;
use App\Services\ArriereFraisService;
use App\Services\RecuThermiqueService;
use App\Services\ThermalPrinterService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

class PaiementMultiple extends Component
{
    private const CATEGORIES_ARRIERE = [1, 17];

    public $anneeId = null;
    public $anneePrecedenteId = null;
    public $cycleId = null;
    public $classeId = null;

    public $classes = [];
    public $inscriptions = [];

    public $inscriptionId = null;
    public $fraisDisponibles = [];
    public $aucunFraisAffecte = false;

    public $modePaiement = '';
    public $datePaiement = '';
    public $montants = [];

    public $totalFrais = 0;
    public $totalPaye = 0;
    public $totalReste = 0;

    public $successMessage = '';
    public $numeroLotGenere = null;

    public bool $paiementArriere = false;
    public $inscriptionArriereId = null;
    public $fraisArrieres = [];
    public $montantsArrieres = [];
    public $totalArriere = 0;

    public array $arrieres = [];
    public array $anciensRecus = [];

    public function mount(): void
    {
        $this->datePaiement = now()->format('Y-m-d');

        $anneeEnCours = $this->getAnneeEnCours();

        if ($anneeEnCours) {
            $this->anneeId = $anneeEnCours->id;

            $anneePrecedente = app(ArriereFraisService::class)
                ->anneePrecedente($anneeEnCours);

            $this->anneePrecedenteId = $anneePrecedente?->id;
        }

        $this->arrieres = [];
    }

    private function getAnneeEnCours()
    {
        $annees = Annee::all();

        return Annee::where('en_cours', true)->first()
            ?? $annees->firstWhere('en_cours', 't')
            ?? $annees->firstWhere('id', 2);
    }

    public function verifierArriere(int $eleveId): void
    {
        if (!$this->anneeId) {
            $this->arrieres[$eleveId] = 0;
            return;
        }

        $anneeActuelle = Annee::find($this->anneeId);

        if (!$anneeActuelle) {
            $this->arrieres[$eleveId] = 0;
            return;
        }

        $service = app(ArriereFraisService::class);
        $anneePrecedente = $service->anneePrecedente($anneeActuelle);

        $this->anneePrecedenteId = $anneePrecedente?->id;

        if (!$anneePrecedente) {
            $this->arrieres[$eleveId] = 0;
            return;
        }

        $this->arrieres[$eleveId] = (float) $service->montantArriere(
            $eleveId,
            $anneeActuelle,
            self::CATEGORIES_ARRIERE
        );
    }

    public function updatedAnneeId($anneeId): void
    {
        $this->cycleId = null;
        $this->classeId = null;
        $this->inscriptionId = null;

        $this->classes = [];
        $this->inscriptions = [];
        $this->anciensRecus = [];
        $this->fraisDisponibles = [];
        $this->montants = [];
        $this->aucunFraisAffecte = false;
        $this->arrieres = [];
        $this->anneePrecedenteId = null;

        $this->fermerPaiementArriere();
        $this->resetTotaux();

        if (!$anneeId) {
            return;
        }

        $annee = Annee::find($anneeId);

        if (!$annee) {
            return;
        }

        $anneePrecedente = app(ArriereFraisService::class)
            ->anneePrecedente($annee);

        $this->anneePrecedenteId = $anneePrecedente?->id;
    }

    public function updatedCycleId($value): void
    {
        $this->classeId = null;
        $this->inscriptionId = null;
        $this->fraisDisponibles = [];
        $this->montants = [];
        $this->aucunFraisAffecte = false;
        $this->arrieres = [];
        $this->anciensRecus = [];

        $this->fermerPaiementArriere();
        $this->resetTotaux();

        $this->classes = $value
            ? Classe::where('cycle_id', $value)->orderByNiveau()->get()->toArray()
            : [];

        $this->inscriptions = [];
    }

    public function updatedClasseId($value): void
    {
        $this->inscriptionId = null;
        $this->fraisDisponibles = [];
        $this->montants = [];
        $this->aucunFraisAffecte = false;
        $this->arrieres = [];
        $this->anciensRecus = [];

        $this->fermerPaiementArriere();
        $this->resetTotaux();

        if (!$value || !$this->anneeId) {
            $this->inscriptions = [];
            return;
        }

        $inscriptions = Inscription::with('eleve')
            ->where('inscriptions.classe_id', $value)
            ->where('inscriptions.annee_id', $this->anneeId)
            ->alphabetique()
            ->get();

        foreach ($inscriptions as $inscription) {
            if ($inscription->eleve_id) {
                $this->verifierArriere((int) $inscription->eleve_id);
            }
        }

        $this->inscriptions = $inscriptions->map(function ($inscription) {
            return [
                'id' => $inscription->id,
                'eleve_id' => $inscription->eleve_id,
                'nom' => trim(
                    ($inscription->eleve->nom ?? '') . ' ' .
                    ($inscription->eleve->prenom ?? '')
                ),
            ];
        })->toArray();
    }

    public function updatedInscriptionId($value): void
    {
        $this->resetErrorBag();
        $this->fraisDisponibles = [];
        $this->montants = [];
        $this->aucunFraisAffecte = false;
        $this->anciensRecus = [];

        $this->fermerPaiementArriere();
        $this->resetTotaux();

        if (!$value) {
            return;
        }

        $inscription = Inscription::with('eleve')
            ->where('id', $value)
            ->where('annee_id', $this->anneeId)
            ->where('classe_id', $this->classeId)
            ->whereHas('classe', function ($query) {
                $query->where('cycle_id', $this->cycleId);
            })
            ->first();

        if (!$inscription) {
            $this->inscriptionId = null;
            $this->addError(
                'inscriptionId',
                'L’inscription ne correspond pas aux filtres sélectionnés.'
            );
            return;
        }

        if ($inscription->eleve_id) {
            $this->verifierArriere((int) $inscription->eleve_id);
        }

        $this->chargerAnciensRecus();

        if ($this->arriereSelectionne > 0) {
            return;
        }

        $this->chargerFraisInscription((int) $value);
    }

    private function chargerFraisInscription(int $inscriptionId): void
    {
        $this->fraisDisponibles = [];
        $this->montants = [];
        $this->aucunFraisAffecte = false;
        $this->resetTotaux();

        $inscription = Inscription::with('inscriptionFrais.frais')->find($inscriptionId);

        if (!$inscription) {
            return;
        }

        if ($this->anneeId && (int) $inscription->annee_id !== (int) $this->anneeId) {
            return;
        }

        $tousLesFrais = $inscription->inscriptionFrais;

        if ($tousLesFrais->isEmpty()) {
            $this->aucunFraisAffecte = true;
            return;
        }

        $this->fraisDisponibles = $tousLesFrais
            ->filter(fn ($if) => (float) ($if->reste ?? 0) > 0)
            ->map(function ($if) {
                return [
                    'frais_id' => $if->frais_id,
                    'nom' => $if->frais->nom ?? $if->frais->description ?? 'Frais',
                    'montant_frais' => (float) $if->montant_frais,
                    'montant_paye' => (float) $if->montant_frais - (float) $if->reste,
                    'reste' => (float) $if->reste,
                    'selectionne' => false,
                ];
            })
            ->values()
            ->toArray();
    }

    public function getArriereSelectionneProperty(): float
    {
        if (!$this->inscriptionId) {
            return 0;
        }

        $inscription = collect($this->inscriptions)->firstWhere(
            'id',
            (int) $this->inscriptionId
        );

        $eleveId = $inscription['eleve_id'] ?? null;

        if (!$eleveId) {
            return 0;
        }

        return (float) ($this->arrieres[$eleveId] ?? 0);
    }

    public function toggleFrais($index): void
    {
        if ($this->arriereSelectionne > 0) {
            $this->addError(
                'frais',
                'Paiement bloqué : veuillez d’abord régler vos arriérés.'
            );
            return;
        }

        if (!isset($this->fraisDisponibles[$index])) {
            return;
        }

        $this->fraisDisponibles[$index]['selectionne'] =
            !$this->fraisDisponibles[$index]['selectionne'];

        $frais = $this->fraisDisponibles[$index];
        $fraisId = $frais['frais_id'];

        if ($this->fraisDisponibles[$index]['selectionne']) {
            $this->montants[$fraisId] = $frais['reste'];
        } else {
            unset($this->montants[$fraisId]);
        }

        $this->calculerTotaux();
    }

    public function updatedMontants(): void
    {
        if ($this->arriereSelectionne > 0) {
            $this->montants = [];
        }

        $this->calculerTotaux();
    }

    private function calculerTotaux(): void
    {
        $selectionnes = collect($this->fraisDisponibles)
            ->filter(fn ($f) => !empty($f['selectionne']));

        $this->totalFrais = $selectionnes->sum(
            fn ($f) => (float) $f['montant_frais']
        );

        $this->totalPaye = $selectionnes->sum(
            fn ($f) => (float) $f['montant_paye']
        );

        $this->totalReste = $selectionnes->sum(
            fn ($f) => (float) $f['reste']
        );
    }

    private function resetTotaux(): void
    {
        $this->totalFrais = 0;
        $this->totalPaye = 0;
        $this->totalReste = 0;
    }

    public function getTotalASaisirProperty(): float
    {
        if ($this->arriereSelectionne > 0) {
            return 0;
        }

        return collect($this->montants)->sum(
            fn ($montant) => (float) $montant
        );
    }

    public function enregistrer(): void
    {
        $this->resetErrorBag();

        if ($this->arriereSelectionne > 0) {
            $this->addError(
                'frais',
                'Paiement bloqué : veuillez d’abord régler vos arriérés.'
            );
            return;
        }

        $this->validate([
            'inscriptionId' => 'required|exists:inscriptions,id',
            'modePaiement' => 'required|string|max:100',
            'datePaiement' => 'required|date',
        ]);

        $inscription = Inscription::with([
            'frais', 'eleve', 'classe', 'annee',
        ])->findOrFail($this->inscriptionId);

        if ($this->anneeId && (int) $inscription->annee_id !== (int) $this->anneeId) {
            $this->addError(
                'frais',
                'L’inscription sélectionnée ne correspond pas à l’année choisie.'
            );
            return;
        }

        if ($inscription->eleve_id) {
            $this->verifierArriere((int) $inscription->eleve_id);

            if ($this->arriereSelectionne > 0) {
                $this->addError(
                    'frais',
                    'Paiement bloqué : cet élève doit d’abord régler ses arriérés.'
                );
                return;
            }
        }

        $fraisSelectionnes = collect($this->fraisDisponibles)
            ->filter(fn ($f) => !empty($f['selectionne']))
            ->values();

        if ($fraisSelectionnes->isEmpty()) {
            $this->addError('frais', 'Veuillez sélectionner au moins un frais.');
            return;
        }

        foreach ($fraisSelectionnes as $f) {
            $fraisId = $f['frais_id'];
            $montant = (float) ($this->montants[$fraisId] ?? 0);

            if ($montant <= 0) {
                $this->addError('frais', "Le montant pour « {$f['nom']} » est invalide.");
                return;
            }

            if ($montant > (float) $f['reste']) {
                $this->addError(
                    'frais',
                    "Le montant pour « {$f['nom']} » dépasse le reste dû (" .
                    number_format((float) $f['reste'], 0, ',', ' ') . ' FCFA).'
                );
                return;
            }
        }

        $numeroLot = 'LOT-' . strtoupper(Str::random(8)) . '-' . now()->format('Ymd');

        try {
            DB::transaction(function () use ($inscription, $fraisSelectionnes, $numeroLot) {
                foreach ($fraisSelectionnes as $f) {
                    $fraisId = (int) $f['frais_id'];
                    $montant = (float) $this->montants[$fraisId];

                    $pivot = DB::table('inscription_frais')
                        ->where('inscription_id', $inscription->id)
                        ->where('frais_id', $fraisId)
                        ->lockForUpdate()
                        ->first();

                    if (!$pivot) {
                        throw new \RuntimeException(
                            "Le frais « {$f['nom']} » n'est plus associé à l'inscription."
                        );
                    }

                    $resteActuel = (float) $pivot->reste;

                    if ($montant > $resteActuel) {
                        throw new \RuntimeException(
                            "Le montant pour « {$f['nom']} » dépasse le reste actuellement dû."
                        );
                    }

                    Paiement::create([
                        'inscription_id' => $inscription->id,
                        'frais_id' => $fraisId,
                        'montant_verse' => $montant,
                        'mode_paiement' => $this->modePaiement,
                        'date_paiement' => $this->datePaiement,
                        'numero_recu' => $numeroLot,
                        'user_id' => auth()->id(),
                    ]);

                    DB::table('inscription_frais')
                        ->where('id', $pivot->id)
                        ->update([
                            'montant_paye' => (float) $pivot->montant_paye + $montant,
                            'reste' => max(0, $resteActuel - $montant),
                        ]);
                }
            }, 3);
        } catch (\Throwable $e) {
            Log::error('Erreur paiement courant', [
                'numero_lot' => $numeroLot,
                'message' => $e->getMessage(),
            ]);

            $this->addError('frais', $e->getMessage());
            return;
        }

        $this->numeroLotGenere = $numeroLot;
        $this->successMessage = 'Paiement enregistré avec succès !';

        try {
            $this->imprimerRecu($numeroLot);
            $this->dispatch('impressionReussie', numeroLot: $numeroLot);
        } catch (\Throwable $e) {
            Log::error('Erreur impression reçu', [
                'numeroLot' => $numeroLot,
                'message' => $e->getMessage(),
            ]);

            $this->dispatch(
                'impressionEchouee',
                numeroLot: $numeroLot,
                message: $e->getMessage()
            );
        }

        $this->dispatch('masquerSucces');
        $this->dispatch('paiement-enregistre');

        $this->inscriptionId = null;
        $this->fraisDisponibles = [];
        $this->montants = [];
        $this->resetTotaux();
    }

    public function ouvrirPaiementArriere(int $eleveId): void
    {
        $this->resetErrorBag();

        if (!$this->anneeId) {
            $this->addError('frais', 'Aucune année scolaire sélectionnée.');
            return;
        }

        $anneeActuelle = Annee::find($this->anneeId);

        if (!$anneeActuelle) {
            $this->addError('frais', 'L’année scolaire sélectionnée est introuvable.');
            return;
        }

        $service = app(ArriereFraisService::class);
        $anneePrecedente = $service->anneePrecedente($anneeActuelle);

        if (!$anneePrecedente) {
            $this->anneePrecedenteId = null;
            $this->addError('frais', 'Aucune année scolaire précédente trouvée.');
            return;
        }

        $this->anneePrecedenteId = $anneePrecedente->id;

        $montantArriere = (float) $service->montantArriere(
            $eleveId,
            $anneeActuelle,
            self::CATEGORIES_ARRIERE
        );

        if ($montantArriere <= 0) {
            $this->arrieres[$eleveId] = 0;
            $this->addError('frais', 'Cet élève ne possède plus d’arriéré à payer.');
            return;
        }

        $details = $service->detailsArriere(
            $eleveId,
            $anneeActuelle,
            self::CATEGORIES_ARRIERE
        );

        if ($details->isEmpty()) {
            Log::warning('Arriéré détecté sans détail', [
                'eleve_id' => $eleveId,
                'annee_actuelle_id' => $anneeActuelle->id,
                'annee_precedente_id' => $anneePrecedente->id,
                'montant_arriere' => $montantArriere,
            ]);

            $this->addError(
                'frais',
                'Un arriéré existe, mais son détail est introuvable. Vérifiez ArriereFraisService.'
            );
            return;
        }

        $this->fraisArrieres = $details
            ->filter(fn ($frais) => (float) $frais->reste > 0)
            ->map(function ($frais) {
                return [
                    'id' => (int) $frais->id,
                    'inscription_id' => (int) $frais->inscription_id,
                    'frais_id' => (int) $frais->frais_id,
                    'nom' => $frais->frais_nom ?? 'Frais',
                    'montant_frais' => (float) $frais->montant_frais,
                    'montant_paye' => (float) $frais->montant_paye,
                    'reste' => (float) $frais->reste,
                ];
            })
            ->values()
            ->toArray();

        if (empty($this->fraisArrieres)) {
            $this->arrieres[$eleveId] = 0;
            $this->addError('frais', 'Tous les frais sont déjà soldés.');
            return;
        }

        $this->montantsArrieres = [];

        foreach ($this->fraisArrieres as $frais) {
            $this->montantsArrieres[$frais['id']] = $frais['reste'];
        }

        $this->inscriptionArriereId = $this->fraisArrieres[0]['inscription_id'] ?? null;
        $this->totalArriere = collect($this->fraisArrieres)
            ->sum(fn ($frais) => (float) $frais['reste']);

        $this->paiementArriere = true;
        $this->fraisDisponibles = [];
        $this->montants = [];
        $this->resetTotaux();
    }

    public function getTotalArriereASaisirProperty(): float
    {
        return collect($this->montantsArrieres)->sum(
            fn ($montant) => is_numeric($montant) ? (float) $montant : 0
        );
    }

    public function enregistrerPaiementArriere(): void
    {
        $this->resetErrorBag('frais');

        if (!$this->paiementArriere) {
            $this->addError('frais', 'Le paiement des arriérés n’est pas activé.');
            return;
        }

        if (!$this->anneeId || !$this->inscriptionArriereId || empty($this->fraisArrieres)) {
            $this->addError('frais', 'Aucun arriéré valide à payer.');
            return;
        }

        $this->validate([
            'modePaiement' => 'required|string|max:100',
            'datePaiement' => 'required|date',
        ]);

        $inscriptionArriere = Inscription::find($this->inscriptionArriereId);

        if (!$inscriptionArriere || !$inscriptionArriere->eleve_id) {
            $this->addError('frais', 'L’inscription associée à l’arriéré est introuvable.');
            return;
        }

        $eleveId = (int) $inscriptionArriere->eleve_id;
        $anneeActuelle = Annee::find($this->anneeId);

        if (!$anneeActuelle) {
            $this->addError('frais', 'L’année scolaire sélectionnée est introuvable.');
            return;
        }

        $service = app(ArriereFraisService::class);
        $anneePrecedente = $service->anneePrecedente($anneeActuelle);

        if (!$anneePrecedente) {
            $this->addError('frais', 'Aucune année scolaire précédente trouvée.');
            return;
        }

        $this->anneePrecedenteId = $anneePrecedente->id;

        $details = $service->detailsArriere(
            $eleveId,
            $anneeActuelle,
            self::CATEGORIES_ARRIERE
        );

        $detailsAutorises = $details
            ->filter(fn ($frais) => (float) $frais->reste > 0)
            ->keyBy(fn ($frais) => (int) $frais->id);

        $montantsAEnregistrer = [];

        foreach ($this->montantsArrieres as $id => $valeur) {
            $id = (int) $id;

            if (!$detailsAutorises->has($id)) {
                continue;
            }

            if (!is_numeric($valeur) || !is_finite((float) $valeur)) {
                $this->addError('frais', 'Un montant saisi est invalide.');
                return;
            }

            $montant = (float) $valeur;

            if ($montant < 0) {
                $this->addError('frais', 'Les montants négatifs ne sont pas autorisés.');
                return;
            }

            if ($montant == 0.0) {
                continue;
            }

            $resteAffiche = (float) $detailsAutorises->get($id)->reste;

            if ($montant > $resteAffiche) {
                $nom = $detailsAutorises->get($id)->frais_nom ?? 'Frais';
                $this->addError(
                    'frais',
                    "Le montant pour « {$nom} » dépasse le reste dû (" .
                    number_format($resteAffiche, 0, ',', ' ') . ' FCFA).'
                );
                return;
            }

            $montantsAEnregistrer[$id] = $montant;
        }

        if (empty($montantsAEnregistrer)) {
            $this->addError('frais', 'Veuillez saisir au moins un montant positif.');
            return;
        }

        $numeroLot = 'ARR-' . strtoupper(Str::random(8)) . '-' . now()->format('Ymd');
        $totalVerse = 0.0;

        try {
            DB::transaction(function () use (
                $montantsAEnregistrer,
                $numeroLot,
                $eleveId,
                $service,
                $anneeActuelle,
                &$totalVerse
            ) {
                foreach ($montantsAEnregistrer as $pivotId => $montant) {
                    $pivot = DB::table('inscription_frais')
                        ->where('id', $pivotId)
                        ->lockForUpdate()
                        ->first();

                    if (!$pivot) {
                        throw new \RuntimeException(
                            "Le frais d'arriéré #{$pivotId} est introuvable."
                        );
                    }

                    $inscription = Inscription::query()
                        ->where('id', $pivot->inscription_id)
                        ->where('eleve_id', $eleveId)
                        ->where('annee_id', $service->anneePrecedente($anneeActuelle)?->id)
                        ->first();

                    if (!$inscription) {
                        throw new \RuntimeException(
                            'Un frais sélectionné ne correspond pas à une inscription antérieure de cet élève.'
                        );
                    }

                    $fraisAutorise = $service->detailsArriere(
                        $eleveId,
                        $anneeActuelle,
                        self::CATEGORIES_ARRIERE
                    )->firstWhere('id', $pivotId);

                    if (!$fraisAutorise) {
                        throw new \RuntimeException(
                            'Un frais sélectionné ne correspond plus à un arriéré valide.'
                        );
                    }

                    $resteActuel = (float) $pivot->reste;

                    if ($resteActuel <= 0 || $montant > $resteActuel) {
                        throw new \RuntimeException(
                            'Le montant saisi dépasse le reste réellement dû ou le frais est déjà soldé.'
                        );
                    }

                    $paiement = Paiement::create([
                        'inscription_id' => $pivot->inscription_id,
                        'frais_id' => $pivot->frais_id,
                        'montant_verse' => $montant,
                        'mode_paiement' => $this->modePaiement,
                        'date_paiement' => $this->datePaiement,
                        'numero_recu' => $numeroLot,
                        'user_id' => auth()->id(),
                    ]);

                    DB::table('inscription_frais')
                        ->where('id', $pivotId)
                        ->update([
                            'montant_paye' => (float) $pivot->montant_paye + $montant,
                            'reste' => max(0, $resteActuel - $montant),
                        ]);

                    $totalVerse += $montant;
                }
            }, 3);
        } catch (\Throwable $e) {
            Log::error('Erreur paiement arriéré', [
                'numero_recu' => $numeroLot,
                'eleve_id' => $eleveId,
                'message' => $e->getMessage(),
            ]);

            $this->addError('frais', $e->getMessage());
            return;
        }

        if ($totalVerse <= 0) {
            $this->addError('frais', 'Aucun paiement d’arriéré n’a été enregistré.');
            return;
        }

        $this->numeroLotGenere = $numeroLot;

        $arriereRestant = (float) $service->montantArriere(
            $eleveId,
            $anneeActuelle,
            self::CATEGORIES_ARRIERE
        );

        $this->arrieres[$eleveId] = $arriereRestant;

        if ($arriereRestant <= 0) {
            $this->successMessage =
                'Arriéré totalement soldé. Le paiement de l’année courante est maintenant débloqué.';
        } else {
            $this->successMessage =
                'Paiement de l’arriéré enregistré. Reste à payer : ' .
                number_format($arriereRestant, 0, ',', ' ') . ' FCFA.';
        }

        try {
            $this->imprimerRecu($numeroLot);
            $this->dispatch('impressionReussie', numeroLot: $numeroLot);
        } catch (\Throwable $e) {
            Log::error('Erreur impression reçu arriéré', [
                'numeroLot' => $numeroLot,
                'message' => $e->getMessage(),
            ]);

            $this->dispatch(
                'impressionEchouee',
                numeroLot: $numeroLot,
                message: $e->getMessage()
            );
        }

        $this->dispatch('paiement-enregistre');

        if ($arriereRestant > 0) {
            $this->rechargerArrieres($eleveId);
            $this->paiementArriere = true;
            return;
        }

        $this->fermerPaiementArriere();

        if ($this->inscriptionId) {
            $this->chargerFraisInscription((int) $this->inscriptionId);
        }

        $this->dispatch('masquerSucces');
    }

    private function rechargerArrieres(int $eleveId): void
    {
        if (!$this->anneeId) {
            return;
        }

        $anneeActuelle = Annee::find($this->anneeId);

        if (!$anneeActuelle) {
            return;
        }

        $service = app(ArriereFraisService::class);
        $details = $service->detailsArriere(
            $eleveId,
            $anneeActuelle,
            self::CATEGORIES_ARRIERE
        );

        $this->fraisArrieres = $details
            ->filter(fn ($frais) => (float) $frais->reste > 0)
            ->map(function ($frais) {
                return [
                    'id' => (int) $frais->id,
                    'inscription_id' => (int) $frais->inscription_id,
                    'frais_id' => (int) $frais->frais_id,
                    'nom' => $frais->frais_nom ?? 'Frais',
                    'montant_frais' => (float) $frais->montant_frais,
                    'montant_paye' => (float) $frais->montant_paye,
                    'reste' => (float) $frais->reste,
                ];
            })
            ->values()
            ->toArray();

        $this->montantsArrieres = [];

        foreach ($this->fraisArrieres as $frais) {
            $this->montantsArrieres[$frais['id']] = $frais['reste'];
        }

        $this->totalArriere = collect($this->fraisArrieres)
            ->sum(fn ($frais) => (float) $frais['reste']);

        $this->inscriptionArriereId =
            $this->fraisArrieres[0]['inscription_id'] ?? null;

        $this->arrieres[$eleveId] = (float) $service->montantArriere(
            $eleveId,
            $anneeActuelle,
            self::CATEGORIES_ARRIERE
        );
    }

    public function fermerPaiementArriere(): void
    {
        $this->paiementArriere = false;
        $this->fraisArrieres = [];
        $this->montantsArrieres = [];
        $this->inscriptionArriereId = null;
        $this->totalArriere = 0;
    }

    public function imprimerRecu(?string $numeroLot = null): void
{
    $numeroLot = $numeroLot ?: $this->numeroLotGenere;

    if (!$numeroLot) {
        throw new \RuntimeException(
            'Aucun numéro de reçu disponible pour l’impression.'
        );
    }

    $paiements = Paiement::with([
        'inscription.eleve',
        'inscription.classe',
        'inscription.annee',
        'frais',
        'user',
    ])->where('numero_recu', $numeroLot)->get();

    if ($paiements->isEmpty()) {
        throw new \RuntimeException("Aucun paiement trouvé pour {$numeroLot}");
    }

    $paiement = $paiements->first();
    $inscription = $paiement->inscription;

    if (!$inscription) {
        throw new \RuntimeException('Inscription introuvable.');
    }

    $fraisIds = $paiements->pluck('frais_id')->filter()->unique()->values();

    if ($fraisIds->isEmpty()) {
        throw new \RuntimeException('Aucun frais associé à ce reçu.');
    }

    $details = $paiements->map(function ($paiement) {
        return [
            'nom' => $paiement->frais->nom
                ?? $paiement->frais->description
                ?? 'Frais',
            'montant' => (float) ($paiement->montant_verse ?? 0),
        ];
    })->toArray();

    $totalRecu = (float) $paiements->sum('montant_verse');

    // Source de vérité : la table pivot inscription_frais
    // (c'est ce qu'affichent l'écran des arriérés et la base).
    // Les arriérés peuvent concerner une autre inscription que celle du reçu,
    // on filtre donc sur l'inscription et les frais de CE reçu.
    $pivots = DB::table('inscription_frais')
        ->where('inscription_id', $inscription->id)
        ->whereIn('frais_id', $fraisIds->toArray())
        ->get();

    $totalFrais = (float) $pivots->sum('montant_frais');
    $totalPaye  = (float) $pivots->sum('montant_paye');
    $reste      = max(0, (float) $pivots->sum('reste'));

    $statut = $reste <= 0
        ? 'SOLDE'
        : ($totalPaye > 0 ? 'PARTIEL' : 'NON PAYE');

    $utilisateur = $paiement->user;
    $nomUtilisateur = $utilisateur
        ? trim(($utilisateur->prenom ?? '') . ' ' . ($utilisateur->nom ?? ''))
        : 'Utilisateur inconnu';

    if ($nomUtilisateur === '') {
        $nomUtilisateur = $utilisateur->email ?? 'Utilisateur inconnu';
    }

    $serviceRecu = new RecuThermiqueService();

    $ticket = $serviceRecu->generate([
        'numero' => $numeroLot,
        'qr_data' => $numeroLot,
        'date' => $paiement->date_paiement
            ? \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y')
            : now()->format('d/m/Y'),
        'eleve' => $inscription->eleve
            ? trim(($inscription->eleve->nom ?? '') . ' ' . ($inscription->eleve->prenom ?? ''))
            : '-',
        'matricule' => $inscription->eleve->matricule ?? '-',
        'classe' => $inscription->classe->nom ?? '-',
        'annee' => $inscription->annee->nom ?? '-',
        'details' => $details,
        'total_recu' => $totalRecu,
        'total_paye' => $totalPaye,
        'total_frais' => $totalFrais,
        'reste' => $reste,
        'statut' => $statut,
        'mode_paiement' => $paiement->mode_paiement ?? '-',
        'utilisateur' => $nomUtilisateur,
        'enregistre_par' => $nomUtilisateur,
    ]);

    $printer = new ThermalPrinterService('Xprinter_POS58');
    $result = $printer->print($ticket);

    Log::info('Reçu imprimé automatiquement', [
        'numero_recu' => $numeroLot,
        'imprimante' => 'Xprinter_POS58',
        'frais_ids' => $fraisIds->toArray(),
        'total_recu' => $totalRecu,
        'total_frais_selectionnes' => $totalFrais,
        'total_paye_frais_selectionnes' => $totalPaye,
        'reste_frais_selectionnes' => $reste,
        'utilisateur_id' => $paiement->user_id,
        'utilisateur' => $nomUtilisateur,
        'resultat' => $result,
    ]);
}
    private function chargerAnciensRecus(): void
    {
        $this->anciensRecus = [];

        if (!$this->anneeId || !$this->cycleId || !$this->classeId || !$this->inscriptionId) {
            return;
        }

        $inscriptionSelectionnee = Inscription::query()
            ->where('id', $this->inscriptionId)
            ->where('annee_id', $this->anneeId)
            ->where('classe_id', $this->classeId)
            ->whereHas('classe', function ($query) {
                $query->where('cycle_id', $this->cycleId);
            })
            ->first();

        if (!$inscriptionSelectionnee || !$inscriptionSelectionnee->eleve_id) {
            return;
        }

        $eleveId = $inscriptionSelectionnee->eleve_id;

        $paiements = Paiement::with([
            'frais',
            'inscription.annee',
            'inscription.classe',
            'inscription.eleve',
        ])
            ->whereNotNull('numero_recu')
            ->where('numero_recu', '<>', '')
            ->whereHas('inscription', function ($query) use ($eleveId) {
                $query->where('eleve_id', $eleveId);
            })
            ->orderByDesc('date_paiement')
            ->orderByDesc('id')
            ->get();

        $this->anciensRecus = $paiements
            ->groupBy('numero_recu')
            ->map(function ($lignes, $numero) {
                $premierPaiement = $lignes->first();
                $inscription = $premierPaiement->inscription;
                $eleve = $inscription?->eleve;

                return [
                    'numero_recu' => $numero,
                    'date' => $premierPaiement->date_paiement
                        ? \Carbon\Carbon::parse($premierPaiement->date_paiement)->format('d/m/Y')
                        : '-',
                    'mode_paiement' => $premierPaiement->mode_paiement ?? '-',
                    'total' => (float) $lignes->sum('montant_verse'),
                    'frais' => $lignes->map(function ($paiement) {
                        return $paiement->frais->nom
                            ?? $paiement->frais->description
                            ?? 'Frais';
                    })->unique()->implode(', '),
                    'annee' => $inscription?->annee?->nom ?? '-',
                    'classe' => $inscription?->classe?->nom ?? '-',
                    'eleve' => trim(($eleve?->nom ?? '') . ' ' . ($eleve?->prenom ?? '')),
                ];
            })
            ->values()
            ->toArray();
    }

    public function reimprimerRecu(string $numeroRecu): void
    {
        $this->resetErrorBag('recus');

        if (!$this->anneeId || !$this->cycleId || !$this->classeId || !$this->inscriptionId) {
            $this->addError(
                'recus',
                'Veuillez sélectionner une année, un cycle, une classe et un élève.'
            );
            return;
        }

        $inscription = Inscription::query()
            ->where('id', $this->inscriptionId)
            ->where('annee_id', $this->anneeId)
            ->where('classe_id', $this->classeId)
            ->whereHas('classe', function ($query) {
                $query->where('cycle_id', $this->cycleId);
            })
            ->first();

        if (!$inscription || !$inscription->eleve_id) {
            $this->addError('recus', 'L’inscription sélectionnée est invalide.');
            return;
        }

        $recuExiste = Paiement::query()
            ->where('numero_recu', $numeroRecu)
            ->whereHas('inscription', function ($query) use ($inscription) {
                $query->where('eleve_id', $inscription->eleve_id);
            })
            ->exists();

        if (!$recuExiste) {
            $this->addError('recus', 'Ce reçu est introuvable pour cet élève.');
            return;
        }

        try {
            $this->imprimerRecu($numeroRecu);
            $this->dispatch('impressionReussie', numeroLot: $numeroRecu);
        } catch (\Throwable $e) {
            Log::error('Erreur réimpression reçu', [
                'numero_recu' => $numeroRecu,
                'eleve_id' => $inscription->eleve_id,
                'message' => $e->getMessage(),
            ]);

            $this->dispatch(
                'impressionEchouee',
                numeroLot: $numeroRecu,
                message: $e->getMessage()
            );
        }
    }

    public function messageArrieres(int $eleveId): ?string
    {
        if (!$this->anneeId) {
            return null;
        }

        $anneeActuelle = Annee::find($this->anneeId);

        if (!$anneeActuelle) {
            return null;
        }

        $service = app(ArriereFraisService::class);
        $anneePrecedente = $service->anneePrecedente($anneeActuelle);

        if (!$anneePrecedente) {
            return null;
        }

        // Utiliser l'année précédente uniquement pour le libellé affiché.
        $anneeLibelle = $anneePrecedente->nom;

        // Les méthodes de calcul reçoivent l'année actuelle :
        // le service détermine lui-même l'année antérieure concernée.
        $types = [];

        $arriereScolarite = (float) $service->montantArriere(
            $eleveId,
            $anneeActuelle,
            self::CATEGORIES_ARRIERE
        );

        if ($arriereScolarite > 0) {
            $types[] = 'scolarité';
        }

        if (method_exists($service, 'montantArriereTd')) {
            $arriereTd = (float) $service->montantArriereTd($eleveId, $anneeActuelle);
            if ($arriereTd > 0) {
                $types[] = 'frais de TD';
            }
        }

        if (method_exists($service, 'montantArriereCantine')) {
            $arriereCantine = (float) $service->montantArriereCantine($eleveId, $anneeActuelle);
            if ($arriereCantine > 0) {
                $types[] = 'cantine';
            }
        }

        if (method_exists($service, 'montantArriereTransport')) {
            $arriereTransport = (float) $service->montantArriereTransport($eleveId, $anneeActuelle);
            if ($arriereTransport > 0) {
                $types[] = 'transport';
            }
        }

        if (empty($types)) {
            return "Cet élève possède un arriéré pour l'année {$anneeLibelle}.";
        }

        if (count($types) === 1) {
            return "Cet élève possède un arriéré de {$types[0]} pour l'année {$anneeLibelle}.";
        }

        $dernier = array_pop($types);

        return 'Cet élève possède des arriérés de '
            . implode(', ', $types)
            . " et de {$dernier} pour l'année {$anneeLibelle}.";
    }

    public function render()
    {
        return view('livewire.paiements.paiement-multiple', [
            'annees' => Annee::all(),
            'cycles' => Cycle::all(),
        ]);
    }
}