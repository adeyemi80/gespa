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
    // ============================================================
    // FILTRES EN CASCADE
    // ============================================================

    public $anneeId = null;
    public $anneePrecedenteId = null;
    public $cycleId = null;
    public $classeId = null;

    // ============================================================
    // LISTES DYNAMIQUES
    // ============================================================

    public $classes = [];
    public $inscriptions = [];

    // ============================================================
    // ÉLÈVE SÉLECTIONNÉ
    // ============================================================

    public $inscriptionId = null;
    public $fraisDisponibles = [];
    public $aucunFraisAffecte = false;

    // ============================================================
    // PAIEMENT ANNÉE COURANTE
    // ============================================================

    public $modePaiement = '';
    public $datePaiement = '';
    public $montants = [];

    // ============================================================
    // TOTAUX ANNÉE COURANTE
    // ============================================================

    public $totalFrais = 0;
    public $totalPaye = 0;
    public $totalReste = 0;

    // ============================================================
    // MESSAGES
    // ============================================================

    public $successMessage = '';
    public $numeroLotGenere = null;

    // ============================================================
    // ARRIÉRÉS
    // ============================================================

    public bool $paiementArriere = false;

    public $inscriptionArriereId = null;

    public $fraisArrieres = [];

    public $montantsArrieres = [];

    public $totalArriere = 0;

    /**
     * Montant d'arriéré par élève.
     *
     * Exemple :
     * [
     *     12 => 33000,
     *     13 => 0,
     * ]
     */
    public array $arrieres = [];

    // ============================================================
    // CONSTANTE DES CATÉGORIES D'ARRIÉRÉS
    // ============================================================

    /**
     * Catégories utilisées pour le blocage et le paiement
     * des arriérés.
     *
     * IMPORTANT :
     * On utilise exactement les mêmes catégories partout.
     */
    private const CATEGORIES_ARRIERE = [1, 17];

    // ============================================================
    // MOUNT
    // ============================================================

    public function mount()
    {
        $this->datePaiement = now()->format('Y-m-d');

        $anneeEnCours = $this->getAnneeEnCours();

        if ($anneeEnCours) {

            $this->anneeId = $anneeEnCours->id;

            $service = app(ArriereFraisService::class);

            $anneePrecedente = $service->anneePrecedente(
                $anneeEnCours
            );

            $this->anneePrecedenteId =
                $anneePrecedente?->id;
        }

        $this->arrieres = [];
    }

    // ============================================================
    // VÉRIFIER L'ARRIÉRÉ D'UN ÉLÈVE
    // ============================================================

    public function verifierArriere(int $eleveId): void
    {
        if (!$this->anneeId) {

            $this->arrieres[$eleveId] = 0;

            return;
        }

        $annee = Annee::find($this->anneeId);

        if (!$annee) {

            $this->arrieres[$eleveId] = 0;

            return;
        }

        $service = app(ArriereFraisService::class);

        $anneePrecedente =
            $service->anneePrecedente($annee);

        if (!$anneePrecedente) {

            $this->anneePrecedenteId = null;

            $this->arrieres[$eleveId] = 0;

            return;
        }

        $this->anneePrecedenteId =
            $anneePrecedente->id;

        /*
        |--------------------------------------------------------------------------
        | Calcul de l'arriéré
        |--------------------------------------------------------------------------
        |
        | On utilise les mêmes catégories que pour le paiement
        | des arriérés.
        |
        */

        $this->arrieres[$eleveId] =
            (float) $service->montantArriere(
                $eleveId,
                $annee,
                self::CATEGORIES_ARRIERE
            );
    }

    // ============================================================
    // ANNÉE EN COURS
    // ============================================================

    private function getAnneeEnCours()
    {
        $annees = Annee::all();

        return Annee::where('en_cours', true)->first()
            ?? $annees->firstWhere('en_cours', 't')
            ?? $annees->firstWhere('id', 2);
    }

    // ============================================================
    // CHANGEMENT D'ANNÉE
    // ============================================================

    public function updatedAnneeId($anneeId)
    {
        $this->cycleId = null;
        $this->classeId = null;
        $this->inscriptionId = null;

        $this->classes = [];
        $this->inscriptions = [];

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

        $anneePrecedente =
            app(ArriereFraisService::class)
                ->anneePrecedente($annee);

        $this->anneePrecedenteId =
            $anneePrecedente?->id;
    }

    // ============================================================
    // CYCLE → CLASSES
    // ============================================================

    public function updatedCycleId($value)
    {
        $this->classeId = null;
        $this->inscriptionId = null;

        $this->fraisDisponibles = [];
        $this->montants = [];

        $this->aucunFraisAffecte = false;

        $this->arrieres = [];

        $this->fermerPaiementArriere();

        $this->resetTotaux();

        $this->classes = $value
            ? Classe::where('cycle_id', $value)
                ->orderByNiveau()
                ->get()
                ->toArray()
            : [];

        $this->inscriptions = [];
    }

    // ============================================================
    // CLASSE → ÉLÈVES
    // ============================================================

    public function updatedClasseId($value)
    {
        $this->inscriptionId = null;

        $this->fraisDisponibles = [];
        $this->montants = [];

        $this->aucunFraisAffecte = false;

        $this->arrieres = [];

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

        /*
        |--------------------------------------------------------------------------
        | Vérifier les arriérés de tous les élèves
        |--------------------------------------------------------------------------
        */

        foreach ($inscriptions as $inscription) {

            if ($inscription->eleve_id) {

                $this->verifierArriere(
                    (int) $inscription->eleve_id
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Préparer la liste pour la vue
        |--------------------------------------------------------------------------
        */

        $this->inscriptions = $inscriptions
            ->map(function ($inscription) {

                return [
                    'id' => $inscription->id,

                    'eleve_id' =>
                        $inscription->eleve_id,

                    'nom' =>
                        trim(
                            ($inscription->eleve->nom ?? '')
                            . ' '
                            .
                            ($inscription->eleve->prenom ?? '')
                        ),
                ];
            })
            ->toArray();
    }

    // ============================================================
    // ÉLÈVE → FRAIS
    // ============================================================

    public function updatedInscriptionId($value)
    {
        $this->fraisDisponibles = [];
        $this->montants = [];

        $this->aucunFraisAffecte = false;

        $this->fermerPaiementArriere();

        $this->resetTotaux();

        if (!$value) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Retrouver l'élève sélectionné
        |--------------------------------------------------------------------------
        */

        $inscriptionSelectionnee = collect(
            $this->inscriptions
        )->firstWhere(
            'id',
            (int) $value
        );

        if (!$inscriptionSelectionnee) {
            return;
        }

        $eleveId =
            $inscriptionSelectionnee['eleve_id'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Vérifier l'arriéré
        |--------------------------------------------------------------------------
        */

        if ($eleveId) {

            $this->verifierArriere(
                (int) $eleveId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SI ARRIÉRÉ :
        | on ne charge pas les frais de l'année courante
        |--------------------------------------------------------------------------
        */

        if ($this->arriereSelectionne > 0) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Charger les frais de l'inscription
        |--------------------------------------------------------------------------
        */

        $this->chargerFraisInscription(
            (int) $value
        );
    }

    // ============================================================
    // CHARGER LES FRAIS D'UNE INSCRIPTION
    // ============================================================

    private function chargerFraisInscription(int $inscriptionId): void
    {
        $this->fraisDisponibles = [];
        $this->montants = [];

        $this->aucunFraisAffecte = false;

        $this->resetTotaux();

        $inscription = Inscription::with([
            'inscriptionFrais.frais'
        ])->find($inscriptionId);

        if (!$inscription) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Sécurité : vérifier l'année
        |--------------------------------------------------------------------------
        */

        if (
            $this->anneeId
            &&
            (int) $inscription->annee_id
                !== (int) $this->anneeId
        ) {
            return;
        }

        $tousLesFrais =
            $inscription->inscriptionFrais;

        /*
        |--------------------------------------------------------------------------
        | Aucun frais affecté
        |--------------------------------------------------------------------------
        */

        if ($tousLesFrais->isEmpty()) {

            $this->aucunFraisAffecte = true;

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Frais avec reste
        |--------------------------------------------------------------------------
        */

        $this->fraisDisponibles = $tousLesFrais
            ->filter(
                fn($if) =>
                    (float) ($if->reste ?? 0) > 0
            )
            ->map(function ($if) {

                return [

                    'frais_id' =>
                        $if->frais_id,

                    'nom' =>
                        $if->frais->nom
                        ?? $if->frais->description
                        ?? 'Frais',

                    'montant_frais' =>
                        (float) $if->montant_frais,

                    'montant_paye' =>
                        (float) $if->montant_frais
                        -
                        (float) $if->reste,

                    'reste' =>
                        (float) $if->reste,

                    'selectionne' =>
                        false,
                ];
            })
            ->values()
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Tous les frais sont soldés
        |--------------------------------------------------------------------------
        */

        if (empty($this->fraisDisponibles)) {
            $this->aucunFraisAffecte = false;
        }
    }

    // ============================================================
    // ARRIÉRÉ DE L'ÉLÈVE SÉLECTIONNÉ
    // ============================================================

    public function getArriereSelectionneProperty()
    {
        if (!$this->inscriptionId) {
            return 0;
        }

        $inscription = collect(
            $this->inscriptions
        )->firstWhere(
            'id',
            (int) $this->inscriptionId
        );

        if (!$inscription) {
            return 0;
        }

        $eleveId =
            $inscription['eleve_id'] ?? null;

        if (!$eleveId) {
            return 0;
        }

        return (float) (
            $this->arrieres[$eleveId] ?? 0
        );
    }

    // ============================================================
    // COCHER / DÉCOCHER UN FRAIS
    // ============================================================

    public function toggleFrais($index)
    {
        /*
        |--------------------------------------------------------------------------
        | Sécurité arriéré
        |--------------------------------------------------------------------------
        */

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

        $frais =
            $this->fraisDisponibles[$index];

        $fraisId =
            $frais['frais_id'];

        if (
            $this->fraisDisponibles[$index]['selectionne']
        ) {

            $this->montants[$fraisId] =
                $frais['reste'];

        } else {

            unset(
                $this->montants[$fraisId]
            );
        }

        $this->calculerTotaux();
    }

    // ============================================================
    // CHANGEMENT D'UN MONTANT
    // ============================================================

    public function updatedMontants()
    {
        if ($this->arriereSelectionne > 0) {

            $this->montants = [];

            $this->calculerTotaux();

            return;
        }

        $this->calculerTotaux();
    }

    // ============================================================
    // CALCUL DES TOTAUX
    // ============================================================

    private function calculerTotaux()
    {
        $selectionnes = collect(
            $this->fraisDisponibles
        )->filter(
            fn($f) =>
                !empty($f['selectionne'])
        );

        $this->totalFrais =
            $selectionnes->sum(
                fn($f) =>
                    (float) $f['montant_frais']
            );

        $this->totalPaye =
            $selectionnes->sum(
                fn($f) =>
                    (float) $f['montant_paye']
            );

        $this->totalReste =
            $selectionnes->sum(
                fn($f) =>
                    (float) $f['reste']
            );
    }

    // ============================================================
    // RESET TOTAUX
    // ============================================================

    private function resetTotaux()
    {
        $this->totalFrais = 0;
        $this->totalPaye = 0;
        $this->totalReste = 0;
    }

    // ============================================================
    // TOTAL À SAISIR ANNÉE COURANTE
    // ============================================================

    public function getTotalASaisirProperty()
    {
        if ($this->arriereSelectionne > 0) {
            return 0;
        }

        return collect(
            $this->montants
        )->sum(
            fn($montant) =>
                (float) $montant
        );
    }

    // ============================================================
    // ENREGISTREMENT PAIEMENT ANNÉE COURANTE
    // ============================================================

    public function enregistrer()
    {
        /*
        |--------------------------------------------------------------------------
        | 1. CONTRÔLE ARRIÉRÉ
        |--------------------------------------------------------------------------
        */

        if ($this->arriereSelectionne > 0) {

            $this->addError(
                'frais',
                'Paiement bloqué : veuillez d’abord régler vos arriérés.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | 2. VALIDATION
        |--------------------------------------------------------------------------
        */

        $this->validate([
            'inscriptionId' =>
                'required|exists:inscriptions,id',

            'modePaiement' =>
                'required|string',

            'datePaiement' =>
                'required|date',
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3. RECHARGER L'INSCRIPTION
        |--------------------------------------------------------------------------
        */

        $inscription = Inscription::with([
            'frais',
            'eleve',
            'classe',
            'annee',
        ])->findOrFail(
            $this->inscriptionId
        );

        /*
        |--------------------------------------------------------------------------
        | 4. Vérifier l'année
        |--------------------------------------------------------------------------
        */

        if (
            $this->anneeId
            &&
            (int) $inscription->annee_id
                !== (int) $this->anneeId
        ) {

            $this->addError(
                'frais',
                'L’inscription sélectionnée ne correspond pas à l’année choisie.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | 5. REFAIRE LE CONTRÔLE DE L'ARRIÉRÉ
        |--------------------------------------------------------------------------
        */

        if ($inscription->eleve_id) {

            $this->verifierArriere(
                (int) $inscription->eleve_id
            );

            if ($this->arriereSelectionne > 0) {

                $this->addError(
                    'frais',
                    'Paiement bloqué : cet élève doit d’abord régler ses arriérés.'
                );

                return;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 6. FRAIS SÉLECTIONNÉS
        |--------------------------------------------------------------------------
        */

        $fraisSelectionnes = collect(
            $this->fraisDisponibles
        )
            ->filter(
                fn($f) =>
                    !empty($f['selectionne'])
            )
            ->values();

        if ($fraisSelectionnes->isEmpty()) {

            $this->addError(
                'frais',
                'Veuillez sélectionner au moins un frais.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | 7. VALIDATION DES MONTANTS
        |--------------------------------------------------------------------------
        */

        foreach ($fraisSelectionnes as $f) {

            $fraisId =
                $f['frais_id'];

            $montant =
                (float) (
                    $this->montants[$fraisId] ?? 0
                );

            if ($montant <= 0) {

                $this->addError(
                    'frais',
                    "Le montant pour « {$f['nom']} » est invalide."
                );

                return;
            }

            if (
                $montant >
                (float) $f['reste']
            ) {

                $this->addError(
                    'frais',
                    "Le montant pour « {$f['nom']} » dépasse le reste dû ("
                    .
                    number_format(
                        $f['reste'],
                        0,
                        ',',
                        ' '
                    )
                    .
                    " FCFA)."
                );

                return;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 8. NUMÉRO DU LOT
        |--------------------------------------------------------------------------
        */

        $numeroLot =
            'LOT-'
            .
            strtoupper(
                Str::random(8)
            )
            .
            '-'
            .
            now()->format('Ymd');

        /*
        |--------------------------------------------------------------------------
        | 9. TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $inscription,
            $fraisSelectionnes,
            $numeroLot
        ) {

            foreach ($fraisSelectionnes as $f) {

                $fraisId =
                    $f['frais_id'];

                $montant =
                    (float) $this->montants[$fraisId];

                /*
                |--------------------------------------------------------------------------
                | Recharger le pivot pour éviter un ancien reste
                |--------------------------------------------------------------------------
                */

                $inscriptionFrais = DB::table(
                    'inscription_frais'
                )
                    ->where(
                        'inscription_id',
                        $inscription->id
                    )
                    ->where(
                        'frais_id',
                        $fraisId
                    )
                    ->first();

                if (!$inscriptionFrais) {

                    throw new \RuntimeException(
                        "Le frais « {$f['nom']} » n'est plus associé à l'inscription."
                    );
                }

                $resteActuel =
                    (float) $inscriptionFrais->reste;

                if ($montant > $resteActuel) {

                    throw new \RuntimeException(
                        "Le montant pour « {$f['nom']} » dépasse le reste actuellement dû."
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Création du paiement
                |--------------------------------------------------------------------------
                */

                $paiement = Paiement::create([

                    'inscription_id' =>
                        $inscription->id,

                    'frais_id' =>
                        $fraisId,

                    'montant_verse' =>
                        $montant,

                    'mode_paiement' =>
                        $this->modePaiement,

                    'date_paiement' =>
                        $this->datePaiement,

                    'numero_recu' =>
                        $numeroLot,
                ]);

                // Utilisateur connecté ayant réellement enregistré le paiement.
                $paiement->user_id = auth()->id();
                $paiement->save();

                /*
                |--------------------------------------------------------------------------
                | Nouveau reste
                |--------------------------------------------------------------------------
                */

                $nouveauReste =
                    max(
                        0,
                        $resteActuel - $montant
                    );

                /*
                |--------------------------------------------------------------------------
                | Mise à jour du pivot
                |--------------------------------------------------------------------------
                */

                DB::table('inscription_frais')
                    ->where(
                        'id',
                        $inscriptionFrais->id
                    )
                    ->update([
                        'montant_paye' =>
                            (float) $inscriptionFrais->montant_paye
                            + $montant,

                        'reste' =>
                            $nouveauReste,
                    ]);
            }
        });

        /*
        |--------------------------------------------------------------------------
        | 10. MESSAGE DE SUCCÈS
        |--------------------------------------------------------------------------
        */

        $this->numeroLotGenere =
            $numeroLot;

        $this->successMessage =
            'Paiement enregistré avec succès !';

        /*
        |--------------------------------------------------------------------------
        | 11. IMPRESSION
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
        | 12. ÉVÉNEMENTS
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
        | 13. RESET
        |--------------------------------------------------------------------------
        */

        $this->inscriptionId = null;

        $this->fraisDisponibles = [];

        $this->montants = [];

        $this->resetTotaux();
    }

    // ============================================================
    // OUVRIR LE PAIEMENT DES ARRIÉRÉS
    // ============================================================

    public function ouvrirPaiementArriere(int $eleveId): void
{
    /*
    |--------------------------------------------------------------------------
    | 1. Vérifier l'année actuelle
    |--------------------------------------------------------------------------
    */

    if (!$this->anneeId) {

        $this->addError(
            'frais',
            'Aucune année scolaire actuelle sélectionnée.'
        );

        return;
    }

    $anneeActuelle = Annee::find($this->anneeId);

    if (!$anneeActuelle) {

        $this->addError(
            'frais',
            'L’année scolaire actuelle est introuvable.'
        );

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Vérifier qu'une année précédente existe
    |--------------------------------------------------------------------------
    */

    if (!$this->anneePrecedenteId) {

        $this->addError(
            'frais',
            'Aucune année précédente trouvée.'
        );

        return;
    }

    $anneePrecedente = Annee::find(
        $this->anneePrecedenteId
    );

    if (!$anneePrecedente) {

        $this->addError(
            'frais',
            'L’année précédente est introuvable.'
        );

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Service des arriérés
    |--------------------------------------------------------------------------
    */

    $service = app(
        ArriereFraisService::class
    );

    /*
    |--------------------------------------------------------------------------
    | 4. Calculer l'arriéré réel
    |--------------------------------------------------------------------------
    |
    | IMPORTANT :
    | On passe l'année ACTUELLE au service.
    |
    | Le service va automatiquement rechercher :
    |
    | 2026-2027
    |     ↓
    | 2025-2026
    |
    */

    $montantArriere = (float) $service->montantArriere(
        $eleveId,
        $anneeActuelle,
        self::CATEGORIES_ARRIERE
    );

    /*
    |--------------------------------------------------------------------------
    | 5. Aucun arriéré
    |--------------------------------------------------------------------------
    */

    if ($montantArriere <= 0) {

        $this->arrieres[$eleveId] = 0;

        $this->addError(
            'frais',
            'Cet élève ne possède plus d’arriéré à payer.'
        );

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | 6. Charger le détail des arriérés
    |--------------------------------------------------------------------------
    |
    | IMPORTANT :
    | Ici aussi on passe l'année ACTUELLE.
    |
    | NE PAS passer $anneePrecedente ici.
    |
    */

    $details = $service->detailsArriere(
        $eleveId,
        $anneeActuelle,
        self::CATEGORIES_ARRIERE
    );

    /*
    |--------------------------------------------------------------------------
    | 7. Arriéré trouvé mais détail introuvable
    |--------------------------------------------------------------------------
    */

    if ($details->isEmpty()) {

        Log::warning(
            'Incohérence dans les arriérés',
            [
                'eleve_id' =>
                    $eleveId,

                'annee_actuelle_id' =>
                    $anneeActuelle->id,

                'annee_precedente_id' =>
                    $anneePrecedente->id,

                'montant_arriere' =>
                    $montantArriere,
            ]
        );

        $this->addError(
            'frais',
            'Un arriéré de '
            .
            number_format(
                $montantArriere,
                0,
                ',',
                ' '
            )
            .
            ' FCFA existe, mais son détail ne peut pas être chargé.'
        );

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | 8. Préparer les frais d'arriérés
    |--------------------------------------------------------------------------
    */

    $this->fraisArrieres = $details
        ->map(function ($frais) {

            return [

                'id' =>
                    $frais->id,

                'inscription_id' =>
                    $frais->inscription_id,

                'frais_id' =>
                    $frais->frais_id,

                'nom' =>
                    $frais->frais_nom
                    ?? 'Frais',

                'montant_frais' =>
                    (float) $frais->montant_frais,

                'montant_paye' =>
                    (float) $frais->montant_paye,

                'reste' =>
                    (float) $frais->reste,
            ];
        })
        ->values()
        ->toArray();

    /*
    |--------------------------------------------------------------------------
    | 9. Inscription concernée
    |--------------------------------------------------------------------------
    */

    $this->inscriptionArriereId =
        $this->fraisArrieres[0]['inscription_id']
        ?? null;

    /*
    |--------------------------------------------------------------------------
    | 10. Préparer les montants à payer
    |--------------------------------------------------------------------------
    */

    $this->montantsArrieres = [];

    foreach (
        $this->fraisArrieres as $frais
    ) {

        $this->montantsArrieres[
            $frais['id']
        ] = $frais['reste'];
    }

    /*
    |--------------------------------------------------------------------------
    | 11. Calculer le total des arriérés
    |--------------------------------------------------------------------------
    */

    $this->totalArriere =
        collect($this->fraisArrieres)
            ->sum(
                fn ($frais) =>
                    (float) $frais['reste']
            );

    /*
    |--------------------------------------------------------------------------
    | 12. Activer le mode paiement arriéré
    |--------------------------------------------------------------------------
    */

    $this->paiementArriere = true;

    /*
    |--------------------------------------------------------------------------
    | 13. Masquer les frais de l'année actuelle
    |--------------------------------------------------------------------------
    */

    $this->fraisDisponibles = [];

    $this->montants = [];

    $this->resetTotaux();
}
    // ============================================================
    // TOTAL À PAYER EN ARRIÉRÉ
    // ============================================================

    public function getTotalArriereASaisirProperty()
    {
        return collect(
            $this->montantsArrieres
        )->sum(
            fn($montant) =>
                (float) $montant
        );
    }

    // ============================================================
    // ENREGISTRER PAIEMENT ARRIÉRÉ
    // ============================================================

    public function enregistrerPaiementArriere(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Vérification
        |--------------------------------------------------------------------------
        */

        if (!$this->paiementArriere) {

            return;
        }

        if (empty($this->fraisArrieres)) {

            $this->addError(
                'frais',
                'Aucun arriéré à payer.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $this->validate([
            'modePaiement' =>
                'required|string',

            'datePaiement' =>
                'required|date',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Vérifier les montants AVANT transaction
        |--------------------------------------------------------------------------
        */

        $montantsAEnregistrer = [];

        foreach (
            $this->fraisArrieres
            as $frais
        ) {

            $inscriptionFraisId =
                $frais['id'];

            $montant =
                (float) (
                    $this->montantsArrieres[
                        $inscriptionFraisId
                    ] ?? 0
                );

            $resteAffiche =
                (float) $frais['reste'];

            /*
            |--------------------------------------------------------------------------
            | Ignorer les montants vides
            |--------------------------------------------------------------------------
            */

            if ($montant <= 0) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Ne pas dépasser le reste affiché
            |--------------------------------------------------------------------------
            */

            if ($montant > $resteAffiche) {

                $this->addError(
                    'frais',
                    "Le montant pour « {$frais['nom']} » dépasse le reste dû ("
                    .
                    number_format(
                        $resteAffiche,
                        0,
                        ',',
                        ' '
                    )
                    .
                    " FCFA)."
                );

                return;
            }

            $montantsAEnregistrer[
                $inscriptionFraisId
            ] = $montant;
        }

        /*
        |--------------------------------------------------------------------------
        | Aucun montant
        |--------------------------------------------------------------------------
        */

        if (empty($montantsAEnregistrer)) {

            $this->addError(
                'frais',
                'Aucun montant d’arriéré n’a été saisi.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Numéro de reçu
        |--------------------------------------------------------------------------
        */

        $numeroLot =
            'ARR-'
            .
            strtoupper(
                Str::random(8)
            )
            .
            '-'
            .
            now()->format('Ymd');

        $totalVerse = 0;

        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        */

        try {

            DB::transaction(function () use (
                $montantsAEnregistrer,
                $numeroLot,
                &$totalVerse
            ) {

                foreach (
                    $montantsAEnregistrer
                    as $inscriptionFraisId => $montant
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Recharger le pivot directement en base
                    |--------------------------------------------------------------------------
                    */

                    $inscriptionFrais =
                        DB::table(
                            'inscription_frais'
                        )
                        ->where(
                            'id',
                            $inscriptionFraisId
                        )
                        ->lockForUpdate()
                        ->first();

                    if (!$inscriptionFrais) {

                        throw new \RuntimeException(
                            "Le frais d'arriéré #{$inscriptionFraisId} est introuvable."
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Vérifier le reste réel
                    |--------------------------------------------------------------------------
                    */

                    $resteActuel =
                        (float) $inscriptionFrais->reste;

                    if ($resteActuel <= 0) {

                        throw new \RuntimeException(
                            'Un des frais sélectionnés est déjà soldé.'
                        );
                    }

                    if ($montant > $resteActuel) {

                        throw new \RuntimeException(
                            'Le montant saisi dépasse le reste réellement dû.'
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Nouveau montant payé
                    |--------------------------------------------------------------------------
                    */

                    $nouveauMontantPaye =
                        (float) $inscriptionFrais->montant_paye
                        +
                        $montant;

                    /*
                    |--------------------------------------------------------------------------
                    | Nouveau reste
                    |--------------------------------------------------------------------------
                    */

                    $nouveauReste =
                        max(
                            0,
                            $resteActuel - $montant
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | Créer le paiement
                    |--------------------------------------------------------------------------
                    */

                    $paiement = Paiement::create([

                        'inscription_id' =>
                            $inscriptionFrais->inscription_id,

                        'frais_id' =>
                            $inscriptionFrais->frais_id,

                        'montant_verse' =>
                            $montant,

                        'mode_paiement' =>
                            $this->modePaiement,

                        'date_paiement' =>
                            $this->datePaiement,

                        'numero_recu' =>
                            $numeroLot,
                    ]);

                    // Utilisateur connecté ayant réellement enregistré l'arriéré.
                    $paiement->user_id = auth()->id();
                    $paiement->save();

                    /*
                    |--------------------------------------------------------------------------
                    | Mettre à jour inscription_frais
                    |--------------------------------------------------------------------------
                    |
                    | On ne modifie pas "statut" ici car la valeur exacte
                    | de ton enum PostgreSQL n'est pas connue avec certitude.
                    | Le champ "reste" suffit pour recalculer l'arriéré.
                    |--------------------------------------------------------------------------
                    */

                    DB::table(
                        'inscription_frais'
                    )
                        ->where(
                            'id',
                            $inscriptionFraisId
                        )
                        ->update([

                            'montant_paye' =>
                                $nouveauMontantPaye,

                            'reste' =>
                                $nouveauReste,
                        ]);

                    $totalVerse += $montant;
                }
            });

        } catch (\Throwable $e) {

            Log::error(
                'Erreur paiement arriéré',
                [
                    'numeroLot' =>
                        $numeroLot,

                    'message' =>
                        $e->getMessage(),

                    'trace' =>
                        $e->getTraceAsString(),
                ]
            );

            $this->addError(
                'frais',
                $e->getMessage()
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Sécurité
        |--------------------------------------------------------------------------
        */

        if ($totalVerse <= 0) {

            $this->addError(
                'frais',
                'Aucun paiement d’arriéré n’a été enregistré.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Retrouver l'élève
        |--------------------------------------------------------------------------
        */

        $inscriptionArriere =
            Inscription::find(
                $this->inscriptionArriereId
            );

        $eleveId =
            $inscriptionArriere?->eleve_id;

        /*
        |--------------------------------------------------------------------------
        | Recalcul immédiat de l'arriéré
        |--------------------------------------------------------------------------
        */

        $arriereRestant = 0;

        if ($eleveId) {

            $this->verifierArriere(
                (int) $eleveId
            );

            $arriereRestant =
                (float) (
                    $this->arrieres[$eleveId]
                    ?? 0
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Message
        |--------------------------------------------------------------------------
        */

        if ($arriereRestant <= 0) {

            $this->successMessage =
                '✅ Arriéré totalement soldé. Le paiement de l’année courante est maintenant débloqué.';

        } else {

            $this->successMessage =
                'Paiement de l’arriéré enregistré. Reste à payer : '
                .
                number_format(
                    $arriereRestant,
                    0,
                    ',',
                    ' '
                )
                .
                ' FCFA.';
        }

        $this->numeroLotGenere =
            $numeroLot;

        /*
        |--------------------------------------------------------------------------
        | IMPRESSION
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
                'Erreur impression reçu arriéré',
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
        | ÉVÉNEMENTS
        |--------------------------------------------------------------------------
        */

        $this->dispatch(
            'paiement-enregistre'
        );

        /*
        |--------------------------------------------------------------------------
        | ARRIÉRÉ ENCORE PRÉSENT
        |--------------------------------------------------------------------------
        |
        | Si paiement partiel :
        | on recharge la liste des arriérés.
        |
        */

        if ($arriereRestant > 0 && $eleveId) {

            $this->rechargerArrieres(
                (int) $eleveId
            );

            $this->paiementArriere = true;

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | ARRIÉRÉ TOTALEMENT SOLDÉ
        |--------------------------------------------------------------------------
        */

        $this->paiementArriere = false;

        $this->fraisArrieres = [];

        $this->montantsArrieres = [];

        $this->inscriptionArriereId = null;

        $this->totalArriere = 0;

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT :
        | recharger automatiquement les frais 2026-2027
        |--------------------------------------------------------------------------
        */

        if ($this->inscriptionId) {

            $this->chargerFraisInscription(
                (int) $this->inscriptionId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Événement succès
        |--------------------------------------------------------------------------
        */

        $this->dispatch(
            'masquerSucces'
        );
    }

    // ============================================================
    // RECHARGER LES ARRIÉRÉS APRÈS PAIEMENT PARTIEL
    // ============================================================

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

        $this->fraisArrieres =
            $details
                ->map(function ($frais) {

                    return [

                        'id' =>
                            $frais->id,

                        'inscription_id' =>
                            $frais->inscription_id,

                        'frais_id' =>
                            $frais->frais_id,

                        'nom' =>
                            $frais->frais_nom
                            ?? 'Frais',

                        'montant_frais' =>
                            (float) $frais->montant_frais,

                        'montant_paye' =>
                            (float) $frais->montant_paye,

                        'reste' =>
                            (float) $frais->reste,
                    ];
                })
                ->values()
                ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Réinitialiser les montants avec les nouveaux restes
        |--------------------------------------------------------------------------
        */

        $this->montantsArrieres = [];

        foreach (
            $this->fraisArrieres
            as $frais
        ) {

            $this->montantsArrieres[
                $frais['id']
            ] = $frais['reste'];
        }

        /*
        |--------------------------------------------------------------------------
        | Nouveau total
        |--------------------------------------------------------------------------
        */

        $this->totalArriere =
            collect(
                $this->fraisArrieres
            )->sum(
                fn($frais) =>
                    (float) $frais['reste']
            );

        /*
        |--------------------------------------------------------------------------
        | Inscription précédente
        |--------------------------------------------------------------------------
        */

        $this->inscriptionArriereId =
            $this->fraisArrieres[0]['inscription_id']
            ?? null;
    }

    // ============================================================
    // FERMER L'ÉCRAN DES ARRIÉRÉS
    // ============================================================

    public function fermerPaiementArriere(): void
    {
        $this->paiementArriere = false;

        $this->fraisArrieres = [];

        $this->montantsArrieres = [];

        $this->inscriptionArriereId = null;

        $this->totalArriere = 0;
    }

    // ============================================================
    // IMPRESSION DU REÇU
    // ============================================================

    public function imprimerRecu(?string $numeroLot = null): void
    {
        // Appel possible depuis Blade avec wire:click="imprimerRecu"
        // ou depuis le code après l'enregistrement avec un numéro précis.
        $numeroLot = $numeroLot ?: $this->numeroLotGenere;

        if (!$numeroLot) {
            throw new \RuntimeException(
                'Aucun numéro de reçu disponible pour l’impression.'
            );
        }
        /*
        |--------------------------------------------------------------------------
        | Récupérer les paiements de ce reçu
        |--------------------------------------------------------------------------
        */

        $paiements = Paiement::with([
            'inscription.eleve',
            'inscription.classe',
            'inscription.annee',
            'frais',
            'user',
        ])
            ->where(
                'numero_recu',
                $numeroLot
            )
            ->get();

        if ($paiements->isEmpty()) {

            throw new \RuntimeException(
                "Aucun paiement trouvé pour {$numeroLot}"
            );
        }

        $paiement =
            $paiements->first();

        $inscription =
            $paiement->inscription;

        if (!$inscription) {

            throw new \RuntimeException(
                'Inscription introuvable.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Frais du reçu
        |--------------------------------------------------------------------------
        */

        $fraisIds =
            $paiements
                ->pluck('frais_id')
                ->filter()
                ->unique()
                ->values();

        if ($fraisIds->isEmpty()) {

            throw new \RuntimeException(
                'Aucun frais associé à ce reçu.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Détails
        |--------------------------------------------------------------------------
        */

        $details =
            $paiements
                ->map(function ($paiement) {

                    return [

                        'nom' =>
                            $paiement->frais->nom
                            ?? $paiement->frais->description
                            ?? 'Frais',

                        'montant' =>
                            (float) (
                                $paiement->montant_verse
                                ?? 0
                            ),
                    ];
                })
                ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Total de ce reçu
        |--------------------------------------------------------------------------
        */

        $totalRecu =
            (float) $paiements->sum(
                'montant_verse'
            );

        /*
        |--------------------------------------------------------------------------
        | Frais sélectionnés
        |--------------------------------------------------------------------------
        */

        $fraisSelectionnes =
            $inscription->frais
                ->filter(
                    fn($frais) =>
                        $fraisIds->contains(
                            $frais->id
                        )
                );

        /*
        |--------------------------------------------------------------------------
        | Total des frais
        |--------------------------------------------------------------------------
        */

        $totalFrais =
            (float) $fraisSelectionnes->sum(
                fn($frais) =>
                    (float) (
                        $frais->pivot->montant_frais
                        ?? 0
                    )
            );

        /*
        |--------------------------------------------------------------------------
        | Total payé sur ces frais
        |--------------------------------------------------------------------------
        */

        $totalPaye =
            (float) Paiement::where(
                'inscription_id',
                $inscription->id
            )
                ->whereIn(
                    'frais_id',
                    $fraisIds->toArray()
                )
                ->sum(
                    'montant_verse'
                );

        /*
        |--------------------------------------------------------------------------
        | Reste
        |--------------------------------------------------------------------------
        */

        $reste =
            max(
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
        | Utilisateur ayant enregistré le paiement
        |--------------------------------------------------------------------------
        */

        $utilisateur = $paiement->user;

        $nomUtilisateur = $utilisateur
            ? trim(
                ($utilisateur->prenom ?? '')
                . ' '
                . ($utilisateur->nom ?? '')
            )
            : 'Utilisateur inconnu';

        if ($nomUtilisateur === '') {
            $nomUtilisateur = $utilisateur->email
                ?? 'Utilisateur inconnu';
        }

        /*
        |--------------------------------------------------------------------------
        | Génération ticket
        |--------------------------------------------------------------------------
        */

        $serviceRecu =
            new RecuThermiqueService();

        $ticket =
            $serviceRecu->generate([

                'numero' =>
                    $numeroLot,
                'qr_data' =>
                      $numeroLot,

                'date' =>
                    $paiement->date_paiement
                        ? \Carbon\Carbon::parse(
                            $paiement->date_paiement
                        )->format('d/m/Y')
                        : now()->format('d/m/Y'),

                'eleve' =>
                    $inscription->eleve
                        ? $inscription->eleve->nom
                        . ' '
                        .
                        $inscription->eleve->prenom
                        : '-',

                'matricule' =>
                    $inscription->eleve->matricule
                    ?? '-',

                'classe' =>
                    $inscription->classe->nom
                    ?? '-',

                'annee' =>
                    $inscription->annee->nom
                    ?? '-',

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
                    $paiement->mode_paiement
                    ?? '-',

                // Nom de l'utilisateur ayant enregistré le paiement.
                'utilisateur' =>
                    $nomUtilisateur,

                // Alias utile si le service thermique attend un autre libellé.
                'enregistre_par' =>
                    $nomUtilisateur,
            ]);

        /*
        |--------------------------------------------------------------------------
        | Impression
        |--------------------------------------------------------------------------
        */

        $printer =
            new ThermalPrinterService(
                'Xprinter_POS58'
            );

        $result =
            $printer->print(
                $ticket
            );

        /*
        |--------------------------------------------------------------------------
        | Log
        |--------------------------------------------------------------------------
        */

        Log::info(
            'Reçu imprimé automatiquement',
            [

                'numero_recu' =>
                    $numeroLot,

                'imprimante' =>
                    'Xprinter_POS58',

                'frais_ids' =>
                    $fraisIds->toArray(),

                'total_recu' =>
                    $totalRecu,

                'total_frais_selectionnes' =>
                    $totalFrais,

                'total_paye_frais_selectionnes' =>
                    $totalPaye,

                'reste_frais_selectionnes' =>
                    $reste,

                'utilisateur_id' =>
                    $paiement->user_id,

                'utilisateur' =>
                    $nomUtilisateur,

                'resultat' =>
                    $result,
            ]
        );
    }

    // ============================================================
    // MESSAGE ARRIÉRÉS
    // ============================================================

    public function messageArrieres(int $eleveId): ?string
    {
        /*
        |--------------------------------------------------------------------------
        | Vérifier l'année précédente
        |--------------------------------------------------------------------------
        */

        if (empty($this->anneePrecedenteId)) {
            return null;
        }

        $annee =
            Annee::find(
                $this->anneePrecedenteId
            );

        if (!$annee) {
            return null;
        }

        $anneeLibelle =
            $annee->nom;

        /*
        |--------------------------------------------------------------------------
        | Service
        |--------------------------------------------------------------------------
        */

        $service =
            app(ArriereFraisService::class);

        $types = [];

        /*
        |--------------------------------------------------------------------------
        | SCOLARITÉ / FRAIS PRINCIPAUX
        |--------------------------------------------------------------------------
        */

        $arriereScolarite =
            (float) $service->montantArriere(
                $eleveId,
                $annee,
                self::CATEGORIES_ARRIERE
            );

        if ($arriereScolarite > 0) {

            $types[] =
                'scolarité';
        }

        /*
        |--------------------------------------------------------------------------
        | TD
        |--------------------------------------------------------------------------
        */

        $arriereTd = 0;

        if (
            method_exists(
                $service,
                'montantArriereTd'
            )
        ) {

            $arriereTd =
                (float) $service->montantArriereTd(
                    $eleveId,
                    $annee
                );
        }

        if ($arriereTd > 0) {

            $types[] =
                'frais de TD';
        }

        /*
        |--------------------------------------------------------------------------
        | CANTINE
        |--------------------------------------------------------------------------
        */

        $arriereCantine = 0;

        if (
            method_exists(
                $service,
                'montantArriereCantine'
            )
        ) {

            $arriereCantine =
                (float) $service->montantArriereCantine(
                    $eleveId,
                    $annee
                );
        }

        if ($arriereCantine > 0) {

            $types[] =
                'cantine';
        }

        /*
        |--------------------------------------------------------------------------
        | TRANSPORT
        |--------------------------------------------------------------------------
        */

        $arriereTransport = 0;

        if (
            method_exists(
                $service,
                'montantArriereTransport'
            )
        ) {

            $arriereTransport =
                (float) $service->montantArriereTransport(
                    $eleveId,
                    $annee
                );
        }

        if ($arriereTransport > 0) {

            $types[] =
                'transport';
        }

        /*
        |--------------------------------------------------------------------------
        | Aucun type
        |--------------------------------------------------------------------------
        */

        if (empty($types)) {

            return
                "Cet élève possède un arriéré pour l'année {$anneeLibelle}.";
        }

        /*
        |--------------------------------------------------------------------------
        | Un seul type
        |--------------------------------------------------------------------------
        */

        if (count($types) === 1) {

            return
                "Cet élève possède un arriéré de {$types[0]} pour l'année {$anneeLibelle}.";
        }

        /*
        |--------------------------------------------------------------------------
        | Plusieurs types
        |--------------------------------------------------------------------------
        */

        $dernier =
            array_pop($types);

        return
            "Cet élève possède des arriérés de "
            .
            implode(
                ', ',
                $types
            )
            .
            " et de {$dernier} pour l'année {$anneeLibelle}.";
    }

    // ============================================================
    // RENDER
    // ============================================================

    public function render()
    {
        return view(
            'livewire.paiements.paiement-multiple',
            [
                'annees' =>
                    Annee::all(),

                'cycles' =>
                    Cycle::all(),
            ]
        );
    }
}