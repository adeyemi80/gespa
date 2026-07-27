<?php

namespace App\Livewire\Depenses;

use App\Models\Annee;
use App\Models\CategorieDepense;
use App\Models\Depense;
use App\Models\PieceJustificative;
use App\Models\TypeDepense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\BudgetDepense;

#[Layout('layouts.app')]
#[Title('Gestion des dépenses')]
class DepenseManager extends Component
{
    use WithFileUploads;
    use WithPagination;

    // Filtres de la liste
    public string $recherche = '';
    public string $categorieFiltre = '';
    public string $typeFiltre = '';
    public string $statutFiltre = '';
    public string $anneeFiltre = '';

    // État des modales
    public bool $showModal = false;
    public bool $showSuppressionModal = false;
    public bool $modeEdition = false;

    // Champs du formulaire
    public ?int $depense_id = null;
    public string $numero_piece = '';
    public string $categorie_id = '';
    public string $type_depense_id = '';
    public string $annee_id = '';
    public string $libelle = '';
    public string $description = '';
    public string $montant = '';
    public string $date_depense = '';
    public string $beneficiaire = '';
    public string $mode_paiement = 'especes';
    public string $reference_paiement = '';
    public string $statut = 'brouillon';

    // Pièces justificatives
    public array $nouvellesPieces = [];
    public $piecesExistantes = [];

    // Suppression
    public ?int $depenseASupprimer = null;

    // Messages (propriétés de composant plutôt que session()->flash(), Livewire ne gère pas bien le flash)
    public string $messageSucces = '';
    public string $messageErreur = '';

    protected function rules(): array
    {
        return [
            'categorie_id' => 'required|exists:categories_depenses,id',
            'type_depense_id' => 'required|exists:types_depenses,id',
            'annee_id' => 'required|exists:annees,id',
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'montant' => 'required|numeric|min:0.01',
            'date_depense' => 'required|date',
            'beneficiaire' => 'nullable|string|max:255',
            'mode_paiement' => 'required|in:especes,cheque,virement,mobile_money',
            'reference_paiement' => 'nullable|string|max:255',
            'nouvellesPieces.*' => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png',
        ];
    }

    protected array $messages = [
        'categorie_id.required' => 'La catégorie est obligatoire.',
        'type_depense_id.required' => 'Le type de dépense est obligatoire.',
        'annee_id.required' => 'L\'année scolaire est obligatoire.',
        'libelle.required' => 'Le libellé est obligatoire.',
        'montant.required' => 'Le montant est obligatoire.',
        'montant.min' => 'Le montant doit être supérieur à zéro.',
        'date_depense.required' => 'La date de la dépense est obligatoire.',
        'nouvellesPieces.*.mimes' => 'Les pièces justificatives doivent être au format PDF, JPG ou PNG.',
        'nouvellesPieces.*.max' => 'Chaque pièce justificative ne doit pas dépasser 5 Mo.',
    ];

    public function mount(): void
    {
        $anneeEnCours = Annee::where('en_cours', true)->first();
        $this->anneeFiltre = (string) ($anneeEnCours->id ?? '');
    }

    public function updatingRecherche(): void
    {
        $this->resetPage();
    }

    public function updatingCategorieFiltre(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFiltre(): void
    {
        $this->resetPage();
    }

    public function updatingStatutFiltre(): void
    {
        $this->resetPage();
    }

    public function updatingAnneeFiltre(): void
    {
        $this->resetPage();
    }

    public function updatedCategorieId(): void
    {
        // Réinitialiser le type sélectionné si la catégorie change, car les types dépendent de la catégorie
        $this->type_depense_id = '';
    }

    public function getTypesDisponiblesProperty()
    {
        if (! $this->categorie_id) {
            return collect();
        }

        return TypeDepense::actif()
            ->where('categorie_id', $this->categorie_id)
            ->orderBy('nom')
            ->get();
    }

    public function ouvrirModalCreation(): void
    {
        $this->resetFormulaire();
        $this->numero_piece = $this->genererNumeroPiece();
        $this->date_depense = now()->format('Y-m-d');
        $this->modeEdition = false;
        $this->showModal = true;
    }

    public function ouvrirModalEdition(int $id): void
    {
        $depense = Depense::with('piecesJustificatives')->findOrFail($id);

        $this->depense_id = $depense->id;
        $this->numero_piece = $depense->numero_piece;
        $this->categorie_id = (string) $depense->typeDepense->categorie_id;
        $this->type_depense_id = (string) $depense->type_depense_id;
        $this->annee_id = (string) $depense->annee_id;
        $this->libelle = $depense->libelle;
        $this->description = (string) $depense->description;
        $this->montant = (string) $depense->montant;
        $this->date_depense = $depense->date_depense->format('Y-m-d');
        $this->beneficiaire = (string) $depense->beneficiaire;
        $this->mode_paiement = $depense->mode_paiement;
        $this->reference_paiement = (string) $depense->reference_paiement;
        $this->statut = $depense->statut;
        $this->piecesExistantes = $depense->piecesJustificatives;
        $this->nouvellesPieces = [];

        $this->modeEdition = true;
        $this->showModal = true;
    }

    public function fermerModal(): void
    {
        $this->showModal = false;
        $this->resetFormulaire();
    }

    protected function resetFormulaire(): void
    {
        $this->reset([
            'depense_id', 'numero_piece', 'categorie_id', 'type_depense_id', 'annee_id',
            'libelle', 'description', 'montant', 'date_depense', 'beneficiaire',
            'mode_paiement', 'reference_paiement', 'statut', 'nouvellesPieces', 'piecesExistantes',
        ]);
        $this->mode_paiement = 'especes';
        $this->statut = 'brouillon';
        $this->resetErrorBag();
    }

    protected function genererNumeroPiece(): string
    {
        $annee = now()->year;

        $dernier = Depense::where('numero_piece', 'like', "DEP-{$annee}-%")
            ->orderByDesc('id')
            ->first();

        $sequence = $dernier
            ? ((int) substr($dernier->numero_piece, -4)) + 1
            : 1;

        return sprintf('DEP-%d-%04d', $annee, $sequence);
    }

    public function enregistrer(): void
    {
        $this->validate();

        DB::transaction(function () {
            if ($this->modeEdition) {
                $depense = Depense::findOrFail($this->depense_id);
                $depense->update([
                    'type_depense_id' => $this->type_depense_id,
                    'annee_id' => $this->annee_id,
                    'libelle' => $this->libelle,
                    'description' => $this->description ?: null,
                    'montant' => $this->montant,
                    'date_depense' => $this->date_depense,
                    'beneficiaire' => $this->beneficiaire ?: null,
                    'mode_paiement' => $this->mode_paiement,
                    'reference_paiement' => $this->reference_paiement ?: null,
                ]);
            } else {
                $depense = Depense::create([
                    'numero_piece' => $this->numero_piece,
                    'type_depense_id' => $this->type_depense_id,
                    'annee_id' => $this->annee_id,
                    'libelle' => $this->libelle,
                    'description' => $this->description ?: null,
                    'montant' => $this->montant,
                    'date_depense' => $this->date_depense,
                    'beneficiaire' => $this->beneficiaire ?: null,
                    'mode_paiement' => $this->mode_paiement,
                    'reference_paiement' => $this->reference_paiement ?: null,
                    'statut' => 'brouillon',
                    'cree_par' => Auth::id(),
                ]);
            }

            foreach ($this->nouvellesPieces as $fichier) {
                $chemin = $fichier->store('pieces-justificatives/' . $depense->id, 'public');

                PieceJustificative::create([
                    'depense_id' => $depense->id,
                    'nom_fichier' => $fichier->getClientOriginalName(),
                    'chemin_fichier' => $chemin,
                    'type_mime' => $fichier->getMimeType(),
                    'taille' => $fichier->getSize(),
                ]);
            }
        });

        $this->messageSucces = $this->modeEdition
            ? 'Dépense mise à jour avec succès.'
            : 'Dépense enregistrée avec succès.';

        $this->showModal = false;
        $this->resetFormulaire();
    }

    public function supprimerPieceJustificative(int $pieceId): void
    {
        $piece = PieceJustificative::findOrFail($pieceId);
        Storage::disk('public')->delete($piece->chemin_fichier);
        $piece->delete();

        $this->piecesExistantes = collect($this->piecesExistantes)
            ->reject(fn ($p) => $p->id === $pieceId)
            ->values();

        $this->messageSucces = 'Pièce justificative supprimée.';
    }

    public function confirmerSuppression(int $id): void
    {
        $this->depenseASupprimer = $id;
        $this->showSuppressionModal = true;
    }

    public function annulerSuppression(): void
    {
        $this->depenseASupprimer = null;
        $this->showSuppressionModal = false;
    }

    public function supprimer(): void
    {
        $depense = Depense::findOrFail($this->depenseASupprimer);

        if ($depense->statut === 'payee') {
            $this->messageErreur = 'Impossible de supprimer une dépense déjà payée.';
            $this->showSuppressionModal = false;
            return;
        }

        foreach ($depense->piecesJustificatives as $piece) {
            Storage::disk('public')->delete($piece->chemin_fichier);
        }

        $depense->delete();

        $this->messageSucces = 'Dépense supprimée avec succès.';
        $this->showSuppressionModal = false;
        $this->depenseASupprimer = null;
    }

    public function valider(int $id): void
    {
        $depense = Depense::findOrFail($id);
        $depense->update([
            'statut' => 'validee',
            'valide_par' => Auth::id(),
            'valide_le' => now(),
        ]);

        $this->messageSucces = "Dépense {$depense->numero_piece} validée avec succès.";
    }

   public function marquerPayee(int $id): void
{
    $depense = Depense::findOrFail($id);

    if ($depense->statut !== 'validee') {
        $this->addError('statut', 'Seule une dépense validée peut être marquée comme payée.');
        return;
    }

    DB::transaction(function () use ($depense) {
        $depense->update(['statut' => 'payee']);

        if ($depense->type_depense_id && $depense->annee_id) {
            $categorieId = TypeDepense::where('id', $depense->type_depense_id)->value('categorie_id');

            if ($categorieId) {
                BudgetDepense::where('categorie_id', $categorieId)
                    ->where('annee_id', $depense->annee_id)
                    ->increment('montant_utilise', $depense->montant);
            }
        }
    });

    $this->messageSucces = 'Dépense marquée comme payée.';
}

    public function rejeter(int $id, string $motif = ''): void
    {
        $depense = Depense::findOrFail($id);
        $depense->update([
            'statut' => 'rejetee',
            'motif_rejet' => $motif ?: 'Non précisé',
        ]);

        $this->messageSucces = "Dépense {$depense->numero_piece} rejetée.";
    }

    public function render()
    {
        $depenses = Depense::query()
            ->with(['typeDepense.categorie', 'annee'])
            ->when($this->recherche, function ($query) {
                $query->where(function ($q) {
                    $q->where('libelle', 'ILIKE', '%' . $this->recherche . '%')
                        ->orWhere('numero_piece', 'ILIKE', '%' . $this->recherche . '%')
                        ->orWhere('beneficiaire', 'ILIKE', '%' . $this->recherche . '%');
                });
            })
            ->when($this->typeFiltre, fn ($q) => $q->where('type_depense_id', $this->typeFiltre))
            ->when($this->categorieFiltre, function ($query) {
                $query->whereHas('typeDepense', fn ($q) => $q->where('categorie_id', $this->categorieFiltre));
            })
            ->when($this->statutFiltre, fn ($q) => $q->where('statut', $this->statutFiltre))
            ->when($this->anneeFiltre, fn ($q) => $q->where('annee_id', $this->anneeFiltre))
            ->orderByDesc('date_depense')
            ->paginate(15);

        return view('livewire.depenses.depense-manager', [
            'depenses' => $depenses,
            'categories' => CategorieDepense::actif()->orderBy('nom')->get(),
            'annees' => Annee::orderBy('nom')->get(),
        ]);
    }
}