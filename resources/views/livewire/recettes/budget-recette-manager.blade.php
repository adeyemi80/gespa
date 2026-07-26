<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Budget des recettes</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('budgets-recettes.export-pdf', ['annee_id' => $anneeFiltre]) }}"
               target="_blank" class="btn btn-outline-secondary">
                <i class="bi bi-file-earmark-pdf"></i> Exporter en PDF
            </a>
            <button type="button" class="btn btn-primary" wire:click="ouvrirModalCreation">
                <i class="bi bi-plus-lg"></i> Nouveau budget
            </button>
        </div>
    </div>

    {{-- Alertes auto-dismiss --}}
    @if ($messageSucces)
        <div wire:key="alerte-succes-{{ now()->timestamp }}" x-data="{ visible: true }" x-show="visible"
             x-init="setTimeout(() => visible = false, 4000)"
             class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $messageSucces }}
            <button type="button" class="btn-close" @click="visible = false"></button>
        </div>
    @endif

    @if ($messageErreur)
        <div wire:key="alerte-erreur-{{ now()->timestamp }}" x-data="{ visible: true }" x-show="visible"
             x-init="setTimeout(() => visible = false, 5000)"
             class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $messageErreur }}
            <button type="button" class="btn-close" @click="visible = false"></button>
        </div>
    @endif

    {{-- Filtre année --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <label class="col-form-label">Année scolaire</label>
                </div>
                <div class="col-md-3">
                    <select wire:model.live="anneeFiltre" class="form-select">
                        @foreach ($annees as $annee)
                            <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Cartes de synthèse --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Total prévu</div>
                    <div class="h4 mb-0">{{ number_format($totalPrevu, 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Total réalisé</div>
                    <div class="h4 mb-0">{{ number_format($totalRealise, 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Écart</div>
                    <div class="h4 mb-0 {{ $totalEcart < 0 ? 'text-danger' : 'text-success' }}">
                        {{ $totalEcart >= 0 ? '+' : '' }}{{ number_format($totalEcart, 0, ',', ' ') }} FCFA
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Taux de réalisation</div>
                    <div class="h4 mb-0">{{ $tauxGlobal }}%</div>
                    <div class="progress mt-1" style="height: 6px;">
                        <div class="progress-bar {{ $tauxGlobal >= 100 ? 'bg-success' : ($tauxGlobal >= 60 ? 'bg-info' : 'bg-warning') }}"
                             style="width: {{ min($tauxGlobal, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Catégorie</th>
                        <th class="text-end">Prévu</th>
                        <th class="text-end">Réalisé</th>
                        <th class="text-end">Écart</th>
                        <th style="width: 160px;">Taux</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($budgets as $budget)
                        <tr wire:key="budget-recette-{{ $budget->id }}">
                            <td>
                                <span class="badge bg-secondary">{{ $budget->categorie->code }}</span>
                                {{ $budget->categorie->nom }}
                            </td>
                            <td class="text-end">{{ number_format($budget->montant_prevu, 0, ',', ' ') }} FCFA</td>
                            <td class="text-end">{{ number_format($budget->montant_realise, 0, ',', ' ') }} FCFA</td>
                            <td class="text-end {{ $budget->montant_ecart < 0 ? 'text-danger' : 'text-success' }} fw-semibold">
                                {{ $budget->montant_ecart >= 0 ? '+' : '' }}{{ number_format($budget->montant_ecart, 0, ',', ' ') }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar {{ $budget->taux_realisation >= 100 ? 'bg-success' : ($budget->taux_realisation >= 60 ? 'bg-info' : 'bg-warning') }}"
                                             style="width: {{ min($budget->taux_realisation, 100) }}%"></div>
                                    </div>
                                    <span class="small text-muted">{{ $budget->taux_realisation }}%</span>
                                </div>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary" title="Modifier"
                                        wire:click="ouvrirModalEdition({{ $budget->id }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer"
                                        wire:click="confirmerSuppression({{ $budget->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Aucun budget de recette défini pour cette année scolaire.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modale création / édition --}}
    @if ($showModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);" wire:key="modal-budget-recette">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form wire:submit="enregistrer">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                {{ $modeEdition ? 'Modifier le budget de recette' : 'Nouveau budget de recette' }}
                            </h5>
                            <button type="button" class="btn-close" wire:click="fermerModal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Année scolaire <span class="text-danger">*</span></label>
                                <select wire:model="annee_id" class="form-select @error('annee_id') is-invalid @enderror">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach ($annees as $annee)
                                        <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                                    @endforeach
                                </select>
                                @error('annee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                                <select wire:model="categorie_id" class="form-select @error('categorie_id') is-invalid @enderror">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach ($this->categoriesDisponibles as $categorie)
                                        <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                                    @endforeach
                                </select>
                                @error('categorie_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div class="form-text">Seules les catégories sans budget déjà défini pour cette année sont proposées.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Montant prévu (FCFA) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" wire:model="montant_prevu"
                                       class="form-control @error('montant_prevu') is-invalid @enderror">
                                @error('montant_prevu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" wire:click="fermerModal">Annuler</button>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="enregistrer">
                                <span wire:loading wire:target="enregistrer" class="spinner-border spinner-border-sm me-1"></span>
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Modale confirmation suppression --}}
    @if ($showSuppressionModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);" wire:key="modal-suppression-budget-recette">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmer la suppression</h5>
                        <button type="button" class="btn-close" wire:click="annulerSuppression"></button>
                    </div>
                    <div class="modal-body">
                        Es-tu sûr de vouloir supprimer ce budget de recette ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="annulerSuppression">Annuler</button>
                        <button type="button" class="btn btn-danger" wire:click="supprimer">
                            <i class="bi bi-trash"></i> Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>