@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="row justify-content-center mt-3">
    <div class="col-md-8">

        @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                {{ $message }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Modifier La Classe
                </div>
                <div class="float-end">
                    <a href="{{ route('classes.index') }}" class="btn btn-primary btn-sm">&larr; Retour</a>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('classes.update', $classe->id) }}" method="post">
                    @csrf
                    @method("PUT")

                    {{-- ✅ Cycle --}}
                    <div class="mb-3 row">
                        <label for="cycle_id" class="col-md-4 col-form-label text-md-end text-start">Cycle</label>
                        <div class="col-md-6">
                            <select name="cycle_id" class="form-select @error('cycle_id') is-invalid @enderror" required>
                                <option value="">-- Choisir un Cycle --</option>
                                @foreach($cycles as $cycle)
                                    <option value="{{ $cycle->id }}" 
                                        {{ $classe->cycle_id == $cycle->id ? 'selected' : '' }}>
                                        {{ $cycle->nom }}
                                    </option>
                                @endforeach
                            </select>

                            @error('cycle_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Nom --}}
                    <div class="mb-3 row">
                        <label for="nom" class="col-md-4 col-form-label text-md-end text-start">Nom</label>
                        <div class="col-md-6">
                            <input type="text" 
                                   class="form-control @error('nom') is-invalid @enderror" 
                                   name="nom" 
                                   value="{{ old('nom', $classe->nom) }}">
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Niveau --}}
                    <div class="mb-3 row">
                        <label for="niveau" class="col-md-4 col-form-label text-md-end text-start">Niveau</label>
                        <div class="col-md-6">
                            <input type="text" 
                                   class="form-control @error('niveau') is-invalid @enderror" 
                                   name="niveau" 
                                   value="{{ old('niveau', $classe->niveau) }}">
                            @error('niveau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Année Scolaire --}}
                    <div class="mb-3 row">
                        <label for="annee_id" class="col-md-4 col-form-label text-md-end text-start">Année Scolaire</label>
                        <div class="col-md-6">
                            <select name="annee_id" class="form-select @error('annee_id') is-invalid @enderror" required>
                                <option value="">-- Choisir une année --</option>
                                @foreach($annees as $annee)
                                    <option value="{{ $annee->id }}" 
                                        {{ $classe->annees->contains($annee->id) ? 'selected' : '' }}>
                                        {{ $annee->nom }}
                                    </option>
                                @endforeach
                            </select>

                            @error('annee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Bouton --}}
                    <div class="mb-3 row">
                        <input type="submit" 
                               class="col-md-3 offset-md-5 btn btn-primary" 
                               value="Modifier">
                    </div>

                </form>
            </div>
        </div>
    </div>    
</div>

@endsection