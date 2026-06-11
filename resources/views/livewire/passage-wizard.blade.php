<div class="container py-4">

    {{-- PROGRESS STEP --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Passage des élèves</h5>

                <div class="d-flex align-items-center gap-3">

                    {{-- DROPDOWN ANNULATION --}}
                    @if(!empty($historique_passages))
                        <div class="dropdown">

                            <button class="btn btn-sm btn-outline-danger dropdown-toggle"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                ↩ Annuler un passage
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                @foreach($historique_passages as $i => $passage)
                                    <li>
                                        <button class="dropdown-item text-danger"
                                                wire:click="annulerPassage({{ $i }})"
                                                wire:confirm="Annuler le passage : {{ $passage['label'] }} ?">
                                            {{ $passage['label'] }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>

                        </div>
                    @endif

                    <span class="badge bg-primary">
                        Étape {{ $step }} / 5
                    </span>

                </div>
            </div>

            <div class="progress" style="height: 6px;">
                <div class="progress-bar"
                     style="width: {{ ($step / 5) * 100 }}%"></div>
            </div>

        </div>
    </div>

    {{-- ALERTS --}}
    @if(session()->has('error'))
        <div class="alert alert-danger shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    @if(session()->has('success'))
        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>
    @endif


    {{-- ================= STEP 1 ================= --}}
    @if($step == 1)

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <h5 class="mb-3">📚 1. Paramètres de passage</h5>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Année source</label>
                        <select class="form-select"
                                wire:model.live="annee_source_id">
                            <option value="">-- Année source --</option>
                            @foreach($annees as $annee)
                                <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Année d'accueil</label>
                        <select class="form-select"
                                wire:model.live="annee_accueil_id">
                            <option value="">-- Année d'accueil --</option>
                            @foreach($annees as $annee)
                                <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Cycle</label>
                        <select class="form-select"
                                wire:model.live="cycle_id">
                            <option value="">-- Cycle --</option>
                            @foreach($cycles as $cycle)
                                <option value="{{ $cycle->id }}">{{ $cycle->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Classe</label>
                        <select class="form-select"
                                wire:model.live="classe_id">
                            <option value="">-- Classe --</option>
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="mt-4 text-end">
                    <button class="btn btn-primary px-4"
                            wire:click="loadEleves"
                            @disabled(!$cycle_id || !$classe_id || !$annee_source_id || !$annee_accueil_id)>
                        Continuer →
                    </button>
                </div>

            </div>
        </div>

    @endif


    {{-- ================= STEP 2 ================= --}}
    @if($step == 2)

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h5 class="mb-0">👨‍🎓 Sélection des élèves</h5>

                    <span class="badge bg-info fs-2 px-5 py-3 shadow rounded-pill">
                        {{ count($selected_eleves) }} / {{ $eleves->count() }}
                    </span>

                </div>

                {{-- SELECT ALL --}}
                @if($eleves->count())
                    <div class="form-check mb-3 border-bottom pb-2">
                        <input class="form-check-input"
                               type="checkbox"
                               wire:model.live="selectAll">
                        <label class="form-check-label fw-bold">
                            Sélectionner tous les élèves
                        </label>
                    </div>
                @endif

                {{-- LISTE --}}
                <div class="list-group">

                    @forelse($eleves as $insc)

                        <label class="list-group-item d-flex align-items-center gap-2">

                            <input type="checkbox"
                                   class="form-check-input"
                                   wire:model.live="selected_eleves"
                                   value="{{ $insc->id }}">

                            <div>
                                <strong>
                                    {{ $insc->eleve->nom }}
                                    {{ $insc->eleve->prenom }}
                                </strong>
                            </div>

                        </label>

                    @empty

                        <div class="alert alert-warning">
                            Aucun élève trouvé
                        </div>

                    @endforelse

                </div>

                <div class="mt-4 text-end">
                    <button class="btn btn-success px-4"
                            wire:click="loadClassesAccueil"
                            @disabled(count($selected_eleves) === 0)>
                        Envoyer →
                    </button>
                </div>

            </div>
        </div>

    @endif


    {{-- ================= STEP 3 ================= --}}
    @if($step == 3)

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <h5 class="mb-3">🏫 Classe d'accueil</h5>

                <select class="form-select"
                        wire:model.live="classe_accueil_id">
                    <option value="">-- Choisir une classe --</option>
                    @foreach($classes_accueil as $c)
                        <option value="{{ $c->id }}">{{ $c->nom }}</option>
                    @endforeach
                </select>

                <div class="mt-4 text-end">
                    <button class="btn btn-warning px-4"
                            wire:click="confirm"
                            @disabled(!$classe_accueil_id)>
                        Valider →
                    </button>
                </div>

            </div>
        </div>

    @endif


    {{-- ================= STEP 4 ================= --}}
    @if($step == 4)

        <div class="card shadow border-0 text-center p-4">

            <h4 class="mb-3">⚠️ Confirmation</h4>

            <p class="text-muted">
                Êtes-vous sûr d'effectuer le passage des élèves sélectionnés ?
            </p>

            <div class="d-flex justify-content-center gap-3 mt-3">

                <button class="btn btn-outline-secondary px-4"
                        wire:click="$set('step', 3)">
                    Non
                </button>

                <button class="btn btn-success px-4"
                        wire:click="executePassage">
                    Oui, valider
                </button>

            </div>

        </div>

    @endif


    {{-- ================= STEP 5 ================= --}}
    @if($step == 5)

        <div class="card shadow border-0 text-center p-4">

            <div class="text-success fs-1 mb-2">✔</div>

            <h4 class="text-success">Passage effectué avec succès</h4>

            <button class="btn btn-primary mt-3"
                    wire:click="$set('step', 1)">
                Nouveau passage
            </button>

        </div>

    @endif

</div>