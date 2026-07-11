<div class="p-4">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h4 fw-semibold mb-1">Gestion des bénéfices</h1>
            <p class="small text-muted mb-0">
                Total général :
                <span class="fw-semibold text-success">
                    {{ number_format($this->total, 0, ',', ' ') }} F CFA
                </span>
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">
            @if ($mode === 'liste')
                <button wire:click="afficherHistorique" class="btn btn-outline-secondary btn-sm">
                    Historique complet
                </button>
            @else
                <button wire:click="afficherListe" class="btn btn-outline-secondary btn-sm">
                    Retour à la liste
                </button>
            @endif

            <button wire:click="ouvrirCreation" class="btn btn-primary btn-sm">
                + Nouveau bénéfice
            </button>
        </div>
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

    {{-- ============================== --}}
    {{-- MODE LISTE --}}
    {{-- ============================== --}}
    @if ($mode === 'liste')

        {{-- Filtres --}}
        <div class="card mb-3">
            <div class="card-body d-flex flex-wrap align-items-end gap-3">
                <div>
                    <label class="form-label small text-muted mb-1">Date début (à partir de)</label>
                    <input type="date" wire:model.live="filtreDateDebut" class="form-control form-control-sm">
                </div>

                <div>
                    <label class="form-label small text-muted mb-1">Date fin (jusqu'à)</label>
                    <input type="date" wire:model.live="filtreDateFin" class="form-control form-control-sm">
                </div>

                @if ($filtreDateDebut || $filtreDateFin)
                    <button wire:click="resetFiltres" class="btn btn-link btn-sm text-muted text-decoration-underline p-0">
                        Réinitialiser les filtres
                    </button>
                @endif
            </div>
        </div>

        {{-- Tableau --}}
        <div class="card mb-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Période</th>
                            <th>Montant</th>
                            <th>Observation</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($benefices as $benefice)
                            <tr wire:key="benefice-{{ $benefice->id }}">
                                <td>
                                    {{ \Carbon\Carbon::parse($benefice->date_debut)->format('d/m/Y') }}
                                    &rarr;
                                    {{ \Carbon\Carbon::parse($benefice->date_fin)->format('d/m/Y') }}
                                </td>
                                <td class="fw-medium">
                                    {{ number_format($benefice->montant, 0, ',', ' ') }} F CFA
                                </td>
                                <td class="text-muted">
                                    {{ $benefice->observation ?: '—' }}
                                </td>
                                <td class="text-end">
                                    <button wire:click="voirDetail({{ $benefice->id }})"
                                            class="btn btn-link btn-sm p-0 me-2">Voir</button>
                                    <button wire:click="ouvrirEdition({{ $benefice->id }})"
                                            class="btn btn-link btn-sm text-warning p-0 me-2">Modifier</button>
                                    <button wire:click="supprimer({{ $benefice->id }})"
                                            wire:confirm="Confirmer la suppression de ce bénéfice ainsi que ses répartitions associées ?"
                                            class="btn btn-link btn-sm text-danger p-0">Supprimer</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Aucun bénéfice trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $benefices->links() }}

    @endif

    {{-- ============================== --}}
    {{-- MODE HISTORIQUE --}}
    {{-- ============================== --}}
    @if ($mode === 'historique')

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Période</th>
                            <th>Montant</th>
                            <th>Répartitions</th>
                            <th>Observation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($historique as $benefice)
                            <tr wire:key="hist-{{ $benefice->id }}">
                                <td>
                                    {{ \Carbon\Carbon::parse($benefice->date_debut)->format('d/m/Y') }}
                                    &rarr;
                                    {{ \Carbon\Carbon::parse($benefice->date_fin)->format('d/m/Y') }}
                                </td>
                                <td class="fw-medium">
                                    {{ number_format($benefice->montant, 0, ',', ' ') }} F CFA
                                </td>
                                <td class="text-muted">
                                    {{ $benefice->repartitions->count() }} répartition(s)
                                </td>
                                <td class="text-muted">
                                    {{ $benefice->observation ?: '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Aucun historique disponible.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @endif

    {{-- ============================== --}}
    {{-- MODAL CRÉATION / ÉDITION --}}
    {{-- ============================== --}}
    @if ($showFormModal)
        <div class="modal d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.4);" wire:click.self="fermerFormModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $editingId ? 'Modifier le bénéfice' : 'Nouveau bénéfice' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="fermerFormModal"></button>
                    </div>

                    <form wire:submit="enregistrer">
                        <div class="modal-body">

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label small text-muted mb-1">Date début</label>
                                    <input type="date" wire:model="date_debut" class="form-control form-control-sm">
                                    @error('date_debut') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-6">
                                    <label class="form-label small text-muted mb-1">Date fin</label>
                                    <input type="date" wire:model="date_fin" class="form-control form-control-sm">
                                    @error('date_fin') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted mb-1">Montant (F CFA)</label>
                                <input type="number" step="0.01" min="0" wire:model="montant" class="form-control form-control-sm">
                                @error('montant') <div class="text-danger small">{{ $message }}</div> @enderror
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
    @if ($showDetailModal && $beneficeSelectionne)
        <div class="modal d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.4);" wire:click.self="fermerDetailModal">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title mb-1">Détail du bénéfice</h5>
                            <p class="small text-muted mb-0">
                                {{ \Carbon\Carbon::parse($beneficeSelectionne->date_debut)->format('d/m/Y') }}
                                &rarr;
                                {{ \Carbon\Carbon::parse($beneficeSelectionne->date_fin)->format('d/m/Y') }}
                            </p>
                        </div>
                        <button type="button" class="btn-close" wire:click="fermerDetailModal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <span class="small text-muted">Montant total</span>
                                <p class="fw-semibold mb-0">
                                    {{ number_format($beneficeSelectionne->montant, 0, ',', ' ') }} F CFA
                                </p>
                            </div>
                            <div class="col-6">
                                <span class="small text-muted">Observation</span>
                                <p class="mb-0">{{ $beneficeSelectionne->observation ?: '—' }}</p>
                            </div>
                        </div>

                        <div>
                            <h6 class="small fw-medium text-muted mb-2">Répartitions par investisseur</h6>

                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Investisseur</th>
                                            <th>Investissement</th>
                                            <th class="text-end">Part reçue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($beneficeSelectionne->repartitions as $repartition)
                                            <tr wire:key="repartition-{{ $repartition->id }}">
                                                <td>{{ $repartition->investissement->investisseur->nom ?? '—' }}</td>
                                                <td class="text-muted">#{{ $repartition->investissement->id ?? '—' }}</td>
                                                <td class="text-end fw-medium">
                                                    {{ number_format($repartition->montant ?? 0, 0, ',', ' ') }} F CFA
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">
                                                    Aucune répartition enregistrée pour ce bénéfice.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
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