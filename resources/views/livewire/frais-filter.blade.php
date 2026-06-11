<div class="container-fluid py-4">

    {{-- EN-TÊTE --}}
    <div class="mb-4">
        <h1 class="h4 fw-bold text-dark">Suivi des Frais Scolaires</h1>
        <p class="text-muted small mb-0">Filtrez par année, cycle, classe et élève.</p>
    </div>

    {{-- ═══════════════════════════════════════════
         FILTRES EN CASCADE
    ═══════════════════════════════════════════ --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <div class="row g-3">

                {{-- Année --}}
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold text-uppercase text-muted">Année scolaire</label>
                    <select wire:model.live="annee_id" class="form-select form-select-sm rounded-2">
                        <option value="">— Année —</option>
                        @foreach($annees as $annee)
                            <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Cycle --}}
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold text-uppercase text-muted">Cycle</label>
                    <select wire:model.live="cycle_id" class="form-select form-select-sm rounded-2" @disabled(!$annee_id)>
                        <option value="">— Cycle —</option>
                        @foreach($cycles as $cycle)
                            <option value="{{ $cycle->id }}">{{ $cycle->nom }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Classe --}}
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold text-uppercase text-muted">Classe</label>
                    <select wire:model.live="classe_id" class="form-select form-select-sm rounded-2" @disabled(!$cycle_id)>
                        <option value="">— Classe —</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Élève --}}
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold text-uppercase text-muted">Élève</label>
                    <select wire:model.live="eleve_id" class="form-select form-select-sm rounded-2" @disabled(!$classe_id)>
                        <option value="">— Élève —</option>
                        @foreach($eleves as $eleve)
                            <option value="{{ $eleve->id }}">{{ $eleve->nom }} {{ $eleve->prenom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div wire:loading class="d-flex align-items-center gap-2 mt-3 text-primary small">
                <div class="spinner-border spinner-border-sm" role="status"></div>
                Chargement…
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         ONGLETS — visibles dès qu'une année est choisie
    ═══════════════════════════════════════════ --}}
    @if($annee_id)
    <ul class="nav nav-tabs mb-0 border-bottom-0">
        <li class="nav-item">
            <button
                wire:click="setOnglet('annee')"
                class="nav-link {{ $onglet === 'annee' ? 'active fw-semibold' : 'text-muted' }}"
            >
                <i class="bi bi-calendar3 me-1"></i>Par année
            </button>
        </li>
        <li class="nav-item">
            <button
                wire:click="setOnglet('classe')"
                class="nav-link {{ $onglet === 'classe' ? 'active fw-semibold' : 'text-muted' }} {{ !$classe_id ? 'disabled opacity-50' : '' }}"
            >
                <i class="bi bi-people me-1"></i>Par classe
            </button>
        </li>
        <li class="nav-item">
            <button
                wire:click="setOnglet('eleve')"
                class="nav-link {{ $onglet === 'eleve' ? 'active fw-semibold' : 'text-muted' }} {{ !$eleve_id ? 'disabled opacity-50' : '' }}"
            >
                <i class="bi bi-person me-1"></i>Par élève
            </button>
        </li>
    </ul>

    <div class="card border-0 shadow-sm rounded-3 rounded-top-start-0 mb-4">
        <div class="card-body p-4">

            {{-- ── ONGLET ANNÉE ─────────────────────────────────────── --}}
            @if($onglet === 'annee')
                @if(count($frais_annee) > 0)
                    @include('livewire.partials.frais-stats', [
                        'frais'  => $frais_annee,
                        'totaux' => $totaux_annee,
                        'titre'  => 'Récapitulatif de l\'année scolaire',
                        'badge'  => 'Tous cycles confondus',
                        'show_nb_eleves' => true,
                    ])
                @else
                    @include('livewire.partials.frais-vide', ['message' => 'Aucune donnée pour cette année.'])
                @endif

            {{-- ── ONGLET CLASSE ────────────────────────────────────── --}}
            @elseif($onglet === 'classe')
                @if(!$classe_id)
                    @include('livewire.partials.frais-vide', ['message' => 'Sélectionnez une classe pour voir ses statistiques.'])
                @elseif(count($frais_classe) > 0)
                    @include('livewire.partials.frais-stats', [
                        'frais'  => $frais_classe,
                        'totaux' => $totaux_classe,
                        'titre'  => 'Récapitulatif de la classe',
                        'badge'  => 'Tous élèves confondus',
                        'show_nb_eleves' => true,
                    ])
                @else
                    @include('livewire.partials.frais-vide', ['message' => 'Aucune donnée pour cette classe.'])
                @endif

            {{-- ── ONGLET ÉLÈVE ─────────────────────────────────────── --}}
            @elseif($onglet === 'eleve')
                @if(!$eleve_id)
                    @include('livewire.partials.frais-vide', ['message' => 'Sélectionnez un élève pour voir ses frais.'])
                @elseif(count($frais_eleve) > 0)
                    @include('livewire.partials.frais-stats', [
                        'frais'  => $frais_eleve,
                        'totaux' => $totaux_eleve,
                        'titre'  => 'Frais de l\'élève',
                        'badge'  => null,
                        'show_nb_eleves' => false,
                    ])
                @else
                    @include('livewire.partials.frais-vide', ['message' => 'Aucun frais enregistré pour cet élève.'])
                @endif
            @endif

        </div>
    </div>

    @else
    {{-- État initial --}}
    <div class="card border-0 rounded-3" style="border: 1.5px dashed #dee2e6 !important;">
        <div class="card-body text-center py-5">
            <i class="bi bi-funnel fs-1 text-muted opacity-25 d-block mb-3"></i>
            <p class="text-muted small mb-0">Commencez par sélectionner une année scolaire.</p>
        </div>
    </div>
    @endif

</div>