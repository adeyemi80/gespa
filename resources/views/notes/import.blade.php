@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>

<div class="container py-5">

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->has('fichier'))
        <div class="alert alert-danger">{{ $errors->first('fichier') }}</div>
    @endif

    <div class="card shadow rounded-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">📥 Importation des Notes</h4>
        </div>

        <form action="{{ route('notes.previsualiser') }}" method="POST" enctype="multipart/form-data" class="p-4">
            @csrf

            <div class="row g-4">

                {{-- Année --}}
                <div class="col-md-4">
                    <label>📅 Année</label>
                    <select id="annee_id" name="annee_id" class="form-select" required>
                        <option value="">-- Choisir --</option>
                        @foreach($annees as $annee)
                            <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Trimestre --}}
                <div class="col-md-4">
                    <label>🗓️ Trimestre</label>
                    <select id="trimestre_id" name="trimestre_id" class="form-select" required>
                        <option value="">-- Choisir --</option>
                    </select>
                </div>

                {{-- Classe --}}
                <div class="col-md-4">
                    <label>🏫 Classe</label>
                    <select id="classe_id" name="classe_id" class="form-select" required>
                        <option value="">-- Choisir --</option>
                    </select>
                </div>

                {{-- Matière --}}
                <div class="col-md-4">
                    <label>📚 Matière</label>
                    <select id="matiere_id" name="matiere_id" class="form-select" required>
                        <option value="">-- Choisir une matière --</option>
                    </select>
                </div>

                {{-- Bouton modèle --}}
                <div class="col-md-4 d-flex align-items-end">
                    <a id="btn-modele" href="#" class="btn btn-success w-100 disabled">
                        ⚠️ Sélectionnez tout
                    </a>
                </div>

                {{-- Fichier --}}
                <div class="col-12">
                    <label>📄 Fichier Excel</label>
                    <input type="file" name="fichier" class="form-control" required>
                </div>

                {{-- Submit --}}
                <div class="col-12 text-end">
                    <button class="btn btn-primary">
                        📊 Prévisualiser
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const annee = document.getElementById('annee_id');
    const classe = document.getElementById('classe_id');
    const trimestre = document.getElementById('trimestre_id');
    const matiere = document.getElementById('matiere_id');
    const btnModele = document.getElementById('btn-modele');

    let loadingMatieres = false;
    let initDone = false;

    // 🔥 STOP SI SCRIPT DÉJÀ INITIALISÉ
    if (initDone) return;
    initDone = true;

    console.log('SCRIPT INIT OK');

    function updateBtn() {
        const ok = annee.value && classe.value && trimestre.value;
        btnModele.disabled = !ok;
        btnModele.innerHTML = ok ? '⬇️ Télécharger Excel' : '⚠️ Sélection requise';
    }

    function loadOptions(select, data, label = '-- Sélectionner --') {
        select.innerHTML = `<option value="">${label}</option>`;
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.nom;
            select.appendChild(option);
        });
    }

    // 🔁 ANNÉE → CLASSES + TRIMESTRES
    annee.addEventListener('change', (e) => {

        const id = e.target.value;
        console.log('Année sélectionnée:', id);

        // 🔥 IMPORTANT : ignore valeur vide
        if (!id) return;

        classe.innerHTML = '<option>Chargement...</option>';
        trimestre.innerHTML = '<option>Chargement...</option>';

        Promise.all([
            fetch(`/annees/${id}/classes/actives`).then(r => r.json()),
            fetch(`/annees/${id}/trimestres`).then(r => r.json())
        ])
        .then(([classes, trimestres]) => {
            loadOptions(classe, classes);
            loadOptions(trimestre, trimestres);
        })
        .catch(err => console.error('Erreur année:', err));
    });

    // 🔁 CLASSE → MATIÈRES
    classe.addEventListener('change', (e) => {

        const id = e.target.value;
        console.log('Classe:', id);

        if (!id) {
            matiere.innerHTML = '<option value="">-- Choisir une matière --</option>';
            return;
        }

        if (loadingMatieres) return;
        loadingMatieres = true;

        matiere.innerHTML = '<option>Chargement...</option>';

        fetch(`/classes/${id}/matieres`)
            .then(r => r.json())
            .then(data => {

                console.log('Matières:', data);
                console.log('Nombre options:', data.length);

                matiere.innerHTML = '<option value="">-- Choisir une matière --</option>';

                data.forEach(m => {
                    const option = document.createElement('option');
                    option.value = m.id;
                    option.textContent = m.nom;
                    matiere.appendChild(option);
                });

                // 🔥 sélection auto
                if (data.length > 0) {
                    matiere.value = data[0].id;
                }

                console.log('Valeur sélectionnée:', matiere.value);

                loadingMatieres = false;
            })
            .catch(err => {
                console.error('Erreur matières:', err);
                matiere.innerHTML = '<option value="">❌ Erreur chargement</option>';
                loadingMatieres = false;
            });
    });

    // 🔘 BOUTON MODELE
    btnModele.addEventListener('click', (e) => {
        e.preventDefault();

        if (!annee.value || !classe.value || !trimestre.value) {
            alert('⚠️ Sélectionnez année, classe et trimestre');
            return;
        }

        const params = new URLSearchParams({
            annee_id: annee.value,
            classe_id: classe.value,
            trimestre_id: trimestre.value
        });

        window.location.href = `/notes/template?${params}`;
    });

    // 🔄 UPDATE BOUTON
    [annee, classe, trimestre].forEach(el => {
        el.addEventListener('change', updateBtn);
    });

    updateBtn();
});
</script>
@endsection