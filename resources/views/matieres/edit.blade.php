@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-4">
    <h2>Modifier la Matière</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Erreurs:</strong>
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <form action="{{ route('matieres.update', $matiere->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nom</label>
            <input type="text" name="nom" class="form-control" value="{{ old('nom', $matiere->nom) }}" required>
        </div>
        <div class="mb-3">
            <label>Type</label>
            <select natype="text" name="type" class="form-control" value="{{ old('type', $matiere->type) }}" required>
                <option value="">-- Choisir un rôle --</option>
                <option value="scientifique">Scientifique</option>
                <option value="litteraire">Litteraire</option>
                 <option value="autres">Autres</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Coefficient</label>
            <input type="number" name="coefficient" class="form-control" value="{{ old('coefficient', $matiere->coefficient) }}" required>
        </div>

       <div class="mb-3">
    <label class="form-label">Classe</label>
    <select name="classe_id" class="form-control" required>
        @foreach ($classes as $classe)
            <option value="{{ $classe->id }}"
                {{ old('classe_id', $matiere->classe_id) == $classe->id ? 'selected' : '' }}>
                {{ $classe->nom }}
            </option>
        @endforeach
    </select>
</div>

        <div class="mb-3">
            <label>Enseignant</label>
            <select name="enseignant_id" class="form-control">
                <option value="">-- Aucun --</option>
                @foreach ($enseignants as $enseignant)
                    <option value="{{ $enseignant->id }}" {{ $enseignant->id == $matiere->enseignant_id ? 'selected' : '' }}>{{ $enseignant->nom }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('matieres.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
