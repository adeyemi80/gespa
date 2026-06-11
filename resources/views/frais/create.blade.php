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
            <h5 class="mb-0">➕ Nouveau Frais avec Échéances</h5>
        </div>

        <div class="card-body">

            {{-- ✅ Message --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
               {{-- Messages erreurs --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4">
                    <h6>⚠️ Veuillez corriger les erreurs :</h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('frais.store') }}">
                @csrf

                {{-- 🎓 Infos principales --}}
                <div class="row g-3 mb-3">

                    {{-- Nom --}}
                    <div class="col-md-6">
                        <label class="form-label">Nom du frais</label>
                       <select type="text"
           name="nom"
           value="{{ old('nom', $frais->nom ?? '') }}"
           class="form-control form-control-sm {{ $errors->has('nom') ? 'is-invalid' : '' }}" required>
                            <option value="">-- Choisir le Nom du Frais --</option>
                            <option value="scolarite">Scolarité</option>
                            <option value="frais_inscription">Frais d'inscription</option>
                            <option value="frais_reinscription">Frais de Réinscription</option>
                            <option value="uniforme">Uniforme</option>
                            <option value="tenue de sport">Tenue de sport</option>
                            <option value="td annuel">TD par année</option>
                            <option value="td mensuel">TD par mois</option>
                            <option value="td seance">TD par séance</option>
                            <option value="lacoste">Lacoste uniforme</option>
                            <option value="sejour">Frais de séjour</option>
                            <option value="sortie">Sortie pédagogique</option>
                            <option value="noel">Frais de Noël</option>
                            <option value="transport">Transport</option>
                        </select>
                    </div>

                    {{-- Montant --}}
                    <div class="col-md-6">
                        <label class="form-label">Montant total (FCFA)</label>
                        <input type="number" name="montant" class="form-control" required>
                    </div>

                    {{-- Cycle --}}
                    <div class="col-md-4">
                        <label class="form-label">Cycle</label>
                        <select id="cycle" name="cycle_id" class="form-select">
                            <option value="">-- Choisir un cycle --</option>
                            @foreach($cycles as $cycle)
                                <option value="{{ $cycle->id }}">{{ $cycle->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Classe --}}
                    <div class="col-md-4">
                        <label class="form-label">Classe</label>
                        <select id="classe" name="classe_id" class="form-select" required>
                            <option value="">-- Choisir une classe --</option>
                        </select>
                    </div>

                    {{-- Année --}}
                    <div class="col-md-4">
                        <label class="form-label">Année scolaire</label>
                        <select name="annee_id" class="form-select" required>
                            <option value="">-- Sélectionnez une année --</option>
                            @foreach($annees as $annee)
                                <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label class="form-label">Description (facultative)</label>
                    <input type="text" name="description" class="form-control">
                </div>

                <hr>

                {{-- 📆 Échéances --}}
                <h6 class="text-primary">📆 Échéances</h6>

                <div id="echeances-container">
                    <div class="row mb-3 echeance-item">
                        <div class="col-md-4">
                            <input type="text" name="echeances[0][libelle]" class="form-control" placeholder="1ère tranche" required>
                        </div>
                        <div class="col-md-4">
                            <input type="number" name="echeances[0][montant]" class="form-control" placeholder="Montant" required>
                        </div>
                        <div class="col-md-4">
                            <input type="date" name="echeances[0][date_limite]" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="button" class="btn btn-outline-success" id="add-echeance">
                        ➕ Ajouter une tranche
                    </button>
                </div>

                {{-- Submit --}}
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        💾 Enregistrer
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- ✅ SCRIPT --}}
<script>
const cycle = document.getElementById('cycle');
const classe = document.getElementById('classe');

// 🔁 Charger classes selon cycle
cycle.addEventListener('change', function () {

    let cycleId = this.value;
    classe.innerHTML = '<option>Chargement...</option>';

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


// 🔁 Ajouter échéance
let index = 1;

document.getElementById('add-echeance').addEventListener('click', function () {

    const container = document.getElementById('echeances-container');

    const row = document.createElement('div');
    row.classList.add('row', 'mb-3');

    row.innerHTML = `
        <div class="col-md-4">
            <input type="text" name="echeances[${index}][libelle]" class="form-control" placeholder="Libellé" required>
        </div>
        <div class="col-md-4">
            <input type="number" name="echeances[${index}][montant]" class="form-control" placeholder="Montant" required>
        </div>
        <div class="col-md-4">
            <input type="date" name="echeances[${index}][date_limite]" class="form-control" required>
        </div>
    `;

    container.appendChild(row);
    index++;
});
</script>

@endsection