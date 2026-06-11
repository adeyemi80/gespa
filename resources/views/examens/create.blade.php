@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">
    <h2>Créer un examen blanc</h2>
 {{-- Message de succès --}}
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            ✅ {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                        </div>
                    @endif
    <form action="{{ route('examens-blancs.store') }}" method="POST">
        @csrf

        {{-- Type d'examen --}}
        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-control" required>
                <option value="BEPC" {{ old('type') == 'BEPC' ? 'selected' : '' }}>BEPC</option>
                <option value="BAC-A" {{ old('type') == 'BAC-A' ? 'selected' : '' }}>BAC-A</option>
                 <option value="BAC-B" {{ old('type') == 'BAC-B' ? 'selected' : '' }}>BAC-B</option>
                  <option value="BAC-C" {{ old('type') == 'BAC-C' ? 'selected' : '' }}>BAC-C</option>
                   <option value="BAC-D" {{ old('type') == 'BAC-D' ? 'selected' : '' }}>BAC-D</option>
            </select>
        </div>

        {{-- Année --}}
        <div class="mb-3 col-md-3">
            <label class="form-label fw-bold">Année <span class="text-danger">*</span></label>
            <select name="annee_id" class="form-select @error('annee_id') is-invalid @enderror" required>
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

        {{-- Dates --}}
        <div class="mb-3">
            <label>Date début</label>
            <input type="date" name="date_debut" class="form-control" value="{{ old('date_debut') }}" required>
        </div>

        <div class="mb-3">
            <label>Date fin</label>
            <input type="date" name="date_fin" class="form-control" value="{{ old('date_fin') }}" required>
        </div>

        {{-- Classes (facultatif si génération automatique) --}}
        {{--<div class="mb-3">
            <label>Classes (facultatif)</label>
            @foreach($classes as $classe)
                <div>
                    <input type="checkbox" name="classes[]" value="{{ $classe->id }}"
                        {{ (is_array(old('classes')) && in_array($classe->id, old('classes'))) ? 'checked' : '' }}>
                    {{ $classe->nom }}
                </div>
            @endforeach
            <small class="text-muted">Les participants des classes 3ème (BEPC) ou Terminale (BAC) seront générés automatiquement même si aucune case n'est cochée.</small>
        </div>--}}

        {{-- Bouton --}}
        <button class="btn btn-primary">Enregistrer</button>
    </form>
</div>
@endsection