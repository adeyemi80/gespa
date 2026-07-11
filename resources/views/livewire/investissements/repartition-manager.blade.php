<div class="py-4" style="background-color: #f8f9fc; min-height: 100%;">
    <div class="container-fluid">

        {{-- En-tête --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-dark fw-bold">📊 Répartitions de bénéfices</h1>
                <p class="text-muted small mb-0">Calcul et suivi des parts distribuées aux investisseurs</p>
            </div>

            <button wire:click="ouvrirCalcul" class="btn btn-primary shadow-sm">
                + Calculer une répartition
            </button>
        </div>

        {{-- Notifications --}}
        <div x-data="{ show: false, type: 'success', message: '' }"
             x-on:notify.window="
                type = $event.detail.type;
                message = $event.detail.message;
                show = true;
                setTimeout(() => show = false, 4000);
             "
             x-show="show"
             x-transition
             class="alert mb-3"
             :class="type === 'success' ? 'alert-success' : 'alert-danger'"
             style="display: none;">
            <span x-text="message"></span>
        </div>

        {{-- Filtre par bénéfice --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body d-flex flex-wrap align-items-end gap-3">
                <div style="min-width: 320px;">
                    <label class="form-label small text-muted fw-medium">🔎 Filtrer par bénéfice</label>
                    <select wire:model.live="beneficeFiltre" class="form-select">
                        <option value="">Tous les bénéfices</option>
                        @foreach ($this->benefices as $benefice)
                            <option value="{{ $benefice->id }}">
                                {{ \Carbon\Carbon::parse($benefice->date_debut)->format('d/m/Y') }}
                                &rarr;
                                {{ \Carbon\Carbon::parse($benefice->date_fin)->format('d/m/Y') }}
                                ({{ number_format($benefice->montant, 0, ',', ' ') }} F CFA)
                            </option>
                        @endforeach
                    </select>
                </div>

                @if ($beneficeFiltre)
                    <button wire:click="$set('beneficeFiltre', null)" class="btn btn-outline-secondary btn-sm">
                        Réinitialiser
                    </button>
                @endif
            </div>
        </div>

        {{-- Tableau --}}
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Bénéfice (période)</th>
                            <th>Investisseur</th>
                            <th class="text-end">Pourcentage</th>
                            <th class="text-end">Montant</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($repartitions as $repartition)
                            <tr wire:key="rep-{{ $repartition->id }}">
                                <td>
                                    @if ($repartition->benefice)
                                        {{ \Carbon\Carbon::parse($repartition->benefice->date_debut)->format('d/m/Y') }}
                                        &rarr;
                                        {{ \Carbon\Carbon::parse($repartition->benefice->date_fin)->format('d/m/Y') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    {{ $repartition->investissement->investisseur->nom ?? '—' }}
                                    {{ $repartition->investissement->investisseur->prenom ?? '' }}
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-info-subtle text-info-emphasis">
                                        {{ number_format($repartition->pourcentage, 2, ',', ' ') }} %
                                    </span>
                                </td>
                                <td class="text-end fw-semibold text-dark">
                                    {{ number_format($repartition->montant, 0, ',', ' ') }} F CFA
                                </td>
                                <td class="text-end">
                                    <button wire:click="voirDetail({{ $repartition->id }})"
                                            class="btn btn-link btn-sm p-0 me-2">Voir</button>
                                    <button wire:click="ouvrirEdition({{ $repartition->id }})"
                                            class="btn btn-link btn-sm text-warning p-0 me-2">Modifier</button>
                                    <button wire:click="supprimer({{ $repartition->id }})"
                                            wire:confirm="Confirmer la suppression de cette répartition ?"
                                            class="btn btn-link btn-sm text-danger p-0">Supprimer</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Aucune répartition trouvée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $repartitions->links() }}
        </div>

        {{-- ============================== --}}
        {{-- MODAL CALCUL AUTOMATIQUE --}}
        {{-- ============================== --}}
        @if ($showCalculModal)
            <div class="modal d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.4);" wire:click.self="fermerCalculModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary-subtle">
                            <h5 class="modal-title text-dark">🧮 Calculer une répartition</h5>
                            <button type="button" class="btn-close" wire:click="fermerCalculModal"></button>
                        </div>

                        <form wire:submit="calculer">
                            <div class="modal-body">

                                <div class="alert alert-info small mb-3">
                                    La répartition sera calculée automatiquement au prorata du capital de chaque
                                    investissement <strong>actif</strong>. Toute répartition déjà existante pour
                                    ce bénéfice sera remplacée.
                                </div>

                                <div class="mb-0">
                                    <label class="form-label small text-muted fw-medium">Bénéfice</label>
                                    <select wire:model="benefice_id" class="form-select">
                                        <option value="">Sélectionner un bénéfice</option>
                                        @foreach ($this->benefices as $benefice)
                                            <option value="{{ $benefice->id }}">
                                                {{ \Carbon\Carbon::parse($benefice->date_debut)->format('d/m/Y') }}
                                                &rarr;
                                                {{ \Carbon\Carbon::parse($benefice->date_fin)->format('d/m/Y') }}
                                                ({{ number_format($benefice->montant, 0, ',', ' ') }} F CFA)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('benefice_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" wire:click="fermerCalculModal" class="btn btn-outline-secondary btn-sm">
                                    Annuler
                                </button>
                                <button type="submit" wire:loading.attr="disabled" class="btn btn-primary btn-sm">
                                    <span wire:loading.remove wire:target="calculer">Calculer</span>
                                    <span wire:loading wire:target="calculer">Calcul en cours...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        {{-- ============================== --}}
        {{-- MODAL ÉDITION --}}
        {{-- ============================== --}}
        @if ($showEditModal)
            <div class="modal d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.4);" wire:click.self="fermerEditModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-warning-subtle">
                            <h5 class="modal-title text-dark">✏️ Modifier la répartition</h5>
                            <button type="button" class="btn-close" wire:click="fermerEditModal"></button>
                        </div>

                        <form wire:submit="enregistrerEdition">
                            <div class="modal-body">

                                <div class="mb-3">
                                    <label class="form-label small text-muted fw-medium">Pourcentage (%)</label>
                                    <input type="number" step="0.0001" min="0" max="100" wire:model="pourcentage" class="form-control">
                                    @error('pourcentage') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-0">
                                    <label class="form-label small text-muted fw-medium">Montant (F CFA)</label>
                                    <input type="number" step="0.01" min="0" wire:model="montant" class="form-control">
                                    @error('montant') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" wire:click="fermerEditModal" class="btn btn-outline-secondary btn-sm">
                                    Annuler
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    Enregistrer
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
        @if ($showDetailModal && $repartitionSelectionnee)
            <div class="modal d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.4);" wire:click.self="fermerDetailModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">🔍 Détail de la répartition</h5>
                            <button type="button" class="btn-close" wire:click="fermerDetailModal"></button>
                        </div>

                        <div class="modal-body">

                            <div class="row g-3">
                                <div class="col-12">
                                    <span class="small text-muted">Bénéfice (période)</span>
                                    <p class="fw-medium mb-0">
                                        @if ($repartitionSelectionnee->benefice)
                                            {{ \Carbon\Carbon::parse($repartitionSelectionnee->benefice->date_debut)->format('d/m/Y') }}
                                            &rarr;
                                            {{ \Carbon\Carbon::parse($repartitionSelectionnee->benefice->date_fin)->format('d/m/Y') }}
                                        @else
                                            —
                                        @endif
                                    </p>
                                </div>

                                <div class="col-6">
                                    <span class="small text-muted">Investisseur</span>
                                    <p class="fw-medium mb-0">
                                        {{ $repartitionSelectionnee->investissement->investisseur->nom ?? '—' }}
                                        {{ $repartitionSelectionnee->investissement->investisseur->prenom ?? '' }}
                                    </p>
                                </div>

                                <div class="col-6">
                                    <span class="small text-muted">Investissement</span>
                                    <p class="fw-medium mb-0">
                                        #{{ $repartitionSelectionnee->investissement_id }}
                                    </p>
                                </div>

                                <div class="col-6">
                                    <span class="small text-muted">Pourcentage</span>
                                    <p class="mb-0">
                                        <span class="badge bg-info-subtle text-info-emphasis fs-6">
                                            {{ number_format($repartitionSelectionnee->pourcentage, 4, ',', ' ') }} %
                                        </span>
                                    </p>
                                </div>

                                <div class="col-6">
                                    <span class="small text-muted">Montant</span>
                                    <p class="fw-bold text-success mb-0">
                                        {{ number_format($repartitionSelectionnee->montant, 0, ',', ' ') }} F CFA
                                    </p>
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
</div>