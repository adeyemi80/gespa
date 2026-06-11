@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">Modifier une note</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('notes.update', $note->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Sélection de l'inscription --}}
                        <div class="form-group mb-3">
                            <label for="inscription_id">Élève / Classe / Année</label>
                            <select name="inscription_id" class="form-control" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach($inscriptions as $inscription)
                                    <option value="{{ $inscription->id }}"
                                        {{ (old('inscription_id', $note->inscription_id ?? '') == $inscription->id) ? 'selected' : '' }}>
                                        {{ $inscription->eleve->nom }} {{ $inscription->eleve->prenom }}
                                        | {{ $inscription->classe->nom ?? '-' }}
                                        | {{ $inscription->annee->nom ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Sélection de la matière --}}
                        <div class="form-group mb-3">
                            <label for="matiere_id">Matière</label>
                            <select name="matiere_id" class="form-control" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id }}"
                                        {{ (old('matiere_id', $note->matiere_id ?? '') == $matiere->id) ? 'selected' : '' }}>
                                        {{ $matiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Sélection du trimestre --}}
                        <div class="form-group mb-3">
                            <label for="trimestre_id">Trimestre</label>
                            <select name="trimestre_id" class="form-control" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach($trimestres as $t)
                                    <option value="{{ $t->id }}"
                                        {{ (old('trimestre_id', $note->trimestre_id ?? '') == $t->id) ? 'selected' : '' }}>
                                        {{ $t->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Notes --}}
                        <div class="form-group mb-3">
                            <label>Moyenne d'Interrogation</label>
                            <input type="number" name="moyenne_interro" step="0.01" max="20" min="0" class="form-control"
                                value="{{ old('moyenne_interro', $note->moyenne_interro ?? '') }}">
                        </div>

                        <div class="form-group mb-3">
                            <label>Devoir 1</label>
                            <input type="number" name="devoir1" step="0.01" max="20" min="0" class="form-control"
                                value="{{ old('devoir1', $note->devoir1 ?? '') }}">
                        </div>

                        <div class="form-group mb-3">
                            <label>Devoir 2</label>
                            <input type="number" name="devoir2" step="0.01" max="20" min="0" class="form-control"
                                value="{{ old('devoir2', $note->devoir2 ?? '') }}">
                        </div>

                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        <a href="{{ route('notes.index') }}" class="btn btn-secondary">Annuler</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
