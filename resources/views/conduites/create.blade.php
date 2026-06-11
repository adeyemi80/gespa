@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Ajouter une Note de Conduite</h4>
        </div>

        <div class="card-body">
            {{-- Messages d’erreur --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <h6>Veuillez corriger les erreurs suivantes :</h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Formulaire --}}
            <form action="{{ route('conduites.store') }}" method="POST">
                @csrf

                {{-- Inscription (Élève + Classe) --}}
                <div class="mb-3">
                    <label for="inscription_id" class="form-label">Élève inscrit</label>
                    <select name="inscription_id" id="inscription_id" class="form-select" required>
                        <option value="">-- Sélectionner un élève --</option>
                        @foreach($inscriptions as $inscription)
                            <option value="{{ $inscription->id }}" {{ old('inscription_id') == $inscription->id ? 'selected' : '' }}>
                                {{ $inscription->eleve->nom }} {{ $inscription->eleve->prenom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Trimestre --}}
                <div class="mb-3">
                    <label for="trimestre_id" class="form-label">Trimestre</label>
                    <select name="trimestre_id" id="trimestre_id" class="form-select" required>
                        <option value="">-- Sélectionner un trimestre --</option>
                        @foreach($trimestres as $trimestre)
                            <option value="{{ $trimestre->id }}" {{ old('trimestre_id') == $trimestre->id ? 'selected' : '' }}>
                                {{ $trimestre->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Note de conduite --}}
                <div class="mb-3">
                    <label for="note_conduite" class="form-label">Note de Conduite</label>
                    <input 
                        type="number" 
                        name="note_conduite" 
                        id="note_conduite" 
                        class="form-control" 
                        step="0.01" 
                        min="0" 
                        max="20" 
                        value="{{ old('note_conduite') }}" 
                        required
                    >
                </div>

                {{-- Boutons --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('conduites.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save2"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
