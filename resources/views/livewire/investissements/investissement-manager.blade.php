<div class="p-4">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 fw-semibold mb-0">Investissements</h1>

        <button wire:click="ouvrirCreation" class="btn btn-primary btn-sm">
            + Nouvel investissement
        </button>
    </div>

    {{-- Notifications --}}
    <div x-data="{ show: false, type: 'success', message: '' }"
         x-on:notify.window="
            type = $event.detail.type;
            message = $event.detail.message;
            show = true;
            setTimeout(() => show = false, 3000);
         "
         x-show="show"
         x-transition
         class="alert mb-3"
         :class="type === 'success' ? 'alert-success' : 'alert-danger'"
         style="display: none;">
        <span x-text="message"></span>
    </div>

    {{-- Filtres --}}
    <div class="card mb-3">
        <div class="card-body d-flex flex-wrap align-items-end gap-3">
            <div class="flex-grow-1" style="min-width: 260px;">
                <label class="form-label small text-muted mb-1">Recherche (nom, prénom de l'investisseur)</label>
                <input type="text" wire:model.live.debounce.300ms="recherche"
                       placeholder="Rechercher un investisseur..."
                       class="form-control form-control-sm">
            </div>

            <div class="form-check pb-2">
                <input type="checkbox" id="actifsUniquement" wire:model.live="actifsUniquement"
                       class="form-check-input">
                <label for="actifsUniquement" class="form-check-label small">Actifs uniquement</label>
            </div>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="card mb-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Investisseur</th>
                        <th class="text-end">Montant</th>
                        <th>Taux</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($investissements as $investissement)
                        <tr wire:key="invmt-{{ $investissement->id }}">
                            <td>{{ \Carbon\Carbon::parse($investissement->date_investissement)->format('d/m/Y') }}</td>
                            <td>
                                {{ $investissement->investisseur->nom ?? '—' }}
                                {{ $investissement->investisseur->prenom ?? '' }}
                            </td>
                            <td class="text-end fw-medium">
                                {{ number_format($investissement->montant, 0, ',', ' ') }} F CFA
                            </td>
                            <td class="text-muted">
                                {{ $investissement->taux !== null ? $investissement->taux.' %' : '—' }}
                            </td>
                            <td>
                                <span class="badge {{ $investissement->statut === 'actif' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                    {{ $investissement->statut ? ucfirst($investissement->statut) : '—' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button wire:click="voirDetail({{ $investissement->id }})"
                                        class="btn btn-link btn-sm p-0 me-2">Voir</button>
                                <button wire:click="ouvrirEdition({{ $investissement->id }})"
                                        class="btn btn-link btn-sm text-warning p-0 me-2">Modifier</button>
                                <button wire:click="supprimer({{ $investissement->id }})"
                                        wire:confirm="Confirmer la suppression de cet investissement ?"
                                        class="btn btn-link btn-sm text-danger p-0">Supprimer</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Aucun investissement trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $investissements->links() }}

    {{-- ============================== --}}
    {{-- MODAL CRÉATION / ÉDITION --}}
    {{-- ============================== --}}
    @if ($showFormModal)
        <div class="modal d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.4);" wire:click.self="fermerFormModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $editingId ? "Modifier l'investissement" : 'Nouvel investissement' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="fermerFormModal"></button>
                    </div>

                    <form wire:submit="enregistrer">
                        <div class="modal-body">

                            @if (! $editingId)
                                <div class="alert alert-info small">
                                    Le montant de l'investissement se construit automatiquement à partir des
                                    versements que vous enregistrerez ensuite — il n'est pas saisi ici.
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label small text-muted mb-1">Investisseur</label>
                                <select wire:model="investisseur_id" class="form-select form-select-sm">
                                    <option value="">Sélectionner un investisseur</option>
                                    @foreach ($this->investisseurs as $investisseur)
                                        <option value="{{ $investisseur->id }}">
                                            {{ $investisseur->nom }} {{ $investisseur->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('investisseur_id') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label small text-muted mb-1">Date d'investissement</label>
                                    <input type="date" wire:model="date_investissement" class="form-control form-control-sm">
                                    @error('date_investissement') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-6">
                                    <label class="form-label small text-muted mb-1">Taux (%)</label>
                                    <input type="number" step="0.01" min="0" wire:model="taux" class="form-control form-control-sm">
                                    @error('taux') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted mb-1">Statut</label>
                                <select wire:model="statut" class="form-select form-select-sm">
                                    <option value="actif">Actif</option>
                                    <option value="inactif">Inactif</option>
                                    <option value="cloture">Clôturé</option>
                                </select>
                                @error('statut') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-0">
                                <label class="form-label small text-muted mb-1">Observation</label>
                                <textarea wire:model="observation" rows="3" class="form-control form-control-sm"></textarea>
                                @error('observation') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" wire:click="fermerFormModal" class="btn btn-outline-secondary btn-sm">
                                Annuler
                            </button>
                            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary btn-sm">
                                <span wire:loading.remove wire:target="enregistrer">Enregistrer</span>
                                <span wire:loading wire:target="enregistrer">Enregistrement...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- ============================== --}}
    {{-- MODAL DÉTAIL (show) --}}
    {{-- ============================== --}}
    @if ($showDetailModal && $investissementSelectionne)
        <div class="modal d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.4);" wire:click.self="fermerDetailModal">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title mb-1">Détail de l'investissement</h5>
                            <p class="small text-muted mb-0">
                                {{ $investissementSelectionne->investisseur->nom ?? '—' }}
                                {{ $investissementSelectionne->investisseur->prenom ?? '' }}
                                —
                                {{ \Carbon\Carbon::parse($investissementSelectionne->date_investissement)->format('d/m/Y') }}
                            </p>
                        </div>
                        <button type="button" class="btn-close" wire:click="fermerDetailModal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <div class="bg-light rounded p-3">
                                    <span class="small text-muted">Montant total investi (versements)</span>
                                    <p class="fw-semibold mb-0">
                                        {{ number_format($totalVersementsSelectionne, 0, ',', ' ') }} F CFA
                                    </p>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="bg-light rounded p-3">
                                    <span class="small text-muted">Total retiré</span>
                                    <p class="fw-semibold mb-0">
                                        {{ number_format($totalRetraitsSelectionne, 0, ',', ' ') }} F CFA
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-primary-subtle rounded p-3 mb-3">
                            <span class="small text-primary">Capital disponible</span>
                            <p class="h5 fw-semibold text-primary mb-0">
                                {{ number_format($capitalDisponibleSelectionne, 0, ',', ' ') }} F CFA
                            </p>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <span class="small text-muted">Taux</span>
                                <p class="mb-0">
                                    {{ $investissementSelectionne->taux !== null ? $investissementSelectionne->taux.' %' : '—' }}
                                </p>
                            </div>
                            <div class="col-6">
                                <span class="small text-muted">Statut</span>
                                <p class="mb-0">{{ $investissementSelectionne->statut ? ucfirst($investissementSelectionne->statut) : '—' }}</p>
                            </div>
                        </div>

                        <div>
                            <span class="small text-muted">Observation</span>
                            <p class="mb-0">{{ $investissementSelectionne->observation ?: '—' }}</p>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button wire:click="fermerDetailModal" class="btn btn-outline-secondary btn-sm">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>