@extends('tableau.neutre')

@section('content')

<a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">
    ⬅️ Retour
</a>

<div class="container py-5">

    {{-- TITRE --}}
    <div class="text-center mb-4">
        <h2 class="fw-bold">Importation des parents</h2>
    </div>

    <div class="row g-4">

        {{-- MODELE --}}
        <div class="col-lg-6">
            <div class="card shadow border-0">
                <div class="card-body">

                    <h5 class="mb-3">Télécharger modèle</h5>

                    <form action="{{ route('parens.import.modele') }}" method="GET">

                        {{-- ANNEE --}}
                        <div class="mb-3">
                            <label>Année</label>
                            <select name="annee_id" id="annee_modele" class="form-select" required>
                                <option value="">-- Année --</option>
                                @foreach($annees as $annee)
                                    <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- CYCLE --}}
                        <div class="mb-3">
                            <label>Cycle</label>
                            <select name="cycle_id" id="cycle_modele" class="form-select" required>
                                <option value="">-- Cycle --</option>
                                @foreach($cycles as $cycle)
                                    <option value="{{ $cycle->id }}">{{ $cycle->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- CLASSE --}}
                        <div class="mb-3">
                            <label>Classe</label>
                            <select name="classe_id" id="classe_modele" class="form-select" required>
                                <option value="">-- Sélectionnez --</option>
                            </select>
                        </div>

                        <button class="btn btn-success w-100">
                            Télécharger
                        </button>

                    </form>
                </div>
            </div>
        </div>

        {{-- IMPORT --}}
        <div class="col-lg-6">
            <div class="card shadow border-0">
                <div class="card-body">

                    <h5 class="mb-3">Importer fichier</h5>

                    <form action="{{ route('parens.import.preview') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- ANNEE --}}
                        <div class="mb-3">
                            <label>Année</label>
                            <select name="annee_id" id="annee_import" class="form-select" required>
                                <option value="">-- Année --</option>
                                @foreach($annees as $annee)
                                    <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- CYCLE --}}
                        <div class="mb-3">
                            <label>Cycle</label>
                            <select name="cycle_id" id="cycle_import" class="form-select" required>
                                <option value="">-- Cycle --</option>
                                @foreach($cycles as $cycle)
                                    <option value="{{ $cycle->id }}">{{ $cycle->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- CLASSE --}}
                        <div class="mb-3">
                            <label>Classe</label>
                            <select name="classe_id" id="classe_import" class="form-select" required>
                                <option value="">-- Sélectionnez --</option>
                            </select>
                        </div>

                        {{-- FILE --}}
                        <div class="mb-3">
                            <input type="file" name="fichier" class="form-control" required>
                        </div>

                        <button class="btn btn-primary w-100">
                            Prévisualiser
                        </button>

                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

{{-- JS AJAX --}}
<script>
async function loadClasses(anneeId, cycleId, selectId) {

    const select = document.getElementById(selectId);

    select.innerHTML = '<option>Chargement...</option>';

    if (!anneeId || !cycleId) {
        select.innerHTML = '<option>-- Sélectionnez année + cycle --</option>';
        return;
    }

    try {
        const response = await fetch(`/annees/${anneeId}/cycles/${cycleId}/classes`);
        const data = await response.json();

        select.innerHTML = '<option value="">-- Classe --</option>';

        data.forEach(classe => {
            select.innerHTML += `<option value="${classe.id}">${classe.nom}</option>`;
        });

    } catch (e) {
        select.innerHTML = '<option>Erreur chargement</option>';
    }
}

// MODELE
document.getElementById('annee_modele').addEventListener('change', updateModele);
document.getElementById('cycle_modele').addEventListener('change', updateModele);

function updateModele() {
    loadClasses(
        document.getElementById('annee_modele').value,
        document.getElementById('cycle_modele').value,
        'classe_modele'
    );
}

// IMPORT
document.getElementById('annee_import').addEventListener('change', updateImport);
document.getElementById('cycle_import').addEventListener('change', updateImport);

function updateImport() {
    loadClasses(
        document.getElementById('annee_import').value,
        document.getElementById('cycle_import').value,
        'classe_import'
    );
}
</script>

@endsection