@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Modifier la Note de Conduite</h3>
                </div>

                <div class="card-body">
                    {{-- Affichage des erreurs --}}
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

                    <form action="{{ route('conduites.update', $conduite) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- Élève inscrit --}}
                        <div class="mb-3">
                            <label for="inscription_id" class="form-label">Élève inscrit</label>
                            <select name="inscription_id" id="inscription_id" class="form-select" required>
                                <option value="">-- Sélectionner un élève --</option>
                                @foreach($inscriptions as $inscription)
                                    <option value="{{ $inscription->id }}" 
                                        {{ old('inscription_id', $conduite->inscription_id) == $inscription->id ? 'selected' : '' }}>
                                        {{ $inscription->eleve->nom }} {{ $inscription->eleve->prenom }} — {{ $inscription->classe->nom }}
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
                                    <option value="{{ $trimestre->id }}" 
                                        {{ old('trimestre_id', $conduite->trimestre_id) == $trimestre->id ? 'selected' : '' }}>
                                        {{ $trimestre->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Note de conduite --}}
                        <div class="mb-4">
                            <label for="note_conduite" class="form-label">Note de Conduite (sur 20)</label>
                            <input 
                                type="number" 
                                name="note_conduite" 
                                id="note_conduite" 
                                class="form-control" 
                                step="0.01" 
                                min="0" 
                                max="20" 
                                value="{{ old('note_conduite', $conduite->note_conduite) }}" 
                                required
                            >
                        </div>

                        {{-- Boutons --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('conduites.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left-circle"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save2"></i> Mettre à jour
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
