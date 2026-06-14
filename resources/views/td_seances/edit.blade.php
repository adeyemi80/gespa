@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">

    <div class="card shadow-sm">
        <div class="card-header bg-warning d-flex justify-content-between align-items-center">
            <span><i class="bi bi-pencil"></i> Modifier la séance #{{ $tdSeance->id }}</span>
            <a href="{{ route('td-seances.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>

        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('td-seances.update', $tdSeance) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Année <span class="text-danger">*</span></label>
                        <select name="annee_id" class="form-select @error('annee_id') is-invalid @enderror">
                            <option value="">Choisir une année</option>
                            @foreach($annees as $annee)
                                <option value="{{ $annee->id }}"
                                    {{ old('annee_id', $tdSeance->annee_id) == $annee->id ? 'selected' : '' }}>
                                    {{ $annee->libelle ?? $annee->nom ?? $annee->id }}
                                </option>
                            @endforeach
                        </select>
                        @error('annee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Classe <span class="text-danger">*</span></label>
                        <select name="classe_id" class="form-select @error('classe_id') is-invalid @enderror">
                            <option value="">Choisir une classe</option>
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}"
                                    {{ old('classe_id', $tdSeance->classe_id) == $classe->id ? 'selected' : '' }}>
                                    {{ $classe->niveau }}
                                </option>
                            @endforeach
                        </select>
                        @error('classe_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="date"
                               class="form-control @error('date') is-invalid @enderror"
                               value="{{ old('date', $tdSeance->date->format('Y-m-d')) }}">
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Thème <span class="text-muted small">(optionnel)</span></label>
                        <input type="text" name="libelle"
                               class="form-control @error('libelle') is-invalid @enderror"
                               placeholder="Ex: Algorithmique, POO..."
                               value="{{ old('libelle', $tdSeance->libelle) }}">
                        @error('libelle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-lg"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('td-seances.show', $tdSeance) }}" class="btn btn-outline-secondary">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection