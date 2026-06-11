@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">💰 Frais par élève</h5>
        </div>

        <div class="card-body">

            {{-- ✅ Message --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- 🔍 Recherche --}}
            <form method="GET" class="mb-4">
                <div class="row g-3">

                    {{-- Année --}}
                    <div class="col-md-3">
                        <label class="form-label">Année</label>
                        <select name="annee_id" class="form-select">
                            <option value="">— Choisir Une Année —</option>
                            @foreach($annees as $annee)
                                <option value="{{ $annee->id }}"
                                    {{ request('annee_id') == $annee->id ? 'selected' : '' }}>
                                    {{ $annee->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Cycle --}}
                    <div class="col-md-3">
                        <label class="form-label">Cycle</label>
                        <select id="cycle" name="cycle_id" class="form-select">
                            <option value="">-- Choisir un cycle --</option>
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
                        <label class="form-label">Classe</label>
                        <select id="classe" name="classe_id" class="form-select">
                            <option value="">-- Choisir une classe --</option>
                        </select>
                    </div>

                    {{-- Élève --}}
                    <div class="col-md-3">
                        <label class="form-label">Élève</label>
                        <select name="eleve_id" id="eleve_id" class="form-select">
                            <option value="">— Choisir Un Élève —</option>
                        </select>
                    </div>

                </div>

                <div class="mt-3 text-end">
                    <button class="btn btn-primary">🔍 Rechercher</button>
                    <a href="{{ route('inscription-frais.index') }}"
                       class="btn btn-outline-secondary">Réinitialiser</a>
                </div>
            </form>

            {{-- 📋 Tableau --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Élève</th>
                            <th>Classe</th>
                            <th>Frais</th>
                            <th>Montant</th>
                            <th>Payé</th>
                            <th>Reste</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($inscriptionFrais as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->inscription->eleve->nom ?? '' }} {{ $item->inscription->eleve->prenom ?? '' }}</td>
                                <td>{{ $item->inscription->classe->nom ?? '—' }}</td>
                                <td>{{ $item->frais->nom ?? '—' }}</td>
                                <td>{{ number_format($item->montant_frais, 0, ',', ' ') }} F</td>
                                <td>{{ number_format($item->montant_paye, 0, ',', ' ') }} F</td>
                                <td>{{ number_format($item->reste, 0, ',', ' ') }} F</td>
                                <td>
                                    @if($item->statut === 'soldé')
                                        <span class="badge bg-success">Soldé</span>
                                    @elseif($item->statut === 'partiellement_payé')
                                        <span class="badge bg-warning text-dark">Partiel</span>
                                    @else
                                        <span class="badge bg-danger">Non payé</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('inscription-frais.show', $item->id)}}"
                                       class="btn btn-sm btn-info text-white">👁️</a>
                                    <a href="{{ route('inscription-frais.edit', $item->id)}}"
                                       class="btn btn-sm btn-warning">✏️</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">
                                    Aucun frais trouvé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $inscriptionFrais->withQueryString()->links() }}

        </div>
    </div>
</div>

{{-- ✅ SCRIPT CORRIGÉ --}}
<script>
const cycle = document.getElementById('cycle');
const classe = document.getElementById('classe');
const eleve = document.getElementById('eleve_id');

// 🔁 Charger classes par cycle
cycle.addEventListener('change', function () {
    let cycleId = this.value;

    classe.innerHTML = '<option>Chargement...</option>';
    eleve.innerHTML = '<option value="">— Choisir Un Élève —</option>';

    if (!cycleId) {
        classe.innerHTML = '<option value="">-- Choisir une classe --</option>';
        return;
    }

    fetch(`/cycles/${cycleId}/classes`)
        .then(res => res.json())
        .then(data => {
            classe.innerHTML = '<option value="">-- Choisir une classe --</option>';

            data.forEach(c => {
                classe.innerHTML += `<option value="${c.id}">${c.nom}</option>`;
            });
        });
});

// 🔁 Charger élèves par classe
classe.addEventListener('change', function () {
    let classeId = this.value;

    eleve.innerHTML = '<option>Chargement...</option>';

    if (!classeId) {
        eleve.innerHTML = '<option value="">— Choisir Un Élève —</option>';
        return;
    }

    fetch(`/ajax/eleves-par-classe/${classeId}`)
        .then(res => res.json())
        .then(data => {
            eleve.innerHTML = '<option value="">— Tous —</option>';

            data.forEach(e => {
                eleve.innerHTML += `<option value="${e.id}">${e.nom} ${e.prenom ?? ''}</option>`;
            });
        });
});
</script>

@endsection