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

    // Inscription d'un élève externe aux TD
    public array $inscriptionsTdExternes = [];
    public ?int $inscriptionTdExterneId = null;

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
    // Historique des anciens reçus
    public array $anciensRecus = [];

    // Validation rules
    protected array $fraisRules = [
        'anneeId' => 'required|exists:annees,id',
        'cycleId' => 'required|exists:cycles,id',
        'classeId' => 'required|exists:classes,id',
        'typeEleve' => 'required|in:ancien,nouveau,externe_td',
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
        } elseif ($this->typeEleve === 'externe_td') {
            $this->chargerInscriptionsTdExternes();
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
    $this->inscriptionTdExterneId = null;
    $this->inscriptionsTdExternes = [];
    $this->nouvelEleveId = null;
    $this->anciensRecus = [];

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

    if ($value === 'externe_td') {
        $this->inscriptions = [];
        if ($this->anneeId && $this->classeId) {
            $this->chargerInscriptionsTdExternes();
        }
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
    | INSCRIPTIONS DES ÉLÈVES EXTERNES TD
    |--------------------------------------------------------------------------
    */

    private function chargerInscriptionsTdExternes(): void
    {
        $this->inscriptionsTdExternes = [];
        $this->viderFrais();

        if (!$this->anneeId || !$this->classeId) {
            return;
        }

        $this->inscriptionsTdExternes = DB::table('inscriptions_td_externes as ite')
            ->join('eleves_externes_td as eet', 'eet.id', '=', 'ite.eleve_externe_td_id')
            ->where('ite.annee_id', $this->anneeId)
            ->where('ite.classe_id', $this->classeId)
            ->where('ite.statut', 'active')
            ->select([
                'ite.id',
                'ite.eleve_externe_td_id',
                'ite.frais_id',
                'ite.montant_total',
                'eet.matricule',
                'eet.nom',
                'eet.prenom',
                'eet.telephone',
            ])
            ->orderBy('eet.nom')
            ->orderBy('eet.prenom')
            ->get()
            ->map(fn ($row) => (array) $row)
            ->toArray();
    }

    public function updatedInscriptionTdExterneId(?int $value): void
    {
        $this->viderFrais();
        $this->anciensRecus = [];

        if (!$value) {
            $this->nom = '';
            $this->prenom = '';
            $this->telephone = '';
            return;
        }

        $inscription = DB::table('inscriptions_td_externes as ite')
            ->join('eleves_externes_td as eet', 'eet.id', '=', 'ite.eleve_externe_td_id')
            ->where('ite.id', $value)
            ->where('ite.annee_id', $this->anneeId)
            ->where('ite.classe_id', $this->classeId)
            ->where('ite.statut', 'active')
            ->select('ite.*', 'eet.nom', 'eet.prenom', 'eet.telephone', 'eet.matricule')
            ->first();

        if (!$inscription) {
            $this->addError('inscriptionTdExterneId', 'Inscription TD externe introuvable ou inactive.');
            $this->inscriptionTdExterneId = null;
            return;
        }

        $this->nom = $inscription->nom ?? '';
        $this->prenom = $inscription->prenom ?? '';
        $this->telephone = $inscription->telephone ?? '';
        $this->chargerFrais();
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
    $this->anciensRecus = [];

    if (!$value) {
        $this->nom = '';
        $this->prenom = '';
        $this->telephone = '';

        return;
    }

    $inscription = Inscription::query()
        ->with('eleve')
        ->where('id', $value)
        ->where('annee_id', $this->anneeId)
        ->where('classe_id', $this->classeId)
        ->first();

    if (!$inscription || !$inscription->eleve) {
        $this->addError('inscriptionId', 'Inscription introuvable.');

        $this->inscriptionId = null;

        return;
    }

    // Informations de l'élève sélectionné
    $this->nom = $inscription->eleve->nom ?? '';
    $this->prenom = $inscription->eleve->prenom ?? '';
    $this->telephone = $inscription->eleve->telephone ?? '';

    // Charger les frais de l'inscription actuelle
    $this->chargerFrais();

    // Charger tous les reçus de cet élève, toutes années confondues
    $this->chargerAnciensRecus();
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

        // Un externe TD ne paie que le frais rattaché à son inscription TD.
        if ($this->typeEleve === 'externe_td') {
            $this->chargerFraisTdExterne();
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


    private function chargerFraisTdExterne(): void
    {
        $this->fraisDisponibles = [];
        $this->achats = [];
        $this->resetTotaux();

        if (!$this->inscriptionTdExterneId || !$this->anneeId || !$this->classeId) {
            return;
        }

        $inscription = DB::table('inscriptions_td_externes as ite')
            ->join('frais as f', 'f.id', '=', 'ite.frais_id')
            ->join('annee_classe_frais as acf', function ($join) {
                $join->on('acf.frais_id', '=', 'ite.frais_id')
                    ->on('acf.annee_id', '=', 'ite.annee_id')
                    ->on('acf.classe_id', '=', 'ite.classe_id');
            })
            ->leftJoin('categories_recettes as cr', 'cr.id', '=', 'f.categorie_recette_id')
            ->where('ite.id', $this->inscriptionTdExterneId)
            ->where('ite.annee_id', $this->anneeId)
            ->where('ite.classe_id', $this->classeId)
            ->where('f.categorie_recette_id', 17)
            ->select([
                'ite.id as inscription_id',
                'ite.frais_id',
                'ite.montant_total',
                'f.nom',
                'f.description',
                'f.categorie_recette_id',
                'cr.nom as categorie',
                'cr.code as categorie_code',
                'acf.montant as tarif_applicable',
            ])
            ->first();

        if (!$inscription) {
            $this->addError('inscriptionTdExterneId', 'Aucun frais TD applicable n’est associé à cette inscription.');
            return;
        }

        $montantTotal = (float) ($inscription->montant_total ?? $inscription->tarif_applicable ?? 0);
        $dejaPaye = (float) DB::table('paiement_achats')
            ->where('inscription_td_externe_id', $this->inscriptionTdExterneId)
            ->where('frais_id', $inscription->frais_id)
            ->sum('montant');

        $reste = max(0, $montantTotal - $dejaPaye);

        if ($reste > 0) {
            $this->fraisDisponibles[] = [
                'frais_id' => (int) $inscription->frais_id,
                'categorie_recette_id' => (int) $inscription->categorie_recette_id,
                'categorie' => $inscription->categorie,
                'categorie_code' => $inscription->categorie_code,
                'nom' => $inscription->nom,
                'description' => $inscription->description,
                'montant' => $montantTotal,
                'deja_paye' => $dejaPaye,
                'reste' => $reste,
                'selectionne' => false,
            ];
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

        if ($this->typeEleve === 'externe_td') {
            if (!$this->inscriptionTdExterneId) {
                $this->addError('inscriptionTdExterneId', 'Sélectionnez l’inscription TD de l’élève externe.');
                return false;
            }

            $existe = DB::table('inscriptions_td_externes')
                ->where('id', $this->inscriptionTdExterneId)
                ->where('annee_id', $this->anneeId)
                ->where('classe_id', $this->classeId)
                ->where('statut', 'active')
                ->exists();

            if (!$existe) {
                $this->addError('inscriptionTdExterneId', 'L’inscription TD externe sélectionnée est invalide.');
                return false;
            }
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
        if ($this->typeEleve === 'externe_td') {
            $this->traiterPaiementTdExterne($numeroRecu, $repartition);
            return;
        }

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


    private function traiterPaiementTdExterne(string $numeroRecu, array $repartition): void
    {
        DB::transaction(function () use ($numeroRecu, $repartition): void {
            $inscription = DB::table('inscriptions_td_externes as ite')
                ->join('eleves_externes_td as eet', 'eet.id', '=', 'ite.eleve_externe_td_id')
                ->where('ite.id', $this->inscriptionTdExterneId)
                ->where('ite.annee_id', $this->anneeId)
                ->where('ite.classe_id', $this->classeId)
                ->where('ite.statut', 'active')
                ->select('ite.*', 'eet.nom', 'eet.prenom', 'eet.telephone')
                ->lockForUpdate()
                ->first();

            if (!$inscription) {
                throw new \RuntimeException('Inscription TD externe introuvable ou inactive.');
            }

            foreach ($repartition['lignes'] as $ligne) {
                if ((int) $ligne['frais_id'] !== (int) $inscription->frais_id
                    || (int) $ligne['categorie_recette_id'] !== 17) {
                    throw new \RuntimeException('Seuls les frais TD sont autorisés pour un élève externe.');
                }

                $tarif = (float) $inscription->montant_total;
                $dejaPaye = (float) DB::table('paiement_achats')
                    ->where('inscription_td_externe_id', $inscription->id)
                    ->where('frais_id', $ligne['frais_id'])
                    ->sum('montant');

                $versement = (float) $ligne['montant'];
                if ($versement <= 0 || $dejaPaye + $versement > $tarif + 0.001) {
                    throw new \RuntimeException('Le versement dépasse le reste dû pour les frais TD.');
                }

                $paiementAchat = PaiementAchat::create([
                    'numero_recu' => $numeroRecu,
                    'inscription_id' => null,
                    'inscription_td_externe_id' => $inscription->id,
                    'nom' => trim($inscription->nom),
                    'prenom' => trim($inscription->prenom),
                    'telephone' => $inscription->telephone ? trim($inscription->telephone) : null,
                    'frais_id' => $ligne['frais_id'],
                    'categorie_recette_id' => 17,
                    'montant' => $versement,
                    'mode_paiement' => $this->modePaiement,
                    'date_paiement' => $this->datePaiement,
                ]);

                Recette::create([
                    'date_paiement' => $this->datePaiement,
                    'montant_verse' => $versement,
                    'paiement_id' => null,
                    'paiement_achat_id' => $paiementAchat->id,
                    'inscription_id' => null,
                    'mode_paiement' => $this->modePaiement,
                    'numero_recu' => $numeroRecu,
                    'categorie_recette_id' => 17,
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

        if (!empty($premier->inscription_td_externe_id)) {
            $inscriptionTd = DB::table('inscriptions_td_externes as ite')
                ->join('eleves_externes_td as eet', 'eet.id', '=', 'ite.eleve_externe_td_id')
                ->join('classes as c', 'c.id', '=', 'ite.classe_id')
                ->join('annees as a', 'a.id', '=', 'ite.annee_id')
                ->where('ite.id', $premier->inscription_td_externe_id)
                ->select('ite.*', 'eet.matricule', 'eet.nom as eleve_nom', 'eet.prenom as eleve_prenom', 'c.nom as classe_nom', 'a.nom as annee_nom')
                ->first();

            $detailsTd = $paiements->map(fn ($p) => [
                'nom' => ($p->categorieRecette?->nom ?? 'TD') . ' - ' . ($p->frais?->nom ?? 'Frais TD'),
                'montant' => (float) $p->montant,
            ])->toArray();

            $totalRecuTd = (float) $paiements->sum('montant');
            $totalPayeTd = (float) DB::table('paiement_achats')
                ->where('inscription_td_externe_id', $premier->inscription_td_externe_id)
                ->where('frais_id', $premier->frais_id)
                ->sum('montant');
            $totalFraisTd = (float) ($inscriptionTd->montant_total ?? 0);
            $resteTd = max(0, $totalFraisTd - $totalPayeTd);

            $ticketTd = (new RecuThermiqueService())->generate([
                'numero' => $numeroRecu,
                'date' => $premier->date_paiement ? \Carbon\Carbon::parse($premier->date_paiement)->format('d/m/Y') : now()->format('d/m/Y'),
                'eleve' => trim(($inscriptionTd->eleve_nom ?? $premier->nom) . ' ' . ($inscriptionTd->eleve_prenom ?? $premier->prenom)),
                'matricule' => $inscriptionTd->matricule ?? '-',
                'classe' => $inscriptionTd->classe_nom ?? '-',
                'annee' => $inscriptionTd->annee_nom ?? '-',
                'details' => $detailsTd,
                'total_recu' => $totalRecuTd,
                'total_paye' => $totalPayeTd,
                'total_frais' => $totalFraisTd,
                'reste' => $resteTd,
                'statut' => $resteTd <= 0 ? 'SOLDE' : 'PARTIEL',
                'mode_paiement' => $premier->mode_paiement ?? '-',
            ]);

            (new ThermalPrinterService('Xprinter_POS58'))->print($ticketTd);
            return;
        }
        $details = $paiements->map(fn($p) => [
            'nom' => ($p->categorieRecette?->nom ?? '') . ' - ' . ($p->frais?->nom ?? 'Frais'),
            'montant' => (float) $p->montant,
        ])->toArray();

        $totalRecu = (float) $paiements->sum('montant');
        $totalFraisSelectionnes = 0;
        $totalDejaPaye = 0;

        foreach ($paiements as $paiement) {

    // Utiliser l'année de l'inscription associée à ce paiement
    $anneePaiementId = $paiement->inscription?->annee_id
        ?? $this->anneeId;

    $inscriptionFrais = DB::table('inscription_frais')
        ->where('inscription_id', $paiement->inscription_id)
        ->where('frais_id', $paiement->frais_id)
        ->where('annee_id', $anneePaiementId)
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
| CHARGER LES ANCIENS REÇUS
|--------------------------------------------------------------------------
*/

private function chargerAnciensRecus(): void
{
    $this->anciensRecus = [];

    if (!$this->inscriptionId) {
        return;
    }

    // Retrouver l'inscription sélectionnée et l'identifiant de l'élève
    $inscription = Inscription::with('eleve')
        ->find($this->inscriptionId);

    if (!$inscription || !$inscription->eleve) {
        return;
    }

    $eleveId = $inscription->eleve_id;

    // Rechercher les paiements de cet élève pour toutes ses inscriptions
    $paiements = PaiementAchat::query()
        ->with([
            'frais',
            'categorieRecette',
            'inscription.eleve',
            'inscription.classe',
            'inscription.annee',
        ])
        ->whereNotNull('numero_recu')
        ->whereHas('inscription', function ($query) use ($eleveId) {
            $query->where('eleve_id', $eleveId);
        })
        ->orderByDesc('date_paiement')
        ->orderByDesc('id')
        ->get();

    // Regrouper les lignes par numéro de reçu
    $this->anciensRecus = $paiements
        ->groupBy('numero_recu')
        ->map(function ($lignes, $numeroRecu) {

            $premier = $lignes->first();

            $inscriptionRecu = $premier->inscription;

            return [
                'numero_recu' => $numeroRecu,

                'date' => $premier->date_paiement
                    ? \Carbon\Carbon::parse($premier->date_paiement)->format('d/m/Y')
                    : '-',

                'annee' => $inscriptionRecu?->annee?->nom ?? '-',

                'classe' => $inscriptionRecu?->classe?->nom ?? '-',

                'eleve' => trim(
                    ($premier->nom ?? '') . ' ' . ($premier->prenom ?? '')
                ),

                'frais' => $lignes
                    ->map(function ($paiement) {
                        return $paiement->frais?->nom ?? 'Frais';
                    })
                    ->unique()
                    ->implode(', '),

                'mode_paiement' => $premier->mode_paiement ?? '-',

                'total' => (float) $lignes->sum('montant'),
            ];
        })
        ->values()
        ->toArray();
}

/*
|--------------------------------------------------------------------------
| RÉIMPRIMER UN ANCIEN REÇU
|--------------------------------------------------------------------------
*/

public function reimprimerAncienRecu(string $numeroRecu): void
{
    $this->resetErrorBag('anciensRecus');

    if (!$this->inscriptionId) {
        $this->addError(
            'anciensRecus',
            'Veuillez d’abord sélectionner un élève.'
        );

        return;
    }

    $inscription = Inscription::find($this->inscriptionId);

    if (!$inscription) {
        $this->addError(
            'anciensRecus',
            'L’inscription sélectionnée est introuvable.'
        );

        return;
    }

    // Vérifier que le reçu appartient à l'élève sélectionné
    $recuExiste = PaiementAchat::query()
        ->where('numero_recu', $numeroRecu)
        ->whereHas('inscription', function ($query) use ($inscription) {
            $query->where('eleve_id', $inscription->eleve_id);
        })
        ->exists();

    if (!$recuExiste) {
        $this->addError(
            'anciensRecus',
            'Ce reçu est introuvable ou ne correspond pas à cet élève.'
        );

        return;
    }

    try {
        // Réutiliser le service d'impression déjà présent
        $this->imprimerRecu($numeroRecu);

        $this->dispatch(
            'impressionReussie',
            numeroRecu: $numeroRecu
        );

        $this->successMessage = "Réimpression du reçu {$numeroRecu} lancée.";

        Log::info('Ancien reçu réimprimé', [
            'numero_recu' => $numeroRecu,
            'eleve_id' => $inscription->eleve_id,
        ]);

    } catch (\Throwable $e) {

        Log::error('Erreur de réimpression d’un ancien reçu', [
            'numero_recu' => $numeroRecu,
            'message' => $e->getMessage(),
        ]);

        $this->addError(
            'anciensRecus',
            'Erreur lors de la réimpression du reçu.'
        );

        $this->dispatch(
            'impressionEchouee',
            numeroRecu: $numeroRecu,
            message: $e->getMessage()
        );
    }
}
    /*
    |--------------------------------------------------------------------------
    | RESET
    |--------------------------------------------------------------------------
    */

    /*
|--------------------------------------------------------------------------
| RESET APRÈS MODIFICATION DES FILTRES
|--------------------------------------------------------------------------
*/

private function resetApresSelection(): void
{
    $this->inscriptionId = null;
    $this->inscriptionTdExterneId = null;
    $this->inscriptionsTdExternes = [];
    $this->nouvelEleveId = null;

    $this->anciensRecus = [];

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