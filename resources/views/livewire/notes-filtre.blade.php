<div>
    {{-- ══════════════════════════════════════════════════════════
         FILTRES
    ══════════════════════════════════════════════════════════ --}}
    <div class="card shadow-sm border-0 rounded mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-primary">
                <i class="bi bi-funnel-fill me-1"></i> Filtres
            </h6>
            <button
                wire:click="resetFiltres"
                class="btn btn-outline-secondary btn-sm"
                title="Réinitialiser les filtres">
                <i class="bi bi-x-lg me-1"></i> Reset
            </button>
        </div>

        <div class="card-body">
            <div class="row g-3">

                {{-- Année --}}
                <div class="col-md-2">
                    <label class="form-label fw-semibold small">Année</label>
                    <select wire:model.live="annee_id" class="form-select form-select-sm">
                        <option value="">— Toutes —</option>
                        @foreach ($annees as $annee)
                            <option value="{{ $annee->id }}">
                                {{ $annee->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Trimestre --}}
                <div class="col-md-2">
                    <label class="form-label fw-semibold small">Trimestre</label>
                    <select wire:model.live="trimestre_id" class="form-select form-select-sm">
                        <option value="">— Tous —</option>
                        @foreach ($trimestres as $trimestre)
                            <option value="{{ $trimestre->id }}">
                                {{ $trimestre->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Cycle --}}
                <div class="col-md-2">
                    <label class="form-label fw-semibold small">Cycle</label>
                    <select wire:model.live="cycle_id" class="form-select form-select-sm">
                        <option value="">— Tous —</option>
                        @foreach ($cycles as $cycle)
                            <option value="{{ $cycle->id }}">
                                {{ $cycle->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Classe — dépend du Cycle --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">
                        Classe
                        @if($cycle_id && $classes->isEmpty())
                            <span class="text-danger small">(aucune classe)</span>
                        @endif
                    </label>
                    <select
                        wire:model.live="classe_id"
                        class="form-select form-select-sm"
                        @if(!$cycle_id) disabled @endif>
                        <option value="">— Toutes —</option>
                        @foreach ($classes as $classe)
                            <option value="{{ $classe->id }}">
                                {{ $classe->nom }}
                            </option>
                        @endforeach
                    </select>
                    @if(!$cycle_id)
                        <div class="form-text text-muted">Sélectionnez un cycle d'abord</div>
                    @endif
                </div>

                {{-- Matière — dépend de la Classe --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">
                        Matière
                        @if($classe_id && $matieres->isEmpty())
                            <span class="text-danger small">(aucune matière)</span>
                        @endif
                    </label>
                    <select
                        wire:model.live="matiere_id"
                        class="form-select form-select-sm"
                        @if(!$classe_id) disabled @endif>
                        <option value="">— Toutes —</option>
                        @foreach ($matieres as $matiere)
                            <option value="{{ $matiere->id }}">
                                {{ $matiere->nom }}
                            </option>
                        @endforeach
                    </select>
                    @if(!$classe_id)
                        <div class="form-text text-muted">Sélectionnez une classe d'abord</div>
                    @endif
                </div>

            </div>

            {{-- Résumé filtres actifs --}}
            @if($annee_id || $trimestre_id || $cycle_id || $classe_id || $matiere_id)
                <div class="mt-3 d-flex flex-wrap gap-2 align-items-center">
                    <span class="text-muted small fw-semibold">Filtres actifs :</span>

                    @if($annee_id)
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle">
                            📅 {{ $annees->firstWhere('id', $annee_id)?->nom }}
                            <button wire:click="$set('annee_id', null)"
                                class="btn-close btn-close-sm ms-1" style="font-size:.6rem;"></button>
                        </span>
                    @endif

                    @if($trimestre_id)
                        <span class="badge bg-info bg-opacity-10 text-info border border-info-subtle">
                            🗓️ {{ $trimestres->firstWhere('id', $trimestre_id)?->nom }}
                            <button wire:click="$set('trimestre_id', null)"
                                class="btn-close btn-close-sm ms-1" style="font-size:.6rem;"></button>
                        </span>
                    @endif

                    @if($cycle_id)
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle">
                            🔄 {{ $cycles->firstWhere('id', $cycle_id)?->nom }}
                            <button wire:click="resetFiltres"
                                class="btn-close btn-close-sm ms-1" style="font-size:.6rem;"></button>
                        </span>
                    @endif

                    @if($classe_id)
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle">
                            🏫 {{ $classes->firstWhere('id', $classe_id)?->nom }}
                            <button wire:click="$set('classe_id', null); $set('matiere_id', null)"
                                class="btn-close btn-close-sm ms-1" style="font-size:.6rem;"></button>
                        </span>
                    @endif

                    @if($matiere_id)
                        <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle">
                            📚 {{ $matieres->firstWhere('id', $matiere_id)?->nom }}
                            <button wire:click="$set('matiere_id', null)"
                                class="btn-close btn-close-sm ms-1" style="font-size:.6rem;"></button>
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         INDICATEUR CHARGEMENT
    ══════════════════════════════════════════════════════════ --}}
    <div wire:loading class="text-center py-2 mb-2">
        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
        <span class="text-muted ms-2 small">Chargement...</span>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         COMPTEUR
    ══════════════════════════════════════════════════════════ --}}
    <p class="text-muted small mb-2" wire:loading.class="opacity-50">
        <i class="bi bi-card-list me-1"></i>
        <strong>{{ $notes->total() }}</strong> note(s) trouvée(s)
        @if($notes->total() > 25)
            — page {{ $notes->currentPage() }}/{{ $notes->lastPage() }}
        @endif
    </p>

    {{-- ══════════════════════════════════════════════════════════
         TABLEAU
    ══════════════════════════════════════════════════════════ --}}
    <div class="card shadow-sm border-0 rounded" wire:loading.class="opacity-50">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0 bg-white text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th class="text-start">Élève</th>
                            <th>Classe</th>
                            <th>Matière</th>
                            <th>Interro</th>
                            <th>Devoir 1</th>
                            <th>Devoir 2</th>
                            <th>Moyenne</th>
                            <th>Appréciation</th>
                            <th>Trimestre</th>
                            <th>Année</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($notes as $note)
                            <tr>
                                <td class="text-muted small">{{ $note->id }}</td>
                                <td class="text-start">
                                    {{ optional($note->inscription->eleve)->nom ?? '—' }}
                                    {{ optional($note->inscription->eleve)->prenom ?? '' }}
                                </td>
                                <td>
                                    {{ optional($note->inscription->classe)->nom
                                       ?? optional($note->classe)->nom
                                       ?? '—' }}
                                </td>
                                <td>{{ optional($note->matiere)->nom ?? '—' }}</td>
                                <td>{{ $note->moyenne_interro ?? '—' }}</td>
                                <td>{{ $note->devoir1 ?? '—' }}</td>
                                <td>{{ $note->devoir2 ?? '—' }}</td>
                                <td><strong>{{ $note->moyenne_matiere ?? '—' }}</strong></td>
                                <td>
                                    @php
                                        $appr  = $note->appreciation;
                                        $badge = match(true) {
                                            in_array($appr, ['Excellent', 'Très bien'])                           => 'success',
                                            in_array($appr, ['Bien', 'Assez bien'])                               => 'info',
                                            $appr === 'Passable'                                                  => 'warning',
                                            in_array($appr, ['Insuffisant','Faible','Très Faible','Médiocre'])    => 'danger',
                                            default                                                               => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">{{ $appr ?? '—' }}</span>
                                </td>
                                <td>{{ optional($note->trimestre)->nom ?? '—' }}</td>
                                <td>
                                    {{ optional($note->annee)->libelle
                                       ?? optional($note->annee)->nom
                                       ?? '—' }}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('notes.show', $note->id) }}"
                                           class="btn btn-outline-info btn-sm" title="Voir">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <form action="{{ route('notes.destroy', $note->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Supprimer cette note ?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm" title="Supprimer">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-3 d-block mb-2 opacity-50"></i>
                                    Aucune note trouvée pour ces critères
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($notes->hasPages())
                <div class="p-3 d-flex justify-content-center">
                    {{ $notes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>