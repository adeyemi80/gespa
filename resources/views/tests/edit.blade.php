@extends('tableau.neutre')

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
            <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Modifier le Test</h4>
            <a href="{{ route('tests.index') }}" class="btn btn-outline-light">
                <i class="bi bi-arrow-left-circle me-1"></i> Retour
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('tests.update', $test) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="titre" class="form-label fw-semibold">Titre</label>
                        <input type="text" name="titre" id="titre" class="form-control shadow-sm"
                               value="{{ old('titre', $test->titre) }}" placeholder="Ex: Test de Mathématiques" required>
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label fw-semibold">Type</label>
                        <select name="type" id="type" class="form-select shadow-sm" required>
                            <option value="">-- Sélectionnez un type --</option>
                            <option value="interro" {{ old('type', $test->type) == 'interro' ? 'selected' : '' }}>Interrogation</option>
                            <option value="devoir" {{ old('type', $test->type) == 'devoir' ? 'selected' : '' }}>Devoir</option>
                            <option value="examen" {{ old('type', $test->type) == 'examen' ? 'selected' : '' }}>Examen</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="matiere_id" class="form-label fw-semibold">Matière</label>
                        <select name="matiere_id" id="matiere_id" class="form-select shadow-sm" required>
                            <option value="">-- Sélectionnez une matière --</option>
                            @foreach(\App\Models\Matiere::all() as $matiere)
                                <option value="{{ $matiere->id }}" {{ old('matiere_id', $test->matiere_id) == $matiere->id ? 'selected' : '' }}>
                                    {{ $matiere->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="classe_id" class="form-label fw-semibold">Classe</label>
                        <select name="classe_id" id="classe_id" class="form-select shadow-sm" required>
                            <option value="">-- Sélectionnez une classe --</option>
                            @foreach(\App\Models\Classe::all() as $classe)
                                <option value="{{ $classe->id }}" {{ old('classe_id', $test->classe_id) == $classe->id ? 'selected' : '' }}>
                                    {{ $classe->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="annee_id" class="form-label fw-semibold">Année</label>
                        <select name="annee_id" id="annee_id" class="form-select shadow-sm" required>
                            <option value="">-- Sélectionnez une année --</option>
                            @foreach(\App\Models\Annee::all() as $annee)
                                <option value="{{ $annee->id }}" {{ old('annee_id', $test->annee_id) == $annee->id ? 'selected' : '' }}>
                                    {{ $annee->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="trimestre_id" class="form-label fw-semibold">Période</label>
                        <select name="annee_id" id="trimestre_id" class="form-select shadow-sm" required>
                            <option value="">-- Sélectionnez une année --</option>
                            @foreach(\App\Models\Trimestre::all() as $trimestre)
                                <option value="{{ $trimestre->id }}" {{ old('trimestre_id', $test->trimestre_id) == $trimestre->id ? 'selected' : '' }}>
                                    {{ $trimestre->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="form-control shadow-sm" placeholder="Ajouter des détails...">{{ old('description', $test->description) }}</textarea>
                    </div>

                    <div class="col-12">
                        <label for="fichier" class="form-label fw-semibold">Fichier (PDF, DOC, DOCX)</label>
                        @if($test->fichier)
                            <p>Fichier actuel : <a href="{{ asset('storage/' . $test->fichier) }}" target="_blank">
                                <i class="bi bi-download me-1"></i>Télécharger</a></p>
                        @endif
                        <input type="file" name="fichier" id="fichier" class="form-control shadow-sm">
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary shadow">
                        <i class="bi bi-pencil-square me-2"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Bootstrap 5 Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection
