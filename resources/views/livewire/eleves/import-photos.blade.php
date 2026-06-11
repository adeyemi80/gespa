<div>
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                Importation des photos élèves
            </h5>
        </div>

        <div class="card-body">
            {{-- Message de succès --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Erreurs d'importation --}}
            @if (isset($erreurs) && count($erreurs) > 0)
                <div class="alert alert-warning">
                    <strong>Attention :</strong> des erreurs ont eu lieu pendant l'importation.
                    <ul class="mb-0 mt-2">
                        @foreach ($erreurs as $erreur)
                            <li>{{ $erreur }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-3">
                {{-- Cycle --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">
                        Cycle
                    </label>

                    <select
                        wire:model.live="cycle_id"
                        class="form-select"
                    >
                        <option value="">
                            Sélectionner un cycle
                        </option>

                        @foreach ($cycles as $cycle)
                            <option value="{{ $cycle->id }}">
                                {{ $cycle->nom }}
                            </option>
                        @endforeach
                    </select>

                    @error('cycle_id')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Classe --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">
                        Classe
                    </label>

                    <select
                        wire:model="classe_id"
                        class="form-select"
                        @disabled(empty($cycle_id))
                    >
                        <option value="">
                            Sélectionner une classe
                        </option>

                        @foreach ($classes as $classe)
                            <option value="{{ $classe->id }}">
                                {{ $classe->nom }}
                            </option>
                        @endforeach
                    </select>

                    @error('classe_id')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Photos --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">
                        Photos
                    </label>

                    <input
                        type="file"
                        wire:model.live="photos"
                        multiple
                        class="form-control"
                        accept=".jpg,.jpeg,.png"
                    >

                    <div wire:loading wire:target="photos" class="mt-1">
                        <small class="text-muted">
                            <span class="spinner-border spinner-border-sm"></span>
                            Chargement des aperçus...
                        </small>
                    </div>

                    @error('photos.*')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Prévisualisation des photos --}}
            @if (count($preview))
                <div class="row mt-3">
                    @foreach ($preview as $photo)
                        <div class="col-md-2 mb-3">
                            <div class="card shadow-sm">
                                <img
                                    src="{{ $photo['url'] }}"
                                    class="card-img-top"
                                    style="height: 180px; object-fit: cover;"
                                    alt="{{ $photo['nom'] }}"
                                >
                                <div class="card-body p-2">
                                    <small class="text-truncate d-block text-muted">
                                        {{ $photo['nom'] }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Bouton d'importation --}}
            <div class="mt-3">
                <button
                    wire:click="importer"
                    class="btn btn-success"
                    wire:loading.attr="disabled"
                    wire:target="importer"
                >
                    <span wire:loading.remove wire:target="importer">
                        <i class="bi bi-upload me-1"></i>
                        Importer
                    </span>
                    <span wire:loading wire:target="importer">
                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                        Importation...
                    </span>
                </button>
            </div>

            {{-- Barre de progression pendant l'import --}}
            <div
                wire:loading
                wire:target="importer"
                class="mt-3"
            >
                <div class="progress">
                    <div
                        class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                        style="width: 100%"
                    >
                        Importation en cours...
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Rapport d'importation --}}
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="alert alert-info mb-0 text-center">
                <div class="fs-4 fw-bold">{{ $rapport['traites'] }}</div>
                <small>Traitées</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="alert alert-success mb-0 text-center">
                <div class="fs-4 fw-bold">{{ $rapport['importes'] }}</div>
                <small>Importées</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="alert alert-warning mb-0 text-center">
                <div class="fs-4 fw-bold">{{ $rapport['remplaces'] }}</div>
                <small>Remplacées</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="alert alert-danger mb-0 text-center">
                <div class="fs-4 fw-bold">{{ $rapport['rejetes'] }}</div>
                <small>Rejetées</small>
            </div>
        </div>
    </div>
</div>