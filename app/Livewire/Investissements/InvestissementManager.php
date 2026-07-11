<?php

namespace App\Livewire\Investissements;

use App\Models\Investissement;
use App\Models\Investisseur;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class InvestissementManager extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    // ------------------------------------------------------------------
    // Liste / filtres
    // ------------------------------------------------------------------
    public string $recherche = '';
    public bool $actifsUniquement = false;

    // ------------------------------------------------------------------
    // Modal création / édition
    // ------------------------------------------------------------------
    public bool $showFormModal = false;
    public ?int $editingId = null;

    public ?int $investisseur_id = null;
    public ?string $date_investissement = null;
    public ?float $taux = null;
    public ?string $statut = 'actif';
    public ?string $observation = null;

    // ------------------------------------------------------------------
    // Modal détail (show)
    // ------------------------------------------------------------------
    public bool $showDetailModal = false;
    public ?Investissement $investissementSelectionne = null;
    public float $totalVersementsSelectionne = 0;
    public float $totalRetraitsSelectionne = 0;
    public float $capitalDisponibleSelectionne = 0;

    /**
     * Note : 'montant' n'est pas une règle de validation ici.
     * Ce n'est plus un champ saisi par l'utilisateur : il est
     * recalculé automatiquement par VersementObserver dès qu'un
     * versement est créé/modifié/supprimé (montant = somme des versements).
     */
    protected function rules(): array
    {
        return [
            'investisseur_id' => ['required', 'exists:investisseurs,id'],
            'date_investissement' => ['required', 'date'],
            'taux' => ['nullable', 'numeric', 'min:0'],
            'statut' => ['nullable', 'string'],
            'observation' => ['nullable', 'string'],
        ];
    }

    protected function messages(): array
    {
        return [
            'investisseur_id.required' => "L'investisseur est obligatoire.",
            'investisseur_id.exists' => "L'investisseur sélectionné est invalide.",
            'date_investissement.required' => 'La date est obligatoire.',
        ];
    }

    public function updatingRecherche(): void
    {
        $this->resetPage();
    }

    public function updatingActifsUniquement(): void
    {
        $this->resetPage();
    }

    // ------------------------------------------------------------------
    // Investisseurs disponibles pour le select (mémorisé)
    // ------------------------------------------------------------------
    #[Computed]
    public function investisseurs()
    {
        return Investisseur::orderBy('nom')->get();
    }

    // ------------------------------------------------------------------
    // Création
    // ------------------------------------------------------------------
    public function ouvrirCreation(): void
    {
        $this->resetValidation();
        $this->reset([
            'editingId',
            'investisseur_id',
            'date_investissement',
            'taux',
            'observation',
        ]);
        $this->statut = 'actif';
        $this->showFormModal = true;
    }

    // ------------------------------------------------------------------
    // Édition
    // ------------------------------------------------------------------
    public function ouvrirEdition(int $id): void
    {
        $investissement = Investissement::findOrFail($id);

        $this->resetValidation();
        $this->editingId = $investissement->id;
        $this->investisseur_id = $investissement->investisseur_id;
        $this->date_investissement = optional($investissement->date_investissement)->format('Y-m-d')
            ?? $investissement->date_investissement;
        $this->taux = $investissement->taux;
        $this->statut = $investissement->statut;
        $this->observation = $investissement->observation;

        $this->showFormModal = true;
    }

    public function fermerFormModal(): void
    {
        $this->showFormModal = false;
        $this->resetValidation();
        $this->reset([
            'editingId',
            'investisseur_id',
            'date_investissement',
            'taux',
            'statut',
            'observation',
        ]);
    }

    // ------------------------------------------------------------------
    // Enregistrement (création ou mise à jour)
    // ------------------------------------------------------------------
    public function enregistrer(): void
    {
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {

            if ($this->editingId) {

                $investissement = Investissement::findOrFail($this->editingId);
                $investissement->update($validated);

                $this->dispatch(
                    'notify',
                    type: 'success',
                    message: 'Investissement modifié avec succès.'
                );

            } else {

                // montant reste à sa valeur par défaut (0) à la création :
                // il ne sera positif qu'une fois des versements enregistrés
                Investissement::create($validated);

                $this->dispatch(
                    'notify',
                    type: 'success',
                    message: 'Investissement créé avec succès. Ajoutez des versements pour constituer son capital.'
                );
            }

        });

        $this->fermerFormModal();
        $this->resetPage();
    }

    // ------------------------------------------------------------------
    // Suppression
    // ------------------------------------------------------------------
    public function supprimer(int $id): void
    {
        DB::transaction(function () use ($id) {

            $investissement = Investissement::findOrFail($id);
            $investissement->delete();

        });

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Investissement supprimé avec succès.'
        );

        $this->resetPage();
    }

    // ------------------------------------------------------------------
    // Affichage détaillé (show)
    // ------------------------------------------------------------------
    public function voirDetail(int $id): void
    {
        $investissement = Investissement::with([
            'investisseur',
            'versements',
            'retraitsCapital',
        ])->findOrFail($id);

        $totalVersements = $investissement->versements()->sum('montant');
        $totalRetraits = $investissement->retraitsCapital()->sum('montant');

        // montant (colonne) = totalVersements en théorie (synchronisé par
        // VersementObserver), donc le capital disponible est simplement :
        $capitalDisponible = $totalVersements - $totalRetraits;

        $this->investissementSelectionne = $investissement;
        $this->totalVersementsSelectionne = $totalVersements;
        $this->totalRetraitsSelectionne = $totalRetraits;
        $this->capitalDisponibleSelectionne = $capitalDisponible;

        $this->showDetailModal = true;
    }

    public function fermerDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->investissementSelectionne = null;
    }

    public function render()
    {
        $investissements = Investissement::with('investisseur')
            ->when($this->recherche, function ($query) {
                $search = $this->recherche;

                $query->whereHas('investisseur', function ($q) use ($search) {
                    $q->where('nom', 'ilike', "%{$search}%")
                        ->orWhere('prenom', 'ilike', "%{$search}%");
                });
            })
            ->when($this->actifsUniquement, function ($query) {
                $query->where('statut', 'actif');
            })
            ->latest()
            ->paginate(15);

        return view('livewire.investissements.investissement-manager', [
            'investissements' => $investissements,
        ]);
    }
}