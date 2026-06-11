@extends('tableau.neutre')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-5 fw-bold">🔍 Rechercher des Épreuves</h1>
        <a href="{{ route('tests.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i> Retour à la liste
        </a>
    </div>

    {{-- Formulaire de filtre --}}
    <div class="card shadow rounded-4 mb-4">
        <div class="card-body">
            <form action="{{ route('tests.index') }}" method="GET" class="row g-3 align-items-end">

                <!-- Titre -->
                <div class="col-md-3">
                    <label for="titre" class="form-label fw-semibold">Titre</label>
                    <input type="text" name="search" id="titre" class="form-control shadow-sm" 
                           value="{{ request('search') }}" placeholder="Ex: Mathématiques">
                </div>

                <!-- Année -->
                <div class="col-md-2">
                    <label for="annee_id" class="form-label fw-semibold">Année</label>
                    <select name="annee_id" id="annee_id" class="form-select shadow-sm">
                        <option value="">Toutes</option>
                        @foreach($annees as $annee)
                            <option value="{{ $annee->id }}" {{ request('annee_id') == $annee->id ? 'selected' : '' }}>
                                {{ $annee->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Trimestre -->
                <div class="col-md-2">
                    <label for="trimestre_id" class="form-label fw-semibold">Trimestre</label>
                    <select name="trimestre_id" id="trimestre_id" class="form-select shadow-sm">
                        <option value="">Tous</option>
                        @foreach($trimestres as $t)
                            <option value="{{ $t->id }}" {{ request('trimestre_id') == $t->id ? 'selected' : '' }}>
                                {{ $t->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Classe (dépend de l'année) -->
                <div class="col-md-2">
                    <label for="classe_id" class="form-label fw-semibold">Classe</label>
                    <select name="classe_id" id="classe_id" class="form-select shadow-sm">
                        <option value="">Toutes</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}" data-annee-id="{{ $classe->annee_id }}"
                                {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Matière (dépend de la classe) -->
                <div class="col-md-2">
                    <label for="matiere_id" class="form-label fw-semibold">Matière</label>
                    <select name="matiere_id" id="matiere_id" class="form-select shadow-sm">
                        <option value="">Toutes</option>
                        @if(request('classe_id'))
                            @foreach($matieres as $matiere)
                                <option value="{{ $matiere->id }}" {{ request('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                    {{ $matiere->nom }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Bouton rechercher -->
                <div class="col-md-1 d-grid">
                    <button type="submit" class="btn btn-primary shadow">
                        <i class="bi bi-search me-1"></i> Rechercher
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- Résultats --}}
    <div class="card shadow rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Titre</th>
                            <th>Matière</th>
                            <th>Classe</th>
                            <th>Année</th>
                            <th>Type</th>
                            <th>Trimestre</th>
                            <th>Fichier</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tests as $test)
                        <tr>
                            <td>{{ $test->id }}</td>
                            <td>{{ $test->titre }}</td>
                            <td>{{ $test->matiere->nom ?? '-' }}</td>
                            <td>{{ $test->classe->nom ?? '-' }}</td>
                            <td>{{ $test->annee->nom ?? '-' }}</td>
                            <td>{{ ucfirst($test->type) }}</td>
                            <td>{{ $test->trimestre->nom ?? '-' }}</td>
                            <td>
                                @if($test->fichier)
                                    <a href="{{ asset('storage/' . $test->fichier) }}" target="_blank">
                                        <i class="bi bi-download me-1"></i>Télécharger
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-nowrap">
                                <a href="{{ route('tests.show', $test) }}" class="btn btn-sm btn-info me-1">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('tests.edit', $test) }}" class="btn btn-sm btn-warning me-1">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('tests.destroy', $test) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Voulez-vous vraiment supprimer ce test ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-3">Aucun test trouvé.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Bootstrap Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

{{-- Script filtrage dynamique --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAnnee = document.getElementById('annee_id');
    const selectClasse = document.getElementById('classe_id');
    const selectMatiere = document.getElementById('matiere_id');

    // Filtrer les classes selon l'année
    function filterClasses() {
        const anneeId = selectAnnee.value;
        const options = selectClasse.querySelectorAll('option[data-annee-id]');
        options.forEach(option => {
            option.style.display = (!anneeId || option.dataset.anneeId === anneeId) ? '' : 'none';
        });
        selectClasse.value = '';
        filterMatieres();
    }

    // Filtrer les matières selon la classe via fetch
    function filterMatieres() {
        const classeId = selectClasse.value;
        if (!classeId) {
            selectMatiere.innerHTML = '<option value="">Toutes</option>';
            return;
        }

        fetch(`/classes/${classeId}/matieres`)
            .then(res => res.json())
            .then(data => {
                selectMatiere.innerHTML = '<option value="">Toutes</option>';
                data.forEach(matiere => {
                    const option = document.createElement('option');
                    option.value = matiere.id;
                    option.textContent = matiere.nom;
                    selectMatiere.appendChild(option);
                });
            })
            .catch(() => {
                selectMatiere.innerHTML = '<option value="">Erreur de chargement</option>';
            });
    }

    selectAnnee.addEventListener('change', filterClasses);
    selectClasse.addEventListener('change', filterMatieres);

    // Initialisation
    filterClasses();
});
</script>
@endsection
