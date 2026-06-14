<div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning fw-bold">
            ENREGISTREMENT DES PRESENCES AUX TD
        </div>
        <div class="card-body">
            <div class="row g-3">

                {{-- Année --}}
                <div class="col-md-2">
                    <label class="form-label">Année</label>
                    <select wire:model.live="annee_id" class="form-select">
                        <option value="">Choisir</option>
                        @foreach($annees as $annee)
                            <option value="{{ $annee->id }}">
                                {{ $annee->libelle ?? $annee->nom ?? $annee->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Cycle --}}
                <div class="col-md-2">
                    <label class="form-label">Cycle</label>
                    <select wire:model.live="cycle_id" class="form-select">
                        <option value="">Choisir</option>
                        @foreach($cycles as $cycle)
                            <option value="{{ $cycle->id }}">
                                {{ $cycle->nom ?? $cycle->libelle ?? $cycle->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Classe --}}
                <div class="col-md-2">
                    <label class="form-label">Classe</label>
                    <select wire:model.live="classe_id" class="form-select">
                        <option value="">Choisir</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}">
                                {{ $classe->niveau }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Séance --}}
                <div class="col-md-4">
                    <label class="form-label">Séance</label>
                    <select wire:model.live="seance_id" class="form-select">
                        <option value="">Choisir</option>
                        @foreach($seances as $seance)
                            <option wire:key="seance-{{ $seance->id }}" value="{{ $seance->id }}">
                                {{ \Carbon\Carbon::parse($seance->date)->format('d/m/Y') }}
                                @if($seance->libelle) — {{ $seance->libelle }} @endif
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>
    </div>

    {{-- Liste des élèves --}}
    @if(count($eleves))
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    Liste des élèves
                    <span class="badge bg-secondary ms-2">{{ count($eleves) }} élèves</span>
                    <span class="badge bg-success ms-1">
                        {{ collect($presences)->filter()->count() }} présents
                    </span>
                </div>
                <button wire:click="toggleTous"
                        class="btn btn-sm {{ $tousSelectionnes ? 'btn-danger' : 'btn-outline-success' }}">
                    {{ $tousSelectionnes ? 'Tout désélectionner' : 'Tout sélectionner' }}
                </button>
            </div>

            <div class="card-body p-0">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Élève</th>
                            <th class="text-center" style="width: 100px;">Présent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eleves as $insc)
                            <tr wire:key="eleve-row-{{ $insc->eleve_id }}"
                                class="{{ !empty($presences[$insc->eleve_id]) ? 'table-success' : '' }}">
                                <td>{{ $insc->nom }} {{ $insc->prenom }}</td>
                                <td class="text-center">
                                    <input type="checkbox"
                                           wire:model="presences.{{ $insc->eleve_id }}"
                                           class="form-check-input fs-5">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Bouton fixe en bas --}}
            <div class="card-footer d-flex justify-content-between align-items-center">
    <span class="text-muted small">
        {{ collect($presences)->filter()->count() }} /
        {{ count($eleves) }} présents
    </span>
    <div class="d-flex gap-2">
        {{-- Voir les présences en base --}}
        <a href="{{ route('td-presences.show', $seance_id) }}"
           class="btn btn-outline-info btn-sm"
           target="_blank">
            <i class="bi bi-eye"></i> Voir les présences
        </a>

        {{-- Enregistrer --}}
        <button wire:click="save"
                wire:loading.attr="disabled"
                class="btn btn-primary">
            <span wire:loading wire:target="save"
                  class="spinner-border spinner-border-sm me-1"></span>
            <span wire:loading.remove wire:target="save">💾</span>
            Enregistrer les présences
        </button>
    </div>
</div>
        </div>

    @elseif($classe_id && $seance_id)
        <div class="alert alert-info">
            Aucun élève inscrit dans cette classe pour cette année.
        </div>
    @endif
</div>