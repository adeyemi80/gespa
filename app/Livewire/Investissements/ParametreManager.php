<?php

namespace App\Livewire\Investissements;

use App\Models\ParametreInvestissement;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ParametreManager extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    // ------------------------------------------------------------------
    // Recherche (index)
    // ------------------------------------------------------------------
    public string $recherche = '';

    // ------------------------------------------------------------------
    // Modal création / édition
    // ------------------------------------------------------------------
    public bool $showFormModal = false;
    public ?int $editingId = null;

    public ?string $cle = null;
    public ?string $libelle = null;
    public ?string $valeur = null;
    public ?string $type = null;
    public ?string $description = null;
    public bool $actif = true;

    // ------------------------------------------------------------------
    // Modal détail (show)
    // ------------------------------------------------------------------
    public bool $showDetailModal = false;
    public ?ParametreInvestissement $parametreSelectionne = null;

    public array $typesDisponibles = [
        'string', 'integer', 'decimal', 'boolean', 'date', 'json',
    ];

    protected function rules(): array
    {
        return [
            'cle' => [
                'required',
                'string',
                'max:100',
                Rule::unique('parametres_investissements', 'cle')->ignore($this->editingId),
            ],
            'libelle' => ['required', 'string', 'max:255'],
            'valeur' => ['nullable', 'string'],
            'type' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ];
    }

    protected function messages(): array
    {
        return [
            'cle.required' => 'La clé est obligatoire.',
            'cle.unique' => 'Cette clé est déjà utilisée par un autre paramètre.',
            'libelle.required' => 'Le libellé est obligatoire.',
            'type.required' => 'Le type est obligatoire.',
        ];
    }

    public function updatingRecherche(): void
    {
        $this->resetPage();
    }

    // ------------------------------------------------------------------
    // Création
    // ------------------------------------------------------------------
    public function ouvrirCreation(): void
    {
        $this->resetValidation();
        $this->reset(['editingId', 'cle', 'libelle', 'valeur', 'type', 'description']);
        $this->actif = true;
        $this->showFormModal = true;
    }

    // ------------------------------------------------------------------
    // Édition
    // ------------------------------------------------------------------
    public function ouvrirEdition(int $id): void
    {
        $parametre = ParametreInvestissement::findOrFail($id);

        $this->resetValidation();
        $this->editingId = $parametre->id;
        $this->cle = $parametre->cle;
        $this->libelle = $parametre->libelle;
        $this->valeur = $parametre->valeur;
        $this->type = $parametre->type;
        $this->description = $parametre->description;
        $this->actif = (bool) $parametre->actif;

        $this->showFormModal = true;
    }

    public function fermerFormModal(): void
    {
        $this->showFormModal = false;
        $this->resetValidation();
        $this->reset(['editingId', 'cle', 'libelle', 'valeur', 'type', 'description', 'actif']);
    }

    // ------------------------------------------------------------------
    // Enregistrement (création ou mise à jour)
    // ------------------------------------------------------------------
    public function enregistrer(): void
    {
        $validated = $this->validate();
        $validated['actif'] = $this->actif;

        if ($this->editingId) {

            $parametre = ParametreInvestissement::findOrFail($this->editingId);
            $parametre->update($validated);

            $this->dispatch(
                'notify',
                type: 'success',
                message: 'Paramètre modifié avec succès.'
            );

        } else {

            ParametreInvestissement::create($validated);

            $this->dispatch(
                'notify',
                type: 'success',
                message: 'Paramètre ajouté avec succès.'
            );
        }

        $this->fermerFormModal();
        $this->resetPage();
    }

    // ------------------------------------------------------------------
    // Suppression
    // ------------------------------------------------------------------
    public function supprimer(int $id): void
    {
        ParametreInvestissement::findOrFail($id)->delete();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Paramètre supprimé avec succès.'
        );

        $this->resetPage();
    }

    // ------------------------------------------------------------------
    // Activation / désactivation rapide (toggle)
    // ------------------------------------------------------------------
    public function toggle(int $id): void
    {
        $parametre = ParametreInvestissement::findOrFail($id);

        $parametre->update([
            'actif' => ! $parametre->actif,
        ]);

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Statut du paramètre modifié.'
        );
    }

    // ------------------------------------------------------------------
    // Affichage détaillé (show)
    // ------------------------------------------------------------------
    public function voirDetail(int $id): void
    {
        $this->parametreSelectionne = ParametreInvestissement::findOrFail($id);
        $this->showDetailModal = true;
    }

    public function fermerDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->parametreSelectionne = null;
    }

    public function render()
    {
        $parametres = ParametreInvestissement::query()
            ->when($this->recherche, function ($query) {
                $search = $this->recherche;

                $query->where(function ($q) use ($search) {
                    $q->where('cle', 'ILIKE', "%{$search}%")
                        ->orWhere('libelle', 'ILIKE', "%{$search}%");
                });
            })
            ->orderBy('libelle')
            ->paginate(15);

        return view('livewire.investissements.parametre-manager', [
            'parametres' => $parametres,
        ]);
    }
}