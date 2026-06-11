@extends('tableau.neutre')

@section('content')

<div class="container py-4">

    <h3 class="mb-4">Gestion des frais par année et classe</h3>

    {{-- MESSAGE SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- FILTRES --}}
    <form method="GET" class="row g-2 mb-4">

        <div class="col-md-4">
            <select name="annee_id" class="form-select form-select-sm">
                <option value="">-- Année --</option>
                @foreach($annees as $annee)
                    <option value="{{ $annee->id }}"
                        {{ request('annee_id') == $annee->id ? 'selected' : '' }}>
                        {{ $annee->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <select id="cycle_id" name="cycle_id" class="form-select form-select-sm">
    <option value="">-- Cycle --</option>
    @foreach($cycles as $cycle)
        <option value="{{ $cycle->id }}">
            {{ $cycle->nom }}
        </option>
    @endforeach
</select>
<select id="classe_id" name="classe_id" class="form-select form-select-sm">
    <option value="">-- Classe --</option>
</select>

        <div class="col-12 text-end">
            <button class="btn btn-primary btn-sm">🔍 Filtrer</button>
            <a href="{{ route('frais.annee_classe.index') }}"
               class="btn btn-secondary btn-sm">
                🔄 Reset
            </a>
        </div>

    </form>

    {{-- TABLE --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-bordered table-striped mb-0">

                <thead class="table-dark">
                    <tr>
                        <th>Année</th>
                        <th>Classe</th>
                        <th>Frais</th>
                        <th>Montant</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($data as $item)

                        <tr>
                            <td>{{ $item->annee->nom ?? '---' }}</td>
                            <td>{{ $item->classe->nom ?? '---' }}</td>
                            <td>{{ $item->frais->nom ?? '---' }}</td>

                            <td>
                                <form method="POST"
                                      action="{{ route('frais.annee_classe.update', $item->id) }}"
                                      class="d-flex">

                                    @csrf
                                    @method('PUT')

                                    <input type="number"
                                           name="montant"
                                           value="{{ $item->montant }}"
                                           class="form-control form-control-sm me-2"
                                           step="0.01">

                                    <button class="btn btn-primary btn-sm">
                                        ✔
                                    </button>

                                </form>
                            </td>

                            <td class="text-center">
                                {{-- suppression optionnelle --}}
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Aucun résultat trouvé
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>
<script>
    document.getElementById('cycle_id').addEventListener('change', function () {

    let cycleId = this.value;
    let classeSelect = document.getElementById('classe_id');

    classeSelect.innerHTML = '<option>Chargement...</option>';

    if (!cycleId) {
        classeSelect.innerHTML = '<option value="">-- Classe --</option>';
        return;
    }

    fetch(`/cycles/${cycleId}/classes`)
        .then(res => res.json())
        .then(data => {

            classeSelect.innerHTML = '<option value="">-- Classe --</option>';

            data.forEach(classe => {
                classeSelect.innerHTML += `
                    <option value="${classe.id}">
                        ${classe.nom}
                    </option>
                `;
            });
        });
});
</script>

@endsection