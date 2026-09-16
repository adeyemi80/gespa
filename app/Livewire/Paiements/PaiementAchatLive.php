<?php

namespace App\Livewire\Paiements;

use App\Models\Annee;
use App\Models\Classe;
use App\Models\Cycle;
use App\Models\Frais;
use App\Models\Inscription;
use App\Models\PaiementAchat;
use App\Models\Recette;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use App\Services\RecuThermiqueService;
use App\Services\ThermalPrinterService;

class PaiementAchatLive extends Component
{
    /*
    |--------------------------------------------------------------------------
    | PROPRIÉTÉS TYPÉES
    |--------------------------------------------------------------------------
    */

    // Années / Cycle / Classe
    public ?int $anneeId = null;
    public ?int $cycleId = null;
    public ?int $classeId = null;

    public array $annees = [];
    public array $cycles = [];
    public array $classes = [];

    // Type d'élève
    public ?string $typeEleve = null;

    // Ancien élève
    public array $inscriptions = [];
    public ?int $inscriptionId = null;

    // Nouvel élève
    public array $nouveauEleve = [
        'nom' => '',
        'prenom' => '',
        'date_naissance' => '',
        'lieu_naissance' => '',
        'sexe' => '',
        'telephone' => '',
        'adresse' => '',
    ];

    public ?int $nouvelEleveId = null;

    // Acheteur
    public string $nom = '';
    public string $prenom = '';
    public string $telephone = '';

    // Frais
    public array $fraisDisponibles = [];
    public array $achats = [];

    // Paiement
    public $montantPaye = '';
    public string $modePaiement = '';
    public string $datePaiement = '';

    // Totaux
    public float $totalFrais = 0;
    public float $totalDejaPaye = 0;
    public float $resteAvantPaiement = 0;
    public float $resteApresPaiement = 0;

    // Reçu
    public ?string $numeroRecuGenere = null;
    public string $successMessage = '';

    // Validation rules
    protected array $fraisRules = [
        'anneeId' => 'required|exists:annees,id',
        'cycleId' => 'required|exists:cycles,id',
        'classeId' => 'required|exists:classes,id',
        'typeEleve' => 'required|in:ancien,nouveau',
        'modePaiement' => 'required|string|max:50',
        'datePaiement' => 'required|date',
        'montantPaye' => 'required|numeric|min:0.01',
    ];

    protected array $nouveauEleveRules = [
        'nouveauEleve.nom' => 'required|string|max:255',
        'nouveauEleve.prenom' => 'required|string|max:255',
        'nouveauEleve.date_naissance' => 'nullable|date',
        'nouveauEleve.lieu_naissance' => 'nullable|string|max:255',
        'nouveauEleve.sexe' => 'nullable|string|max:20',
        'nouveauEleve.telephone' => 'nullable|string|max:30',
        'nouveauEleve.adresse' => 'nullable|string|max:255',
    ];


    /*
    |--------------------------------------------------------------------------
    | MOUNT
    |--------------------------------------------------------------------------
    */

        public function mount(): void
{
    $this->datePaiement = now()->format('Y-m-d');

    $this->annees = Annee::orderByDesc('id')
        ->get()
        ->toArray();

    $this->cycles = Cycle::orderBy('nom')
        ->get()
        ->toArray();

    $annee = Annee::where('en_cours', true)->first();

    if ($annee) {
        $this->anneeId = $annee->id;
    }

    $this->chargerClasses();
}

    /*
    |--------------------------------------------------------------------------
    | ANNÉE
    |--------------------------------------------------------------------------
    */

    public function updatedAnneeId(?int $value): void
    {
        if (!$value) {
            $this->resetApresSelection();
            return;
        }

        // Valider que l'année existe
        if (!Annee::find($value)) {
            $this->addError('anneeId', 'Année invalide.');
            $this->anneeId = null;
            return;
        }

        $this->resetApresSelection();
        $this->cycleId = null;
        $this->classeId = null;
        $this->classes = [];
        $this->inscriptions = [];
    }


    /*
    |--------------------------------------------------------------------------
    | CYCLE
    |--------------------------------------------------------------------------
    */

    public function updatedCycleId(?int $value): void
    {
        if (!$value || !$this->anneeId) {
            $this->resetApresSelection();
            return;
        }

        // Valider que le cycle existe
        if (!Cycle::find($value)) {
            $this->addError('cycleId', 'Cycle invalide.');
            $this->cycleId = null;
            return;
        }

        $this->resetApresSelection();
        $this->classeId = null;
        $this->chargerClasses();
    }


    /*
    |--------------------------------------------------------------------------
    | CHARGER LES CLASSES
    |--------------------------------------------------------------------------
    */

private function chargerClasses()
{
    if (!$this->cycleId || !$this->anneeId) {
        $this->classes = [];
        return;
    }

    $this->classes = Classe::query()
        ->where('cycle_id', $this->cycleId)
        ->whereHas('annees', function ($query) {
            $query->where('annees.id', $this->anneeId);
        })
        ->orderBy('nom')
        ->get()
        ->map(function ($classe) {
            return [
                'id' => $classe->id,
                'nom' => $classe->nom,
                'niveau' => $classe->niveau,
                'cycle_id' => $classe->cycle_id,
            ];
        })
        ->values()
        ->toArray();
}



    /*
    |--------------------------------------------------------------------------
    | CLASSE
    |--------------------------------------------------------------------------
    */

    public function updatedClasseId(?int $value): void
    {
        if (!$value || !$this->anneeId) {
            $this->resetApresSelection();
            return;
        }

        // Valider que la classe existe et appartient au cycle/année
        $classe = Classe::query()
            ->where('id', $value)
            ->where('cycle_id', $this->cycleId)
            ->whereHas('annees', fn($q) => $q->where('annees.id', $this->anneeId))
            ->first();

        if (!$classe) {
            $this->addError('classeId', 'Classe invalide pour cette année.');
            $this->classeId = null;
            return;
        }

        $this->inscriptionId = null;
        $this->inscriptions = [];
        $this->viderFrais();

        if ($this->typeEleve === 'ancien') {
            $this->chargerInscriptions();
        } elseif ($this->typeEleve === 'nouveau') {
            $this->chargerFrais();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | TYPE D'ÉLÈVE
    |--------------------------------------------------------------------------
    */

   public function updatedTypeEleve($value): void
{
    /*
    |--------------------------------------------------------------------------
    | On conserve :
    | - l'année sélectionnée
    | - le cycle sélectionné
    | - la classe sélectionnée
    |
    | Le choix "ancien" ou "nouveau" ne doit donc jamais remettre
    | $this->anneeId, $this->cycleId ou $this->classeId à null.
    |--------------------------------------------------------------------------
    */

    // Réinitialiser l'élève actuellement sélectionné
    $this->inscriptionId = null;
    $this->nouvelEleveId = null;

    // Réinitialiser les informations de l'élève
    $this->nom = '';
    $this->prenom = '';
    $this->telephone = '';

    // Réinitialiser les frais et les montants
    $this->viderFrais();
    $this->resetTotaux();

    /*
    |--------------------------------------------------------------------------
    | NOUVEL ÉLÈVE
    |--------------------------------------------------------------------------
    |
    | Le formulaire "Nouveau élève" s'affichera automatiquement dans le Blade
    | puisque $this->typeEleve vaut "nouveau".
    |
    | On conserve $this->classeId afin que la classe précédemment sélectionnée
    | soit automatiquement affichée dans le formulaire.
    |
    */
    if ($value === 'nouveau') {

        // Aucun ancien élève à sélectionner
        $this->inscriptions = [];

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | ANCIEN ÉLÈVE
    |--------------------------------------------------------------------------
    |
    | On recharge les élèves inscrits dans :
    | - l'année sélectionnée
    | - la classe sélectionnée
    |
    */
    if ($value === 'ancien') {

        $this->chargerInscriptions();

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Aucun type sélectionné
    |--------------------------------------------------------------------------
    */

    $this->inscriptions = [];
}

    /*
    |--------------------------------------------------------------------------
    | CHARGER LES INSCRIPTIONS
    |--------------------------------------------------------------------------
    */

    private function chargerInscriptions(): void
    {
        if (!$this->anneeId || !$this->classeId) {
            return;
        }

        $this->inscriptions = Inscription::query()
            ->with('eleve')
            ->where('classe_id', $this->classeId)
            ->where('annee_id', $this->anneeId)
            ->orderBy('eleve_id')
            ->get()
            ->map(fn($inscription) => [
                'id' => $inscription->id,
                'eleve_id' => $inscription->eleve_id,
                'nom' => trim(($inscription->eleve?->nom ?? '') . ' ' . ($inscription->eleve?->prenom ?? '')),
                'matricule' => $inscription->eleve?->matricule ?? '',
            ])
            ->values()
            ->toArray();
    }


    /*
    |--------------------------------------------------------------------------
    | SÉLECTION ANCIEN ÉLÈVE
    |--------------------------------------------------------------------------
    */

    public function updatedInscriptionId(?int $value): void
    {
        $this->viderFrais();

        if (!$value) {
            return;
        }

        $inscription = Inscription::query()
            ->with('eleve')
            ->where('id', $value)
            ->where('annee_id', $this->anneeId)
            ->where('classe_id', $this->classeId)
            ->first();

        if (!$inscription?->eleve) {
            $this->addError('inscriptionId', 'Inscription introuvable.');
            $this->inscriptionId = null;
            return;
        }

        $this->nom = $inscription->eleve->nom ?? '';
        $this->prenom = $inscription->eleve->prenom ?? '';
        $this->telephone = $inscription->eleve->telephone ?? '';

        $this->chargerFrais();
    }


    /*
    |--------------------------------------------------------------------------
    | ENREGISTRER NOUVEL ÉLÈVE
    |--------------------------------------------------------------------------
    */

    public function enregistrerNouvelEleve(): void
    {
        $this->resetErrorBag();

        if (!$this->anneeId) {
            $this->addError('nouveauEleve.nom', 'Veuillez sélectionner une année.');
            return;
        }

        if (!$this->classeId) {
            $this->addError('nouveauEleve.nom', 'Veuillez sélectionner une classe.');
            return;
        }

        $this->validate($this->nouveauEleveRules);

        try {
            DB::transaction(function (): void {
                $eleve = \App\Models\Eleve::create([
                    'nom' => trim($this->nouveauEleve['nom']),
                    'prenom' => trim($this->nouveauEleve['prenom']),
                    'date_naissance' => $this->nouveauEleve['date_naissance'] ?: null,
                    'lieu_naissance' => $this->nouveauEleve['lieu_naissance'] ?: null,
                    'sexe' => $this->nouveauEleve['sexe'] ?: null,
                    'telephone' => $this->nouveauEleve['telephone'] ?: null,
                    'adresse' => $this->nouveauEleve['adresse'] ?: null,
                     'nationalite' => 'Béninoise',
                    ]);

                $inscription = Inscription::create([
                    'eleve_id' => $eleve->id,
                    'classe_id' => $this->classeId,
                    'annee_id' => $this->anneeId,
                    'date_inscription' => $this->datePaiement,
                    'decision' => null,
                ]);

                $this->nouvelEleveId = $eleve->id;
                $this->inscriptionId = $inscription->id;
                $this->nom = $eleve->nom;
                $this->prenom = $eleve->prenom;
                $this->telephone = $eleve->telephone ?? '';
            });

            $this->typeEleve = 'nouveau';
            $this->chargerFrais();
            session()->flash('success', 'Nouvel élève enregistré et inscrit.');
        } catch (\Throwable $e) {
            Log::error('Erreur création élève', ['message' => $e->getMessage()]);
            $this->addError('nouveauEleve.nom', 'Erreur lors de l\'enregistrement.');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CHARGER LES FRAIS
    |--------------------------------------------------------------------------
    */

    private function chargerFrais(): void
    {
        $this->fraisDisponibles = [];
        $this->achats = [];
        $this->resetTotaux();

        if (!$this->anneeId || !$this->classeId) {
            return;
        }

        $lignes = DB::table('annee_classe_frais')
            ->join('frais', 'frais.id', '=', 'annee_classe_frais.frais_id')
            ->leftJoin('categories_recettes', 'categories_recettes.id', '=', 'frais.categorie_recette_id')
            ->where('annee_classe_frais.annee_id', $this->anneeId)
            ->where('annee_classe_frais.classe_id', $this->classeId)
            ->select([
                'frais.id as frais_id',
                'frais.nom',
                'frais.description',
                'frais.categorie_recette_id',
                'categories_recettes.nom as categorie',
                'categories_recettes.code as categorie_code',
                'annee_classe_frais.montant',
            ])
            ->orderBy('frais.nom')
            ->get();

        foreach ($lignes as $ligne) {
            $dejaPaye = 0;
            $reste = (float) $ligne->montant;

            if ($this->inscriptionId) {
                $inscriptionFrais = DB::table('inscription_frais')
                    ->where('inscription_id', $this->inscriptionId)
                    ->where('frais_id', $ligne->frais_id)
                    ->where('annee_id', $this->anneeId)
                    ->first();

                if ($inscriptionFrais) {
                    $dejaPaye = (float) ($inscriptionFrais->montant_paye ?? 0);
                    $reste = max(0, (float) ($inscriptionFrais->reste ?? ($ligne->montant - $dejaPaye)));
                } else {
                    $dejaPaye = (float) PaiementAchat::query()
                        ->where('inscription_id', $this->inscriptionId)
                        ->where('frais_id', $ligne->frais_id)
                        ->sum('montant');

                    $reste = max(0, (float) $ligne->montant - $dejaPaye);
                }
            }

            // Ne pas afficher un frais totalement soldé
            if ($reste <= 0) {
                continue;
            }

            $this->fraisDisponibles[] = [
                'frais_id' => (int) $ligne->frais_id,
                'categorie_recette_id' => $ligne->categorie_recette_id,
                'categorie' => $ligne->categorie,
                'categorie_code' => $ligne->categorie_code,
                'nom' => $ligne->nom,
                'description' => $ligne->description,
                'montant' => (float) $ligne->montant,
                'deja_paye' => $dejaPaye,
                'reste' => $reste,
                'selectionne' => false,
            ];
        }

        // Pour un nouveau élève : initialiser les lignes inscription_frais
        if ($this->inscriptionId && $this->typeEleve === 'nouveau') {
            $this->initialiserInscriptionFrais();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | INITIALISER LES FRAIS
    |--------------------------------------------------------------------------
    */

    private function initialiserInscriptionFrais(): void
    {
        if (!$this->inscriptionId) {
            return;
        }

        foreach ($this->fraisDisponibles as $frais) {
            DB::table('inscription_frais')->updateOrInsert(
                [
                    'inscription_id' => $this->inscriptionId,
                    'frais_id' => $frais['frais_id'],
                    'annee_id' => $this->anneeId,
                ],
                [
                    'montant_frais' => $frais['montant'],
                    'montant_paye' => 0,
                    'reste' => $frais['montant'],
                    'statut' => 'non_payé',
                    'est_arriere' => false,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | TOGGLE FRAIS
    |--------------------------------------------------------------------------
    */

    public function toggleFrais(int $index): void
    {
        if (!isset($this->fraisDisponibles[$index])) {
            return;
        }

        $frais = $this->fraisDisponibles[$index];
        $fraisId = (int) $frais['frais_id'];

        // Chercher dans le panier
        $indexPanier = array_search(
            $fraisId,
            array_column($this->achats, 'frais_id'),
            true
        );

        if ($indexPanier !== false) {
            // Retirer du panier
            unset($this->achats[$indexPanier]);
            $this->achats = array_values($this->achats);
            $this->fraisDisponibles[$index]['selectionne'] = false;
        } else {
            // Ajouter au panier
            $this->achats[] = [
                'frais_id' => $frais['frais_id'],
                'categorie_recette_id' => $frais['categorie_recette_id'],
                'categorie' => $frais['categorie'],
                'categorie_code' => $frais['categorie_code'],
                'nom' => $frais['nom'],
                'description' => $frais['description'],
                'montant' => (float) $frais['montant'],
                'deja_paye' => (float) $frais['deja_paye'],
                'reste' => (float) $frais['reste'],
            ];
            $this->fraisDisponibles[$index]['selectionne'] = true;
        }

        $this->calculerTotaux();
    }


    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER ACHAT
    |--------------------------------------------------------------------------
    */

    public function supprimerAchat(int $index): void
    {
        if (!isset($this->achats[$index])) {
            return;
        }

        $fraisId = $this->achats[$index]['frais_id'];
        unset($this->achats[$index]);
        $this->achats = array_values($this->achats);

        // Déselectionner le frais
        foreach ($this->fraisDisponibles as $key => $frais) {
            if ((int) $frais['frais_id'] === $fraisId) {
                $this->fraisDisponibles[$key]['selectionne'] = false;
            }
        }

        $this->calculerTotaux();
    }


    /*
    |--------------------------------------------------------------------------
    | CALCULER TOTAUX
    |--------------------------------------------------------------------------
    */

    private function calculerTotaux(): void
    {
        $this->totalFrais = collect($this->achats)->sum(fn($a) => (float) ($a['reste'] ?? 0));
        $this->totalDejaPaye = collect($this->achats)->sum(fn($a) => (float) ($a['deja_paye'] ?? 0));
        $this->resteAvantPaiement = $this->totalFrais;
        $this->resteApresPaiement = max(0, $this->totalFrais - (float) ($this->montantPaye ?: 0));
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATED MONTANT PAYÉ
    |--------------------------------------------------------------------------
    */

    public function updatedMontantPaye(float|string|null $value): void
    {
        $this->calculerTotaux();
    }


    /*
    |--------------------------------------------------------------------------
    | RÉPARTIR PAIEMENT
    |--------------------------------------------------------------------------
    */

    private function repartirPaiement(float $montant): array
    {
        $resultat = [];
        $restePaiement = (float) $montant;

        foreach ($this->achats as $achat) {
            if ($restePaiement <= 0) {
                break;
            }

            $resteFrais = (float) ($achat['reste'] ?? 0);
            if ($resteFrais <= 0) {
                continue;
            }

            $versement = min($restePaiement, $resteFrais);

            $resultat[] = [
                'frais_id' => $achat['frais_id'],
                'categorie_recette_id' => $achat['categorie_recette_id'],
                'montant' => $versement,
            ];

            $restePaiement -= $versement;
        }

        return [
            'lignes' => $resultat,
            'non_reparti' => $restePaiement,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | ENREGISTRER PAIEMENT
    |--------------------------------------------------------------------------
    */

    public function enregistrer(): void
    {
        $this->resetErrorBag();

        // Validation générale
        $this->validate($this->fraisRules);

        // Validations métier
        if (!$this->validerContexte()) {
            return;
        }

        if (empty($this->achats)) {
            $this->addError('achats', 'Veuillez sélectionner au moins un frais.');
            return;
        }

        $this->calculerTotaux();
        $montant = (float) $this->montantPaye;

        if ($montant > $this->totalFrais) {
            $this->addError('montantPaye', 'Le montant dépasse le reste à payer.');
            return;
        }

        $repartition = $this->repartirPaiement($montant);

        if (empty($repartition['lignes']) || $repartition['non_reparti'] > 0.01) {
            $this->addError('montantPaye', 'Erreur lors de la répartition du paiement.');
            return;
        }

        $numeroRecu = 'ACH-' . now()->format('Ymd') . '-' . strtoupper(Str::random(8));

        try {
            $this->traiterPaiement($numeroRecu, $repartition);
            $this->numeroRecuGenere = $numeroRecu;
            $this->successMessage = 'Paiement enregistré avec succès.';

            try {
                $this->imprimerRecu($numeroRecu);
                $this->dispatch('impressionReussie', numeroRecu: $numeroRecu);
            } catch (\Throwable $e) {
                Log::error('Erreur impression', ['numero_recu' => $numeroRecu, 'message' => $e->getMessage()]);
                $this->dispatch('impressionEchouee', numeroRecu: $numeroRecu, message: $e->getMessage());
            }

            $this->dispatch('achat-enregistre', numeroRecu: $numeroRecu);
            $this->dispatch('ouvrirTicket', numeroRecu: $numeroRecu);

            $this->montantPaye = 0;
            $this->chargerFrais();
        } catch (\Throwable $e) {
            Log::error('Erreur paiement', ['message' => $e->getMessage()]);
            $this->addError('montantPaye', 'Erreur lors de l\'enregistrement.');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDER CONTEXTE
    |--------------------------------------------------------------------------
    */

    private function validerContexte(): bool
    {
        if ($this->typeEleve === 'ancien' && !$this->inscriptionId) {
            $this->addError('inscriptionId', 'Sélectionnez l\'élève.');
            return false;
        }

        if ($this->typeEleve === 'nouveau' && !$this->inscriptionId) {
            $this->addError('nouveauEleve.nom', 'Enregistrez le nouvel élève.');
            return false;
        }

        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | TRAITER PAIEMENT
    |--------------------------------------------------------------------------
    */

    private function traiterPaiement(string $numeroRecu, array $repartition): void
    {
        DB::transaction(function () use ($numeroRecu, $repartition): void {
            foreach ($repartition['lignes'] as $ligne) {
                $frais = Frais::find($ligne['frais_id']);

                if (!$frais) {
                    throw new \RuntimeException('Frais introuvable.');
                }

                $inscriptionFrais = DB::table('inscription_frais')
                    ->where('inscription_id', $this->inscriptionId)
                    ->where('frais_id', $ligne['frais_id'])
                    ->where('annee_id', $this->anneeId)
                    ->lockForUpdate()
                    ->first();

                if (!$inscriptionFrais) {
                    $montantFrais = collect($this->achats)
                        ->firstWhere('frais_id', $ligne['frais_id'])['montant'] ?? 0;

                    DB::table('inscription_frais')->insert([
                        'inscription_id' => $this->inscriptionId,
                        'frais_id' => $ligne['frais_id'],
                        'annee_id' => $this->anneeId,
                        'montant_frais' => $montantFrais,
                        'montant_paye' => 0,
                        'reste' => $montantFrais,
                        'statut' => 'non_payé',
                        'est_arriere' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $inscriptionFrais = DB::table('inscription_frais')
                        ->where('inscription_id', $this->inscriptionId)
                        ->where('frais_id', $ligne['frais_id'])
                        ->where('annee_id', $this->anneeId)
                        ->lockForUpdate()
                        ->first();
                }

                $resteActuel = (float) ($inscriptionFrais->reste ?? 0);
                $versement = (float) $ligne['montant'];

                if ($versement > $resteActuel) {
                    throw new \RuntimeException('Montant du paiement dépasse le reste.');
                }

                $nouveauPaye = (float) ($inscriptionFrais->montant_paye ?? 0) + $versement;
                $nouveauReste = max(0, $resteActuel - $versement);

                DB::table('inscription_frais')
                    ->where('id', $inscriptionFrais->id)
                    ->update([
                        'montant_paye' => $nouveauPaye,
                        'reste' => $nouveauReste,
                        'statut' => $nouveauReste <= 0 ? 'soldé' : 'non_payé',
                        'updated_at' => now(),
                    ]);

                $paiementAchat = PaiementAchat::create([
                    'numero_recu' => $numeroRecu,
                    'inscription_id' => $this->inscriptionId,
                    'nom' => trim($this->nom),
                    'prenom' => trim($this->prenom),
                    'telephone' => $this->telephone ? trim($this->telephone) : null,
                    'frais_id' => $ligne['frais_id'],
                    'categorie_recette_id' => $ligne['categorie_recette_id'],
                    'montant' => $versement,
                    'mode_paiement' => $this->modePaiement,
                    'date_paiement' => $this->datePaiement,
                ]);

                Recette::create([
                    'date_paiement' => $this->datePaiement,
                    'montant_verse' => $versement,
                    'paiement_id' => null,
                    'paiement_achat_id' => $paiementAchat->id,
                    'inscription_id' => $this->inscriptionId,
                    'mode_paiement' => $this->modePaiement,
                    'numero_recu' => $numeroRecu,
                    'categorie_recette_id' => $ligne['categorie_recette_id'],
                    'annee_id' => $this->anneeId,
                ]);
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | IMPRIMER REÇU
    |--------------------------------------------------------------------------
    */

    public function imprimerRecu(?string $numeroRecu = null): void
    {
        // Permet l'appel depuis Blade avec wire:click="imprimerRecu"
        // ou depuis enregistrer() avec imprimerRecu($numeroRecu).
        $numeroRecu = $numeroRecu ?: $this->numeroRecuGenere;

        if (!$numeroRecu) {
            throw new \RuntimeException('Aucun numéro de reçu disponible pour l\'impression.');
        }
        $paiements = PaiementAchat::query()
            ->with(['frais', 'categorieRecette', 'inscription.eleve', 'inscription.classe', 'inscription.annee'])
            ->where('numero_recu', $numeroRecu)
            ->orderBy('id')
            ->get();

        if ($paiements->isEmpty()) {
            throw new \RuntimeException("Paiement introuvable pour {$numeroRecu}.");
        }

        $premier = $paiements->first();
        $details = $paiements->map(fn($p) => [
            'nom' => ($p->categorieRecette?->nom ?? '') . ' - ' . ($p->frais?->nom ?? 'Frais'),
            'montant' => (float) $p->montant,
        ])->toArray();

        $totalRecu = (float) $paiements->sum('montant');
        $totalFraisSelectionnes = 0;
        $totalDejaPaye = 0;

        foreach ($paiements as $paiement) {
            $inscriptionFrais = DB::table('inscription_frais')
                ->where('inscription_id', $paiement->inscription_id)
                ->where('frais_id', $paiement->frais_id)
                ->where('annee_id', $this->anneeId)
                ->first();

            if ($inscriptionFrais) {
                $totalFraisSelectionnes += (float) $inscriptionFrais->montant_frais;
                $totalDejaPaye += (float) $inscriptionFrais->montant_paye;
            }
        }

        $fraisIds = $paiements->pluck('frais_id')->unique()->values();
        $totalPayeHistorique = PaiementAchat::query()
            ->where('inscription_id', $premier->inscription_id)
            ->whereIn('frais_id', $fraisIds)
            ->sum('montant');

        $reste = max(0, $totalFraisSelectionnes - $totalPayeHistorique);
        $statut = $reste <= 0 ? 'SOLDE' : ($totalPayeHistorique > 0 ? 'PARTIEL' : 'NON PAYE');

        $serviceRecu = new RecuThermiqueService();
        $ticket = $serviceRecu->generate([
            'numero' => $numeroRecu,
            'date' => $premier->date_paiement ? \Carbon\Carbon::parse($premier->date_paiement)->format('d/m/Y') : now()->format('d/m/Y'),
            'eleve' => trim($premier->nom . ' ' . $premier->prenom),
            'matricule' => $premier->inscription?->eleve?->matricule ?? '-',
            'classe' => $premier->inscription?->classe?->nom ?? '-',
            'annee' => $premier->inscription?->annee?->nom ?? '-',
            'details' => $details,
            'total_recu' => $totalRecu,
            'total_paye' => $totalPayeHistorique,
            'total_frais' => $totalFraisSelectionnes,
            'reste' => $reste,
            'statut' => $statut,
            'mode_paiement' => $premier->mode_paiement ?? '-',
        ]);

        $printer = new ThermalPrinterService('Xprinter_POS58');
        $printer->print($ticket);
    }


    /*
    |--------------------------------------------------------------------------
    | RESET
    |--------------------------------------------------------------------------
    */

    private function resetApresSelection(): void
    {
        $this->inscriptionId = null;
        $this->nouvelEleveId = null;
        $this->viderFrais();
        $this->nom = '';
        $this->prenom = '';
        $this->telephone = '';
    }

    private function viderFrais(): void
    {
        $this->fraisDisponibles = [];
        $this->achats = [];
        $this->resetTotaux();
    }

    private function resetTotaux(): void
    {
        $this->totalFrais = 0;
        $this->totalDejaPaye = 0;
        $this->resteAvantPaiement = 0;
        $this->resteApresPaiement = 0;
    }


    /*
    |--------------------------------------------------------------------------
    | RENDER
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view('livewire.paiements.paiement-achat-live');
    }
}