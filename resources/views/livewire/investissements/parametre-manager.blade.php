<div class="p-4">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 fw-semibold mb-0">Paramètres d'investissement</h1>

        <button wire:click="ouvrirCreation" class="btn btn-primary btn-sm">
            + Nouveau paramètre
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

    {{-- Recherche --}}
    <div class="card mb-3">
        <div class="card-body">
            <label class="form-label small text-muted mb-1">Recherche (clé, libellé)</label>
            <input type="text" wire:model.live.debounce.300ms="recherche"
                   placeholder="Rechercher un paramètre..."
                   class="form-control form-control-sm" style="max-width: 400px;">
        </div>
    </div>

    {{-- Tableau --}}
    <div class="card mb-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Clé</th>
                        <th>Libellé</th>
                        <th>Valeur</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($parametres as $parametre)
                        <tr wire:key="param-{{ $parametre->id }}">
                            <td><code class="small">{{ $parametre->cle }}</code></td>
                            <td>{{ $parametre->libelle }}</td>
                            <td class="text-muted">{{ $parametre->valeur ?: '—' }}</td>
                            <td class="text-muted">{{ $parametre->type }}</td>
                            <td>
                                <button wire:click="toggle({{ $parametre->id }})"
                                        class="btn btn-sm {{ $parametre->actif ? 'btn-success' : 'btn-secondary' }}">
                                    {{ $parametre->actif ? 'Actif' : 'Inactif' }}
                                </button>
                            </td>
                            <td class="text-end">
                                <button wire:click="voirDetail({{ $parametre->id }})"
                                        class="btn btn-link btn-sm p-0 me-2">Voir</button>
                                <button wire:click="ouvrirEdition({{ $parametre->id }})"
                                        class="btn btn-link btn-sm text-warning p-0 me-2">Modifier</button>
                                <button wire:click="supprimer({{ $parametre->id }})"
                                        wire:confirm="Confirmer la suppression de ce paramètre ?"
                                        class="btn btn-link btn-sm text-danger p-0">Supprimer</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Aucun paramètre trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $parametres->links() }}

    {{-- ============================== --}}
    {{-- MODAL CRÉATION / ÉDITION --}}
    {{-- ============================== --}}
    @if ($showFormModal)
        <div class="modal d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.4);" wire:click.self="fermerFormModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $editingId ? 'Modifier le paramètre' : 'Nouveau paramètre' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="fermerFormModal"></button>
                    </div>

                    <form wire:submit="enregistrer">
                        <div class="modal-body">

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label small text-muted mb-1">Clé</label>
                                    <input type="text" wire:model="cle"
                                           placeholder="ex: taux_repartition_defaut"
                                           class="form-control form-control-sm font-monospace">
                                    @error('cle') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-6">
                                    <label class="form-label small text-muted mb-1">Type</label>
                                    <select wire:model="type" class="form-select form-select-sm">
                                        <option value="">Sélectionner un type</option>
                                        @foreach ($typesDisponibles as $t)
                                            <option value="{{ $t }}">{{ $t }}</option>
                                        @endforeach
                                    </select>
                                    @error('type') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted mb-1">Libellé</label>
                                <input type="text" wire:model="libelle" class="form-control form-control-sm">
                                @error('libelle') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted mb-1">Valeur</label>
                                <input type="text" wire:model="valeur" class="form-control form-control-sm">
                                @error('valeur') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted mb-1">Description</label>
                                <textarea wire:model="description" rows="3" class="form-control form-control-sm"></textarea>
                                @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-check mb-0">
                                <input type="checkbox" id="actif" wire:model="actif" class="form-check-input">
                                <label for="actif" class="form-check-label small">Paramètre actif</label>
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
    @if ($showDetailModal && $parametreSelectionne)
        <div class="modal d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.4);" wire:click.self="fermerDetailModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Détail du paramètre</h5>
                        <button type="button" class="btn-close" wire:click="fermerDetailModal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <span class="small text-muted">Clé</span>
                                <p class="mb-0"><code>{{ $parametreSelectionne->cle }}</code></p>
                            </div>

                            <div class="col-6">
                                <span class="small text-muted">Type</span>
                                <p class="mb-0">{{ $parametreSelectionne->type }}</p>
                            </div>

                            <div class="col-6">
                                <span class="small text-muted">Libellé</span>
                                <p class="fw-medium mb-0">{{ $parametreSelectionne->libelle }}</p>
                            </div>

                            <div class="col-6">
                                <span class="small text-muted">Statut</span>
                                <p class="mb-0">
                                    <span class="badge {{ $parametreSelectionne->actif ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                        {{ $parametreSelectionne->actif ? 'Actif' : 'Inactif' }}
                                    </span>
                                </p>
                            </div>

                            <div class="col-12">
                                <span class="small text-muted">Valeur</span>
                                <p class="mb-0">{{ $parametreSelectionne->valeur ?: '—' }}</p>
                            </div>
                        </div>

                        <div>
                            <span class="small text-muted">Description</span>
                            <p class="mb-0">{{ $parametreSelectionne->description ?: '—' }}</p>
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