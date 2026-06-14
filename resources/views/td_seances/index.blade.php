@extends('tableau.neutre')

@section('content')
<button
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container-fluid">

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

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span><i class="bi bi-calendar3"></i> Séances TD</span>
            <a href="{{ route('td-seances.create') }}" class="btn btn-light btn-sm">
                <i class="bi bi-plus-lg"></i> Nouvelle séance
            </a>
        </div>

        {{-- Filtres --}}
        <div class="card-body border-bottom"
             x-data="cycleClasses({{ $classes->groupBy('cycle_id')->toJson() }}, '{{ request('cycle_id') }}')">

            <form method="GET" action="{{ route('td-seances.index') }}" class="row g-2 align-items-end">

                {{-- Année --}}
                <div class="col-md-2">
                    <label class="form-label mb-1 text-muted small">Année</label>
                    <select name="annee_id" class="form-select form-select-sm">
                        <option value="">Toutes les années</option>
                        @foreach($annees as $annee)
                            <option value="{{ $annee->id }}"
                                {{ $anneeId == $annee->id ? 'selected' : '' }}>
                                {{ $annee->libelle ?? $annee->nom ?? $annee->id }}
                                @if($annee->en_cours) ✅ @endif
                            </option>
                        @endforeach
                    </select>
                    @if($anneeEnCours)
                        <div class="form-text text-success" style="font-size: 11px;">
                            <i class="bi bi-check-circle"></i>
                            {{ $anneeEnCours->libelle ?? $anneeEnCours->nom }}
                        </div>
                    @endif
                </div>

                {{-- Cycle --}}
                <div class="col-md-2">
                    <label class="form-label mb-1 text-muted small">Cycle</label>
                    <select name="cycle_id" class="form-select form-select-sm"
                            x-model="cycleId"
                            @change="filtrerClasses()">
                        <option value="">Tous les cycles</option>
                        @foreach($cycles as $cycle)
                            <option value="{{ $cycle->id }}">
                                {{ $cycle->nom ?? $cycle->libelle ?? $cycle->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Classe --}}
                <div class="col-md-2">
                    <label class="form-label mb-1 text-muted small">Classe</label>
                    <select name="classe_id" class="form-select form-select-sm">
                        <option value="">Toutes les classes</option>

                        {{-- Classes filtrées par Alpine si cycle sélectionné --}}
                        <template x-if="cycleId">
                            <template x-for="classe in classesFiltrees" :key="classe.id">
                                <option :value="classe.id"
                                        :selected="classe.id == {{ request('classe_id', 'null') }}">
                                    <span x-text="classe.niveau"></span>
                                </option>
                            </template>
                        </template>

                        {{-- Toutes les classes si aucun cycle --}}
                        <template x-if="!cycleId">
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}"
                                    {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                                    {{ $classe->niveau }}
                                </option>
                            @endforeach
                        </template>
                    </select>
                </div>

                {{-- Date --}}
                <div class="col-md-2">
                    <label class="form-label mb-1 text-muted small">Date</label>
                    <input type="date" name="date" class="form-control form-control-sm"
                           value="{{ request('date') }}">
                </div>

                {{-- Boutons --}}
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success btn-sm w-100">
                        <i class="bi bi-funnel"></i> Filtrer
                    </button>
                    <a href="{{ route('td-seances.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-x-lg"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        {{-- Tableau --}}
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Année</th>
                        <th>Classe</th>
                        <th>Thème</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($seances as $seance)
                        <tr>
                            <td class="text-muted small">{{ $seance->id }}</td>
                            <td>{{ $seance->date->format('d/m/Y') }}</td>
                            <td>{{ $seance->annee->libelle ?? $seance->annee->nom ?? '—' }}</td>
                            <td>{{ $seance->classe->niveau ?? '—' }}</td>
                            <td>{{ $seance->libelle ?? '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('td-seances.show', $seance) }}"
                                   class="btn btn-sm btn-outline-info" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('td-seances.edit', $seance) }}"
                                   class="btn btn-sm btn-outline-warning" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('td-seances.destroy', $seance) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer cette séance ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                                Aucune séance trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($seances->hasPages())
            <div class="card-footer">
                {{ $seances->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function cycleClasses(toutesLesClasses, cycleIdInitial) {
    return {
        cycleId: cycleIdInitial,
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