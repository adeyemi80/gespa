<?php

namespace App\Livewire\Paiements;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\CategorieRecette;
use App\Models\Frais;
use App\Models\Inscription;
use App\Models\PaiementAchat;
use App\Services\RecuThermiqueService;
use App\Services\ThermalPrinterService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

class PaiementAchatLive extends Component
{
    /*
    |--------------------------------------------------------------------------
    | ANNÉE / CLASSE
    |--------------------------------------------------------------------------
    */

    public $anneeId = null;
    public $cycleId = null;
    public $classeId = null;

    public $annees = [];
    public $cycles = [];
    public $classes = [];
    public $inscriptions = [];


    /*
    |--------------------------------------------------------------------------
    | ACHETEUR
    |--------------------------------------------------------------------------
    */

    public $inscriptionId = null;

    public $nom = '';
    public $prenom = '';
    public $telephone = '';


    /*
    |--------------------------------------------------------------------------
    | CATÉGORIES
    |--------------------------------------------------------------------------
    */

    public $categoriesRecettes = [];

    /*
     * Plusieurs catégories peuvent être sélectionnées
     */
    public $categoriesSelectionnees = [];


    /*
    |--------------------------------------------------------------------------
    | FRAIS / ARTICLES
    |--------------------------------------------------------------------------
    */

    public $fraisDisponibles = [];

    /*
     * Articles sélectionnés dans le panier
     */
    public $achats = [];


    /*
    |--------------------------------------------------------------------------
    | PAIEMENT
    |--------------------------------------------------------------------------
    */

    public $modePaiement = '';
    public $datePaiement = '';


    /*
    |--------------------------------------------------------------------------
    | TOTAL
    |--------------------------------------------------------------------------
    */

    public $total = 0;


    /*
    |--------------------------------------------------------------------------
    | MESSAGES
    |--------------------------------------------------------------------------
    */

    public $successMessage = '';
    public $numeroRecuGenere = null;


    /*
    |--------------------------------------------------------------------------
    | MOUNT
    |--------------------------------------------------------------------------
    */

    public function mount()
    {
        $this->datePaiement = now()->format('Y-m-d');

        /*
         * Années
         */
        $this->annees = Annee::orderByDesc('id')->get();
        /*
         * Cycles
         */
        $this->cycles = Cycle::orderBy('nom')->get();

        /*
         * Année en cours
         */
        $annee = Annee::where('en_cours', true)->first();

        if ($annee) {
            $this->anneeId = $annee->id;
        }

        /*
         * Catégories destinées aux achats
         */
        $this->chargerCategories();
    }


    /*
    |--------------------------------------------------------------------------
    | CATÉGORIES D'ACHAT
    |--------------------------------------------------------------------------
    */

    private function chargerCategories()
    {
        $this->categoriesRecettes = CategorieRecette::query()
            ->where('actif', true)
            ->where('est_achat', true)
            ->orderBy('nom')
            ->get()
            ->toArray();
    }


    /*
    |--------------------------------------------------------------------------
    | CHANGEMENT D'ANNÉE
    |--------------------------------------------------------------------------
    */

    public function updatedAnneeId($value)
    {
        $this->classeId = null;
        $this->inscriptionId = null;

        $this->classes = [];
        $this->inscriptions = [];

        $this->viderFraisEtPanier();

        if (!$value) {
            return;
        }

        if ($this->cycleId) {
            $this->chargerClasses();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CYCLE
    |--------------------------------------------------------------------------
    */

    public function updatedCycleId($value)
    {
        $this->classeId = null;
        $this->inscriptionId = null;

        $this->classes = [];
        $this->inscriptions = [];

        $this->viderFraisEtPanier();

        if (!$value) {
            return;
        }

        $this->chargerClasses();
    }


    /*
    |--------------------------------------------------------------------------
    | CHARGER LES CLASSES
    |--------------------------------------------------------------------------
    */

    private function chargerClasses()
    {
        if (!$this->cycleId) {
            return;
        }

        /*
         * Pas de scope orderByNiveau()
         * pour éviter une erreur si ce scope n'existe pas.
         */
        $this->classes = Classe::query()
            ->where('cycle_id', $this->cycleId)
            ->orderBy('nom')
            ->get()
            ->toArray();
    }


    /*
    |--------------------------------------------------------------------------
    | CLASSE
    |--------------------------------------------------------------------------
    */

    public function updatedClasseId($value)
    {
        $this->inscriptionId = null;

        $this->inscriptions = [];

        /*
         * On conserve les informations saisies manuellement
         * uniquement si l'utilisateur les a saisies.
         */

        $this->viderFraisEtPanier();

        if (!$value || !$this->anneeId) {
            return;
        }

        $this->chargerInscriptions();

        /*
         * Si des catégories étaient déjà sélectionnées,
         * on recharge les frais.
         */
        if (!empty($this->categoriesSelectionnees)) {
            $this->chargerFrais();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CHARGER LES ÉLÈVES INSCRITS
    |--------------------------------------------------------------------------
    */

    private function chargerInscriptions()
    {
        $this->inscriptions = Inscription::query()
            ->with('eleve')
            ->where('classe_id', $this->classeId)
            ->where('annee_id', $this->anneeId)
            ->get()
            ->sortBy(function ($inscription) {
                return mb_strtolower(
                    trim(
                        ($inscription->eleve->nom ?? '')
                        . ' '
                        . ($inscription->eleve->prenom ?? '')
                    )
                );
            })
            ->map(function ($inscription) {

                return [
                    'id' => $inscription->id,

                    'nom' => trim(
                        ($inscription->eleve->nom ?? '')
                        . ' '
                        . ($inscription->eleve->prenom ?? '')
                    ),
                ];
            })
            ->values()
            ->toArray();
    }


    /*
    |--------------------------------------------------------------------------
    | ÉLÈVE INSCRIT
    |--------------------------------------------------------------------------
    */

    public function updatedInscriptionId($value)
    {
        if (!$value) {
            return;
        }

        $inscription = Inscription::with('eleve')
            ->find($value);

        if (!$inscription || !$inscription->eleve) {
            return;
        }

        $this->nom =
            $inscription->eleve->nom ?? '';

        $this->prenom =
            $inscription->eleve->prenom ?? '';
    }


    /*
    |--------------------------------------------------------------------------
    | CATÉGORIES
    |--------------------------------------------------------------------------
    */

    public function updatedCategoriesSelectionnees()
    {
        $this->chargerFrais();
    }


    /*
    |--------------------------------------------------------------------------
    | CHARGER LES FRAIS DES CATÉGORIES
    |--------------------------------------------------------------------------
    */

   private function chargerFrais()
{
    $this->fraisDisponibles = [];

    if (empty($this->categoriesSelectionnees)) {
        return;
    }

    if (!$this->anneeId || !$this->classeId) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Récupération des frais appartenant aux catégories sélectionnées
    |--------------------------------------------------------------------------
    */

    $frais = Frais::query()
        ->with('categorieRecette')
        ->whereIn(
            'categorie_recette_id',
            $this->categoriesSelectionnees
        )
        ->orderBy('nom')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Pour chaque frais, rechercher le montant correspondant
    | à l'année + classe + frais
    |--------------------------------------------------------------------------
    */

    foreach ($frais as $fraisItem) {

        $pivot = DB::table('annee_classe_frais')
            ->where('annee_id', $this->anneeId)
            ->where('classe_id', $this->classeId)
            ->where('frais_id', $fraisItem->id)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Si aucune tarification n'existe pour cette combinaison,
        | on ne présente pas l'article comme achetable.
        |--------------------------------------------------------------------------
        */

        if (!$pivot) {
            continue;
        }

        $this->fraisDisponibles[] = [
            'frais_id' => $fraisItem->id,

            'categorie_recette_id' =>
                $fraisItem->categorie_recette_id,

            'categorie' =>
                $fraisItem->categorieRecette?->nom,

            'categorie_code' =>
                $fraisItem->categorieRecette?->code,

            'nom' =>
                $fraisItem->nom,

            'description' =>
                $fraisItem->description,

            'montant' =>
                (float) $pivot->montant,

            'selectionne' =>
                false,
        ];
    }
}

    private function articleDansPanier($fraisId)
    {
        foreach ($this->achats as $achat) {

            if (
                (int) $achat['frais_id']
                ===
                (int) $fraisId
            ) {
                return true;
            }
        }

        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | AJOUTER / RETIRER UN ARTICLE
    |--------------------------------------------------------------------------
    */

    public function toggleFrais($index)
    {
        if (
            !isset(
                $this->fraisDisponibles[$index]
            )
        ) {
            return;
        }

        $frais =
            $this->fraisDisponibles[$index];

        $fraisId =
            (int) $frais['frais_id'];

        /*
         * Chercher l'article dans le panier.
         */
        $indexPanier = null;

        foreach ($this->achats as $key => $achat) {

            if (
                (int) $achat['frais_id']
                ===
                $fraisId
            ) {
                $indexPanier = $key;
                break;
            }
        }

        /*
         * RETIRER
         */
        if ($indexPanier !== null) {

            unset(
                $this->achats[$indexPanier]
            );

            $this->achats =
                array_values($this->achats);

            $this->fraisDisponibles[$index]
                ['selectionne'] = false;

        } else {

            /*
             * AJOUTER
             */
            $this->achats[] = [

                'frais_id' =>
                    $frais['frais_id'],

                'categorie_recette_id' =>
                    $frais['categorie_recette_id'],

                'categorie' =>
                    $frais['categorie'],

                'categorie_code' =>
                    $frais['categorie_code'],

                'nom' =>
                    $frais['nom'],

                'description' =>
                    $frais['description'],

                'montant' =>
                    (float) $frais['montant'],
            ];

            $this->fraisDisponibles[$index]
                ['selectionne'] = true;
        }

        $this->calculerTotal();
    }


    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER UN ARTICLE DU PANIER
    |--------------------------------------------------------------------------
    */

    public function supprimerAchat($index)
    {
        if (!isset($this->achats[$index])) {
            return;
        }

        unset($this->achats[$index]);

        $this->achats =
            array_values($this->achats);

        /*
         * Recharger l'état des cases.
         */
        $this->chargerFrais();

        $this->calculerTotal();
    }


    /*
    |--------------------------------------------------------------------------
    | TOTAL
    |--------------------------------------------------------------------------
    */

    private function calculerTotal()
    {
        $this->total = collect($this->achats)
            ->sum(function ($achat) {

                return (float) (
                    $achat['montant'] ?? 0
                );
            });
    }


    /*
    |--------------------------------------------------------------------------
    | VIDER FRAIS ET PANIER
    |--------------------------------------------------------------------------
    */

    private function viderFraisEtPanier()
    {
        $this->fraisDisponibles = [];

        $this->achats = [];

        $this->total = 0;
    }


    /*
    |--------------------------------------------------------------------------
    | ENREGISTRER LE PAIEMENT
    |--------------------------------------------------------------------------
    */

    public function enregistrer()
    {
        $this->resetErrorBag();

        /*
         * Validation.
         */
        $this->validate([
            'nom' => [
                'required',
                'string',
                'max:255',
            ],

            'prenom' => [
                'required',
                'string',
                'max:255',
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'modePaiement' => [
                'required',
                'string',
                'max:50',
            ],

            'datePaiement' => [
                'required',
                'date',
            ],
        ]);


        /*
         * Vérifier le panier.
         */
        if (empty($this->achats)) {

            $this->addError(
                'achats',
                'Veuillez sélectionner au moins un article.'
            );

            return;
        }


        /*
         * Vérifier le montant.
         */
        $this->calculerTotal();

        if ($this->total <= 0) {

            $this->addError(
                'achats',
                'Le montant total doit être supérieur à zéro.'
            );

            return;
        }


        /*
         * Numéro unique du reçu.
         */
        $numeroRecu =
            'ACH-'
            . now()->format('Ymd')
            . '-'
            . strtoupper(
                Str::random(8)
            );


        /*
         * Vérification finale des articles
         * avant insertion.
         */
        foreach ($this->achats as $achat) {

            $fraisExiste =
                Frais::where(
                    'id',
                    $achat['frais_id']
                )
                ->where(
                    'categorie_recette_id',
                    $achat['categorie_recette_id']
                )
                ->exists();

            if (!$fraisExiste) {

                $this->addError(
                    'achats',
                    'Un des articles sélectionnés n\'existe plus.'
                );

                return;
            }
        }


        /*
         * TRANSACTION
         */
        DB::transaction(function () use (
            $numeroRecu
        ) {

            foreach ($this->achats as $achat) {

                PaiementAchat::create([

                    'numero_recu' =>
                        $numeroRecu,

                    /*
                     * NULL pour un non-inscrit.
                     */
                    'inscription_id' =>
                        $this->inscriptionId ?: null,

                    'nom' =>
                        trim($this->nom),

                    'prenom' =>
                        trim($this->prenom),

                    'telephone' =>
                        $this->telephone
                        ? trim($this->telephone)
                        : null,

                    'frais_id' =>
                        $achat['frais_id'],

                    'categorie_recette_id' =>
                        $achat['categorie_recette_id'],

                    'montant' =>
                        (float) $achat['montant'],

                    'mode_paiement' =>
                        $this->modePaiement,

                    'date_paiement' =>
                        $this->datePaiement,
                ]);
            }
        });


        /*
         * Mémoriser le reçu.
         */
        $this->numeroRecuGenere =
            $numeroRecu;

        $this->successMessage =
            'Paiement enregistré avec succès.';


        /*
         * IMPRESSION
         */
        try {

            $this->imprimerRecu(
                $numeroRecu
            );

            $this->dispatch(
                'impressionReussie',
                numeroRecu: $numeroRecu
            );

        } catch (\Throwable $e) {

            Log::error(
                'Erreur impression reçu achat',
                [
                    'numero_recu' =>
                        $numeroRecu,

                    'message' =>
                        $e->getMessage(),

                    'trace' =>
                        $e->getTraceAsString(),
                ]
            );

            /*
             * Le paiement reste enregistré.
             * Seule l'impression a échoué.
             */
            $this->dispatch(
                'impressionEchouee',
                numeroRecu: $numeroRecu,
                message: $e->getMessage()
            );
        }


        /*
         * Événements Livewire.
         */
        $this->dispatch(
            'achat-enregistre',
            numeroRecu: $numeroRecu
        );

        $this->dispatch(
            'ouvrirTicket',
            numeroRecu: $numeroRecu
        );


        /*
         * RESET DU FORMULAIRE
         */
        $this->achats = [];

        $this->fraisDisponibles = [];

        $this->categoriesSelectionnees = [];

        $this->inscriptionId = null;

        $this->nom = '';

        $this->prenom = '';

        $this->telephone = '';

        $this->modePaiement = '';

        $this->total = 0;

        /*
         * On conserve :
         * - année
         * - cycle
         * - classe
         */
    }


    /*
    |--------------------------------------------------------------------------
    | IMPRESSION
    |--------------------------------------------------------------------------
    */

    private function imprimerRecu(
        string $numeroRecu
    ): void {

        /*
         * Récupérer toutes les lignes
         * du même reçu.
         */
        $paiements =
            PaiementAchat::query()
                ->with([
                    'frais',
                    'categorieRecette',
                    'inscription.eleve',
                    'inscription.classe',
                    'inscription.annee',
                ])
                ->where(
                    'numero_recu',
                    $numeroRecu
                )
                ->orderBy('id')
                ->get();


        if ($paiements->isEmpty()) {

            throw new \RuntimeException(
                "Aucun paiement trouvé pour le reçu {$numeroRecu}."
            );
        }


        $premier =
            $paiements->first();


        /*
         * Construire les lignes du ticket.
         */
        $details =
            $paiements->map(
                function ($paiement) {

                    $categorie =
                        $paiement
                            ->categorieRecette
                            ?->nom;

                    $article =
                        $paiement
                            ->frais
                            ?->nom;

                    return [

                        'nom' =>
                            $categorie
                            ? (
                                $categorie
                                . ' - '
                                . ($article ?? 'Article')
                            )
                            : (
                                $article
                                ?? 'Article'
                            ),

                        'montant' =>
                            (float)
                            $paiement->montant,
                    ];
                }
            )
            ->toArray();


        /*
         * TOTAL.
         */
        $total =
            (float) $paiements
                ->sum('montant');


        /*
         * INSCRIPTION ÉVENTUELLE.
         */
        $inscription =
            $premier->inscription;


        /*
         * SERVICE REÇU
         */
        $serviceRecu =
            new RecuThermiqueService();


        /*
         * Génération du ticket.
         *
         * Cette structure reprend le format
         * utilisé par votre système de reçu.
         */
        $ticket =
            $serviceRecu->generate([

                'numero' =>
                    $numeroRecu,

                'date' =>
                    $premier->date_paiement
                    ? $premier
                        ->date_paiement
                        ->format('d/m/Y')
                    : now()->format('d/m/Y'),

                'eleve' =>
                    trim(
                        $premier->nom
                        . ' '
                        . $premier->prenom
                    ),

                'matricule' =>
                    $inscription?->eleve?->matricule
                    ?? '-',

                'classe' =>
                     Classe::find($this->classeId)?->nom
                     ?? $inscription?->classe?->nom
                     ?? '-',

                'annee' =>
                      Annee::find($this->anneeId)?->nom
                      ?? $inscription?->annee?->nom
                      ?? '-',

                'details' =>
                    $details,

                'total_recu' =>
                    $total,

                'total_paye' =>
                    $total,

                'total_frais' =>
                    $total,

                'reste' =>
                    0,

                'statut' =>
                    'PAYE',

                'mode_paiement' =>
                    $premier->mode_paiement
                    ?? '-',
            ]);


        /*
         * IMPRIMANTE
         */
        $printer =
            new ThermalPrinterService(
                'Xprinter_POS58'
            );


        /*
         * Impression.
         */
        $result =
            $printer->print($ticket);


        Log::info(
            'Reçu achat imprimé',
            [
                'numero_recu' =>
                    $numeroRecu,

                'imprimante' =>
                    'Xprinter_POS58',

                'resultat' =>
                    $result,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RENDER
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view(
            'livewire.paiements.paiement-achat-live'
        );
    }
}