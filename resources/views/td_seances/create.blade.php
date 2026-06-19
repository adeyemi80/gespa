@extends('tableau.neutre')

@section('content')
<button
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span><i class="bi bi-plus-circle"></i> Nouvelle séance TD</span>
            <a href="{{ route('td-seances.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> LES SEANCES DE TD
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('td-seances.store') }}" method="POST"
                  x-data="cycleClasses({{ $classes->groupBy('cycle_id')->toJson() }})">
                @csrf

                <div class="row g-3">
{{-- Année --}}
<div class="col-md-6">
    <label class="form-label">Année <span class="text-danger">*</span></label>
    <select name="annee_id"
            class="form-select @error('annee_id') is-invalid @enderror">
        <option value="">Choisir une année</option>
        @foreach($annees as $annee)
            <option value="{{ $annee->id }}"
                {{-- Priorité : old() après erreur, sinon année en cours --}}
                {{ old('annee_id', $anneeEnCours?->id) == $annee->id ? 'selected' : '' }}>
                {{ $annee->libelle ?? $annee->nom ?? $annee->id }}
                @if($annee->en_cours)
                    ✅
                @endif
            </option>
        @endforeach
    </select>
    @error('annee_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                    {{-- Cycle --}}
                    <div class="col-md-6">
                        <label class="form-label">Cycle <span class="text-danger">*</span></label>
                        <select class="form-select"
                                x-model="cycleId"
                                @change="filtrerClasses()">
                            <option value="">Choisir un cycle</option>
                            @foreach($cycles as $cycle)
                                <option value="{{ $cycle->id }}">
                                    {{ $cycle->nom ?? $cycle->libelle ?? $cycle->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Classe --}}
                    <div class="col-md-6">
                        <label class="form-label">Classe <span class="text-danger">*</span></label>
                        <select name="classe_id"
                                class="form-select @error('classe_id') is-invalid @enderror"
                                :disabled="classesFiltrees.length === 0">
                            <option value="">
                                <span x-text="cycleId ?"> Choisir d'abord un cycle</span>
                            </option>
                            <template x-for="classe in classesFiltrees" :key="classe.id">
                                <option :value="classe.id"
                                        :selected="classe.id == {{ old('classe_id', 'null') }}">
                                    <span x-text="classe.niveau"></span>
                                </option>
                            </template>
                        </select>
                        @error('classe_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted" x-show="cycleId && classesFiltrees.length === 0">
                            Aucune classe pour ce cycle.
                        </div>
                    </div>

                    {{-- Date --}}
                    <div class="col-md-6">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="date"
                               class="form-control @error('date') is-invalid @enderror"
                               value="{{ old('date', now()->format('Y-m-d')) }}">
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Thème --}}
                    <div class="col-md-12">
                        <label class="form-label">
                            Thème <span class="text-muted small">(optionnel)</span>
                        </label>
                        <input type="text" name="libelle"
                               class="form-control @error('libelle') is-invalid @enderror"
                               placeholder="Ex: MATHS - ANGLAIS"
                               value="{{ old('libelle') }}">
                        @error('libelle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg"></i> Créer la séance
                    </button>
                    <a href="{{ route('td-seances.index') }}" class="btn btn-outline-secondary">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function cycleClasses(toutesLesClasses) {
    return {
        cycleId: '{{ old('cycle_id', '') }}',
        classesFiltrees: [],
        toutesLesClasses: toutesLesClasses,

        init() {
            if (this.cycleId) {
                this.filtrerClasses();
            }
        },

        filtrerClasses() {
            if (!this.cycleId) {
                this.classesFiltrees = [];
                return;
            }
            this.classesFiltrees = this.toutesLesClasses[this.cycleId] ?? [];
        }
    }
}
</script>
@endsection