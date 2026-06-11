@extends('layouts.app')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="row justify-content-center mt-5">
    <div class="col-md-8">

        {{-- Message de succès --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 w-50 text-center shadow" role="alert" style="z-index: 1050;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm rounded-3 border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ajouter une classe</h5>
                <a href="{{ route('classes.index') }}" class="btn btn-light btn-sm">&larr; Retour</a>
            </div>

            <div class="card-body">
                <form action="{{ route('classes.store') }}" method="POST">
                    @csrf
                    {{-- Cycle --}}
                    <div class="mb-3">
    <label>Cycle</label>
    <select name="cycle_id" class="form-control" required>
        <option value="">-- Choisir --</option>
        @foreach($cycles as $cycle)
            <option value="{{ $cycle->id }}">{{ $cycle->nom }}</option>
        @endforeach
    </select>
</div>
                    {{-- Nom --}}
                    <div class="mb-3 row">
                        <label for="nom" class="col-md-4 col-form-label text-md-end text-start">Nom</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom') }}" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Niveau --}}
                    <div class="mb-3 row">
                        <label for="niveau" class="col-md-4 col-form-label text-md-end text-start">Niveau</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('niveau') is-invalid @enderror" id="niveau" name="niveau" value="{{ old('niveau') }}">
                            @error('niveau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Année Scolaire --}}
                    <div class="mb-3 row">
                        <label for="annee_id" class="col-md-4 col-form-label text-md-end">Année Scolaire</label>
                        <div class="col-md-6">
                            <select name="annee_id" id="anneeSelect" class="form-select @error('annee_id') is-invalid @enderror" required>
                                <option value="">-- Choisir une année --</option>
                                @foreach($annees as $annee)
                                    <option value="{{ $annee->id }}" {{ old('annee_id') == $annee->id ? 'selected' : '' }}>
                                        {{ $annee->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('annee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Activer ? --}}
                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label text-md-end">Activer ?</label>
                        <div class="col-md-6">
                            <input type="hidden" name="active" value="0">
                            <input type="checkbox" name="active" value="1" {{ old('active', 1) ? 'checked' : '' }}>
                        </div>
                    </div>

                    {{-- Bouton Submit --}}
                    <div class="mb-3 row">
                        <div class="col-md-6 offset-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-success shadow-sm">
                                <i class="bi bi-check-circle"></i> Enregistrer
                            </button>
                            <a href="{{ route('classes.index') }}" class="btn btn-secondary shadow-sm">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script pour fermer l'alerte automatiquement --}}
<script>
setTimeout(() => {
    const alert = document.querySelector('.alert');
    if (alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    }
}, 4000);
</script>

@endsection
