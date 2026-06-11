@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
<div class="card-header bg-primary-subtle text-primary-emphasis d-flex align-items-center">
    <h3 class="mb-4 text-primary fw-bold">
        📊 Importation des notes (Multi-matières)
    </h3>
</div>
  <p class="text-muted">
            Téléchargez ici le fichier Excel d'importation des notes par classe et par matière. 
            Sélectionnez successivement l'année scolaire, le trimestre concerné et la classe, 
            puis cliquez sur Générer le fichier excel.
        </p>
    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ==========================================================
         BOUTON OUVERTURE FORMULAIRE MODÈLE  ✅ RESTAURÉ
       ========================================================== --}}
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-outline-primary"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#modeleExcel">
            📥 Télécharger le modèle Excel
        </button>
    </div>

    {{-- ==========================================================
         CARD 1 : MODÈLE EXCEL (FERMÉ PAR DÉFAUT)  ✅ COMPLÈTE
       ========================================================== --}}
    <div class="collapse mb-5" id="modeleExcel">
        <div class="card shadow-sm">
            <div class="card-header bg-light fw-bold">
                📥 Modèle Excel (1 feuille = 1 matière)
            </div>

            <div class="card-body">
                <form action="{{ route('notes.import.template') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Année scolaire</label>
                            <select id="annee_1" name="annee_id" class="form-select" required>
                                <option value="">-- choisir --</option>
                                @foreach($annees as $annee)
                                    <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Trimestre</label>
                            <select id="trimestre_1" name="trimestre_id" class="form-select" required>
                                <option value="">-- année d'abord --</option>
                            </select>
                        </div>

            {{-- Cycle --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">🎓 Cycle</label>
                <select id="cycle_1" name="cycle_id" class="form-select" required>
                    <option value="">-- Choisir un cycle --</option>
                    @foreach($cycles as $cycle)
                        <option value="{{ $cycle->id }}">
                            {{ $cycle->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

                        <div class="col-md-4">
                            <label class="form-label">Classe</label>
                            <select id="classe_1" name="classe_id" class="form-select" required>
    <option value="">-- cycle d'abord --</option>
</select>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button class="btn btn-primary">
                            ⬇️ Générer le fichier Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ==========================================================
         CARD 2 : IMPORT MULTI-TYPES + MATIÈRE
       ========================================================== --}}
    <div class="card shadow-sm">
        <div class="card-header bg-light fw-bold">
            🔎 Importation des notes (plusieurs types à la fois)
        </div>

        <div class="card-body">
            <form action="{{ route('notes.import.preview') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Année scolaire</label>
                        <select id="annee_2" name="annee_id" class="form-select" required>
                            <option value="">-- choisir --</option>
                            @foreach($annees as $annee)
                                <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Trimestre</label>
                        <select id="trimestre_2" name="trimestre_id" class="form-select" required>
                            <option value="">-- année d'abord --</option>
                        </select>
                    </div>
                    {{-- Cycle --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">🎓 Cycle</label>
                <select id="cycle_2" name="cycle_id" class="form-select" required>
                    <option value="">-- Choisir un cycle --</option>
                    @foreach($cycles as $cycle)
                        <option value="{{ $cycle->id }}">
                            {{ $cycle->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

                    <div class="col-md-3">
                        <label class="form-label">Classe</label>
                        <select id="classe_2" name="classe_id" class="form-select" required>
    <option value="">-- cycle d'abord --</option>
</select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Matière</label>
                        <select id="matiere_2" name="matiere_id" class="form-select" required>
                            <option value="">-- classe d'abord --</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4">

                {{-- Types de notes --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">🧮 Types de notes à importer</label>
                    <div class="row">
                        @foreach([
                            'interrogation1' => 'Interrogation 1',
                            'interrogation2' => 'Interrogation 2',
                            'interrogation3' => 'Interrogation 3',
                            'devoir1' => 'Devoir 1',
                            'devoir2' => 'Devoir 2',
                            
                        ] as $key => $label)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="types[]"
                                           value="{{ $key }}"
                                           id="{{ $key }}">
                                    <label class="form-check-label" for="{{ $key }}">
                                        {{ $label }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <small class="text-muted">
                        ✔ Seules les colonnes cochées seront importées
                    </small>
                </div>

                <div class="mb-3">
                    <label class="form-label">📄 Fichier Excel (multi-feuilles)</label>
                    <input type="file" name="file" class="form-control" required>
                </div>

                <div class="text-end mt-4">
                    <button class="btn btn-success">
                        🔎 Prévisualiser les notes
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
<script>
const annees = @json($annees);

function resetSelect(select, label) {
    select.innerHTML = `<option value="">${label}</option>`;
}

function populate(select, items) {
    items.forEach(item => {
        select.innerHTML += `<option value="${item.id}">${item.nom}</option>`;
    });
}

// 🔥 Charger classes via AJAX (cycle → classes)
function loadClasses(cycleId, classeSelect, matiereSelect = null) {

    resetSelect(classeSelect, '-- chargement... --');
    if (matiereSelect) resetSelect(matiereSelect, '-- classe d\'abord --');

    if (!cycleId) {
        resetSelect(classeSelect, '-- cycle d\'abord --');
        return;
    }

    fetch(`/cycles/${cycleId}/classes`)
        .then(res => res.json())
        .then(data => {
            resetSelect(classeSelect, '-- choisir classe --');
            data.forEach(c => {
                classeSelect.innerHTML += `<option value="${c.id}">${c.nom}</option>`;
            });
        })
        .catch(() => {
            resetSelect(classeSelect, 'Erreur de chargement');
        });
}

// 🔥 Gestion complète d’un formulaire
function bind(prefix) {

    const annee     = document.getElementById(`annee_${prefix}`);
    const trimestre = document.getElementById(`trimestre_${prefix}`);
    const cycle     = document.getElementById(`cycle_${prefix}`);
    const classe    = document.getElementById(`classe_${prefix}`);
    const matiere   = document.getElementById(`matiere_${prefix}`);

    // =========================
    // ANNÉE → TRIMESTRES
    // =========================
    if (annee && trimestre) {
        annee.addEventListener('change', function() {

            resetSelect(trimestre, '-- année d\'abord --');
            if (matiere) resetSelect(matiere, '-- classe d\'abord --');

            if (!this.value) return;

            const selected = annees.find(a => a.id == this.value);
            if (selected?.trimestres) {
                populate(trimestre, selected.trimestres);
            }
        });
    }

    // =========================
    // CYCLE → CLASSES
    // =========================
    if (cycle && classe) {
        cycle.addEventListener('change', function () {
            loadClasses(this.value, classe, matiere);
        });
    }

    // =========================
    // CLASSE → MATIÈRES (FORMULAIRE 2)
    // =========================
    if (prefix === '2' && classe && matiere) {

        classe.addEventListener('change', function() {

            resetSelect(matiere, '-- classe d\'abord --');

            if (!this.value) return;

            // 🔥 AJAX pour matières (recommandé)
            fetch(`/classes/${this.value}/matieres`)
                .then(res => res.json())
                .then(data => {
                    resetSelect(matiere, '-- choisir matière --');

                    data.forEach(m => {
                        matiere.innerHTML += `<option value="${m.id}">${m.nom}</option>`;
                    });
                })
                .catch(() => {
                    resetSelect(matiere, 'Erreur');
                });
        });
    }
}

// 🔥 INITIALISATION
document.addEventListener('DOMContentLoaded', function () {
    bind('1');
    bind('2');
});
</script>
@endsection
