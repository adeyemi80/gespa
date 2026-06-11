@extends('classes.layout')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Oups !</strong> Veuillez corriger les erreurs ci-dessous :
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    <div class="card shadow rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Ajouter une Epreuve</h4>
            <a href="{{ route('tests.index') }}" class="btn btn-outline-light">
                <i class="bi bi-arrow-left-circle me-1"></i> Retour
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('tests.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="titre" class="form-label fw-semibold">Titre</label>
                        <input type="text" name="titre" id="titre" class="form-control shadow-sm" 
                               value="{{ old('titre') }}" placeholder="Ex: Epreuve de Mathématiques" required>
                    </div>
 <div class="mb-3">
            <label for="date" class="form-label">Date du test</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>
                    <div class="col-md-6">
                        <label for="type" class="form-label fw-semibold">Type</label>
                        <select name="type" id="type" class="form-select shadow-sm" required>
                            <option value="">-- Sélectionnez un type --</option>
                            <option value="interro" {{ old('type') == 'interro' ? 'selected' : '' }}>Interrogation</option>
                            <option value="devoir" {{ old('type') == 'devoir' ? 'selected' : '' }}>Devoir</option>
                            <option value="composition" {{ old('type') == 'composition' ? 'selected' : '' }}>Composition</option>
                            <option value="examen" {{ old('type') == 'examen' ? 'selected' : '' }}>Examen</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="annee_id" class="form-label fw-semibold">Année</label>
                        <select name="annee_id" id="annee_id" class="form-select shadow-sm" required>
                            <option value="">-- Sélectionnez une année --</option>
                            @foreach(\App\Models\Annee::all() as $annee)
                                <option value="{{ $annee->id }}" {{ old('annee_id') == $annee->id ? 'selected' : '' }}>
                                    {{ $annee->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="classe_id" class="form-label fw-semibold">Classe</label>
                        <select name="classe_id" id="classe_id" class="form-select shadow-sm" required>
                            <option value="">-- Sélectionnez une classe --</option>
                            @foreach(\App\Models\Classe::all() as $classe)
                                <option value="{{ $classe->id }}" data-annee-id="{{ $classe->annee_id }}">
                                    {{ $classe->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="matiere_id" class="form-label fw-semibold">Matière</label>
                        <select name="matiere_id" id="matiere_id" class="form-select shadow-sm" required>
                            <option value="">-- Sélectionnez une matière --</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">🗓️ Période</label>
                        <select name="trimestre_id" class="form-select shadow-sm" required>
                            <option value="">-- Sélectionnez un Trimestre --</option>
                            @foreach ($trimestres as $trimestre)
                                <option value="{{ $trimestre->id }}">{{ $trimestre->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="form-control shadow-sm" placeholder="Ajouter des détails...">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-12">
                        <label for="fichier" class="form-label fw-semibold">Fichier (PDF, DOC, DOCX)</label>
                        <input type="file" name="fichier" id="fichier" class="form-control shadow-sm">
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary shadow">
                        <i class="bi bi-save2 me-2"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Bootstrap 5 Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

{{-- JavaScript pour lier classes et matières --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAnnee = document.getElementById('annee_id');
    const selectClasse = document.getElementById('classe_id');
    const matiereSelect = document.getElementById('matiere_id');

    // Filtrer les classes selon l'année sélectionnée
    function filterClasses() {
        const anneeId = selectAnnee.value;
        const options = selectClasse.querySelectorAll('option[data-annee-id]');
        options.forEach(option => {
            option.style.display = option.getAttribute('data-annee-id') === anneeId ? '' : 'none';
        });
        selectClasse.value = '';
        matiereSelect.innerHTML = '<option value="">-- Sélectionnez une matière --</option>';
    }

    selectAnnee.addEventListener('change', filterClasses);

    // Charger les matières via AJAX selon la classe sélectionnée
    selectClasse.addEventListener('change', function () {
        const classeId = this.value;
        if (!classeId) {
            matiereSelect.innerHTML = '<option value="">-- Sélectionnez une matière --</option>';
            return;
        }

        fetch(`/classes/${classeId}/matieres`)
            .then(res => res.json())
            .then(data => {
                matiereSelect.innerHTML = '<option value="">-- Sélectionnez une matière --</option>';
                data.forEach(matiere => {
                    const option = document.createElement('option');
                    option.value = matiere.id;
                    option.textContent = matiere.nom;
                    matiereSelect.appendChild(option);
                });
            })
            .catch(() => {
                matiereSelect.innerHTML = '<option value="">Erreur de chargement</option>';
            });
    });

    filterClasses();
});
</script>
@endsection
