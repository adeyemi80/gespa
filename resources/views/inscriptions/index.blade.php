@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>

<div class="container py-5" style="background-color: #f8f9fa; min-height: 100vh;">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-primary mb-0">📝 Liste des Inscriptions</h4>
        <a href="{{ route('inscriptions.create') }}" class="btn btn-success">
            ➕ Nouvelle inscription
        </a>
    </div>

    {{-- Alerte succès --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-center mx-auto w-75" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    {{-- FORMULAIRE DE FILTRAGE --}}
    <form method="GET" action="{{ route('inscriptions.index') }}" class="row g-3 align-items-end mb-4">

        {{-- Cycle --}}
        <div class="col-md-3">
            <label class="form-label fw-bold">Cycle</label>
            <select id="cycle" name="cycle_id" class="form-select">
                <option value="">-- Tous les cycles --</option>
                @foreach($cycles as $cycle)
                    <option value="{{ $cycle->id }}"
                        {{ request('cycle_id') == $cycle->id ? 'selected' : '' }}>
                        {{ $cycle->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Classe --}}
        <div class="col-md-3">
            <label class="form-label fw-bold">Classe</label>
            <select id="classe" name="classe_id" class="form-select">
                <option value="">-- Toutes les classes --</option>
                @foreach($classes as $classe)
                    <option value="{{ $classe->id }}"
                        {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                        {{ $classe->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Année --}}
        <div class="col-md-3">
            <label class="form-label fw-bold">Année Scolaire</label>
            <select name="annee_id" class="form-select">
                <option value="">-- Toutes les années --</option>
                @foreach($annees as $annee)
                    <option value="{{ $annee->id }}"
                        {{ request('annee_id') == $annee->id ? 'selected' : '' }}>
                        {{ $annee->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Boutons --}}
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary">🔍 Rechercher</button>
            <a href="{{ route('inscriptions.index') }}" class="btn btn-secondary">🔄 Réinitialiser</a>
        </div>

    </form>

    {{-- TABLEAU --}}
    <div class="card shadow-sm border-0 rounded">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0 bg-white text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>👨‍🎓 Élève</th>
                            <th>🏫 Classe</th>
                            <th>📅 Année scolaire</th>
                            <th>🗓️ Date d'inscription</th>
                            <th>⚙️ Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inscriptions as $inscription)
                            <tr>
                                <td>{{ $inscription->id }}</td>
                                <td>
                                    {{ optional($inscription->eleve)->nom ?? '—' }}
                                    {{ optional($inscription->eleve)->prenom ?? '' }}
                                </td>
                                <td>{{ optional($inscription->classe)->nom ?? '—' }}</td>
                                <td>{{ optional($inscription->annee)->nom ?? '—' }}</td>
                                <td>{{ $inscription->date_inscription }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('inscriptions.show', $inscription) }}"
                                           class="btn btn-outline-info btn-sm" title="Voir">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="{{ route('inscriptions.edit', $inscription) }}"
                                           class="btn btn-outline-warning btn-sm" title="Modifier">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('inscriptions.destroy', $inscription) }}"
                                              method="POST"
                                              onsubmit="return confirm('Supprimer cette inscription ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger btn-sm" title="Supprimer">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-muted py-4">
                                    Aucune inscription trouvée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination avec filtres conservés --}}
            <div class="p-3">
                {{ $inscriptions->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Fermeture automatique alerte --}}
<script>
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) new bootstrap.Alert(alert).close();
    }, 4000);
</script>

{{-- Filtre dynamique cycle → classes --}}
<script>
    const savedCycleId  = "{{ request('cycle_id') }}";
    const savedClasseId = "{{ request('classe_id') }}";

    const cycleSelect  = document.getElementById('cycle');
    const classeSelect = document.getElementById('classe');

    function loadClasses(cycleId, preselectId = null) {
        if (!cycleId) {
            classeSelect.innerHTML = '<option value="">-- Toutes les classes --</option>';
            @foreach($classes as $classe)
                classeSelect.innerHTML +=
                    `<option value="{{ $classe->id }}">{{ $classe->nom }}</option>`;
            @endforeach
            return;
        }

        classeSelect.innerHTML = '<option value="">Chargement...</option>';

        fetch(`/cycles/${cycleId}/classes`)
            .then(r => r.json())
            .then(data => {
                classeSelect.innerHTML =
                    '<option value="">-- Toutes les classes --</option>';
                data.forEach(c => {
                    const selected = preselectId == c.id ? 'selected' : '';
                    classeSelect.innerHTML +=
                        `<option value="${c.id}" ${selected}>${c.nom}</option>`;
                });
            });
    }

    // Changement manuel du cycle
    cycleSelect.addEventListener('change', function () {
        loadClasses(this.value);
    });

    // Restauration au chargement de la page
    if (savedCycleId) {
        loadClasses(savedCycleId, savedClasseId);
    }
</script>

@endsection