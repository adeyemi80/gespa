@extends('tableau.neutre')

@section('title', 'Importation des élèves')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📥 Importation des Élèves</h5>
                </div>

                <div class="card-body">
                    {{-- Message de succès --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Formulaire d'import --}}
                    <form action="{{ route('eleves.import.previsualiser') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="annee_id" class="form-label">📅 Année scolaire</label>
                            <select name="annee_id" id="annee_id" class="form-select" required>
                                <option value="">-- Sélectionnez une année --</option>
                                @foreach($annees as $annee)
                                    <option value="{{ $annee->id }}" {{ old('annee_id') == $annee->id ? 'selected' : '' }}>
                                        {{ $annee->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="classe_id" class="form-label">🏫 Classe</label>
                            <select name="classe_id" id="classe_id" class="form-select" required>
                                <option value="">-- Sélectionnez une classe --</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                        {{ $classe->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="fichier" class="form-label">📄 Fichier Excel</label>
                            <input type="file" name="fichier" id="fichier" class="form-control" accept=".xlsx,.xls,.csv" required>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success">
                                🔍 Prévisualiser
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
