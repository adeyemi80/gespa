@extends('tableau.neutre')

@section('content')

<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>

<div class="container py-5" style="background-color: #f8f9fa; min-height: 100vh;">

    <div class="card shadow mb-4">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                📋 Liste des Frais Scolaires
            </h5>

            <a href="{{ route('frais.export.pdf') }}"
               class="btn btn-light btn-sm">
                📥 Télécharger PDF
            </a>

        </div>

        <div class="card-body">

            {{-- Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            {{-- FILTRE --}}
<form method="GET" action="{{ route('frais.index') }}" class="row g-3 align-items-end mb-4">

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
        <a href="{{ route('frais.index') }}" class="btn btn-secondary">🔄 Réinitialiser</a>
    </div>

</form>

            {{-- EXPORT PDF --}}
            <div class="mb-3">

                <a href="{{ route('frais.export.pdf', request()->query()) }}"
                   class="btn btn-outline-danger">

                    📥 Exporter PDF

                </a>

            </div>

            {{-- TABLEAU --}}
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle text-center">

                    <thead class="table-primary">

                        <tr>

                            <th>ID</th>
                            <th>📚 Classe(s)</th>
                            <th>📅 Année(s)</th>
                            <th>📌 Nom du Frais</th>
                            <th>📝 Description</th>
                            <th>💰 Montant</th>
                            <th>📆 Échéances</th>
                            <th>⚙️ Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($frais as $f)

                            <tr>

                                {{-- ID --}}
                                <td>
                                    {{ $f->id }}
                                </td>

                                {{-- CLASSES --}}
                                <td>

                                    @forelse($f->anneeClasseFrais as $pivot)

                                        @if($pivot->classe)

                                            <span class="badge bg-primary">

                                                {{ $pivot->classe->nom }}

                                            </span>

                                        @endif

                                    @empty

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endforelse

                                </td>

                                {{-- ANNEES --}}
                                <td>

                                    @forelse($f->anneeClasseFrais as $pivot)

                                        @if($pivot->annee)

                                            <span class="badge bg-success">

                                                {{ $pivot->annee->nom }}

                                            </span>

                                        @endif

                                    @empty

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endforelse

                                </td>

                                {{-- NOM --}}
                                <td>

                                    {{ $f->nom }}

                                </td>

                                {{-- DESCRIPTION --}}
                                <td>

                                    {{ $f->description }}

                                </td>

                                {{-- MONTANT --}}
                                <td>

                                    @php
                                        $montant = $f->anneeClasseFrais->first()?->montant;
                                    @endphp

                                    <strong>

                                        {{ number_format($montant ?? 0, 2) }} F

                                    </strong>

                                </td>

                                {{-- ÉCHÉANCES --}}
                                <td class="text-start">

                                    <ul class="mb-0 ps-3">

                                        @forelse($f->echeances as $e)

                                            <li>

                                                <strong>
                                                    {{ $e->nom }}
                                                </strong>

                                                :

                                                {{ number_format($e->montant, 2) }} F

                                                (

                                                {{ $e->date_limite
                                                    ? \Carbon\Carbon::parse($e->date_limite)->format('d/m/Y')
                                                    : '—' }}

                                                )

                                            </li>

                                        @empty

                                            <li class="text-muted">
                                                Aucune échéance
                                            </li>

                                        @endforelse

                                    </ul>

                                </td>

                                {{-- ACTIONS --}}
                                <td>

                                    <div class="d-flex justify-content-center gap-1">

                                        {{-- Voir --}}
                                        <a href="{{ route('frais.show', $f->id) }}"
                                           class="btn btn-sm btn-info">

                                            👁

                                        </a>

                                        {{-- ADMIN --}}
                                        @if(auth()->check() && auth()->user()?->role === 'admin')

                                            {{-- Modifier --}}
                                            <a href="{{ route('frais.edit', $f->id) }}"
                                               class="btn btn-sm btn-warning">

                                                ✏️

                                            </a>

                                            {{-- Supprimer --}}
                                            <form action="{{ route('frais.destroy', $f->id) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Voulez-vous vraiment supprimer ce frais ?');">

                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-sm btn-danger">

                                                    🗑

                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8" class="text-muted">

                                    Aucun frais trouvé.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

{{-- SCRIPT --}}
<script>
    const savedCycleId  = "{{ request('cycle_id') }}";
    const savedClasseId = "{{ request('classe_id') }}";

    const cycleSelect  = document.getElementById('cycle');
    const classeSelect = document.getElementById('classe');

    function loadClasses(cycleId, preselectClasseId = null) {
        if (!cycleId) {
            classeSelect.innerHTML = '<option value="">-- Toutes les classes --</option>';
            return;
        }

        classeSelect.innerHTML = '<option value="">Chargement...</option>';

        fetch(`/cycles/${cycleId}/classes`)
            .then(r => r.json())
            .then(data => {
                classeSelect.innerHTML = '<option value="">-- Toutes les classes --</option>';
                data.forEach(classe => {
                    const selected = preselectClasseId == classe.id ? 'selected' : '';
                    classeSelect.innerHTML +=
                        `<option value="${classe.id}" ${selected}>${classe.nom}</option>`;
                });
            });
    }

    // Au changement de cycle par l'utilisateur
    cycleSelect.addEventListener('change', function () {
        loadClasses(this.value);
    });

    // Au chargement de la page : restaurer l'état des filtres
    if (savedCycleId) {
        loadClasses(savedCycleId, savedClasseId);
    }
</script>

@endsection