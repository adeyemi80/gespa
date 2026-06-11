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
            <h5 class="mb-0">📥 Importation des Élèves par Classe et Année</h5>

            <a href="#" id="btn-modele" class="btn btn-success btn-sm">
                ⬇️ Télécharger le modèle Excel
            </a>
        </div>

        {{-- Message de succès --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show text-center w-75 mx-auto mt-3">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Formulaire --}}
        <form action="{{ route('eleves.previsualiser') }}" method="POST" enctype="multipart/form-data" class="row g-3 p-4">
            @csrf

            {{-- Année --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">📅 Année scolaire</label>
                <select name="annee_id" id="annee_id" class="form-select" required>
                    <option value="">-- Choisir une année --</option>
                    @foreach($annees as $annee)
                        <option value="{{ $annee->id }}">
                            {{ $annee->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Cycle --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">🎓 Cycle</label>
                <select name="cycle_id" id="cycle_id" class="form-select" required>
                    <option value="">-- Choisir un cycle --</option>
                    @foreach($cycles as $cycle)
                        <option value="{{ $cycle->id }}">
                            {{ $cycle->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Classe --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">🏫 Classe</label>
                <select name="classe_id" id="classe_id" class="form-select" required>
                    <option value="">-- Choisir année et cycle --</option>
                </select>
            </div>

            {{-- Fichier --}}
            <div class="col-md-12">
                <label class="form-label fw-bold">📎 Fichier Excel ou CSV</label>
                <input type="file" name="fichier" class="form-control" accept=".csv,.xlsx" required>
            </div>

            {{-- Bouton --}}
            <div class="col-12 text-end mt-3">
                <button type="submit" class="btn btn-success">
                    📤 Importer les élèves
                </button>
            </div>
        </form>
    </div>

    {{-- Règles --}}
    <div class="alert alert-info shadow-sm mt-4">
        <h5 class="text-primary mb-3">ℹ️ Règles d'importation</h5>

        <ul>
            <li>Format : <strong>CSV</strong> ou <strong>Excel (.xlsx)</strong></li>
            <li>Première ligne = en-têtes</li>
        </ul>

        <div class="bg-light border rounded p-3 mb-3">
            <code>
                matricule | nom | prenom | date_naissance | sexe | telephone | nationalité | lieu_naissance
            </code>
        </div>

        <ul>
            <li>Champs obligatoires : matricule, nom, prénom, date_naissance, sexe, lieu_naissance</li>
            <li>Sexe : M ou F</li>
            <li>Matricule unique</li>
        </ul>
    </div>
</div>

{{-- JS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const anneeSelect  = document.getElementById('annee_id');
    const cycleSelect  = document.getElementById('cycle_id');
    const classeSelect = document.getElementById('classe_id');
    const btnModele    = document.getElementById('btn-modele');

    // 🔹 Charger classes selon année + cycle
    async function loadClasses(anneeId, cycleId) {

        classeSelect.innerHTML = '<option>Chargement...</option>';

        if (!anneeId || !cycleId) {
            classeSelect.innerHTML = '<option value="">-- Choisir année et cycle --</option>';
            return;
        }

        try {
            const response = await fetch(`/annees/${anneeId}/cycles/${cycleId}/classes`);

            if (!response.ok) throw new Error('Erreur');

            const classes = await response.json();

            classeSelect.innerHTML = '<option value="">-- Choisir une classe --</option>';

            classes.forEach(classe => {
                const option = document.createElement('option');
                option.value = classe.id;
                option.textContent = classe.nom;
                classeSelect.appendChild(option);
            });

        } catch (e) {
            classeSelect.innerHTML = '<option>Erreur de chargement</option>';
            console.error(e);
        }
    }

    // 🔹 Events
    anneeSelect.addEventListener('change', () => {
        loadClasses(anneeSelect.value, cycleSelect.value);
    });

    cycleSelect.addEventListener('change', () => {
        loadClasses(anneeSelect.value, cycleSelect.value);
    });

    // 🔹 Téléchargement modèle
    btnModele.addEventListener('click', function (e) {
        e.preventDefault();

        const anneeId = anneeSelect.value;
        const cycleId = cycleSelect.value;

        if (!anneeId || !cycleId) {
            alert("⚠️ Sélectionnez année et cycle !");
            return;
        }

        window.location.href = `/eleves/modele/import?annee_id=${anneeId}&cycle_id=${cycleId}`;
    });

});
</script>

@endsection