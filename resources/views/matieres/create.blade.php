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
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Ajouter une Matière</h5>
                </div>

                <div class="card-body">

                    {{-- Affichage des erreurs --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Veuillez corriger les erreurs ci-dessous :</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Message de succès --}}
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            ✅ {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                        </div>
                    @endif

                    <form action="{{ route('matieres.store') }}" method="POST">
                        @csrf

                        {{-- Nom --}}
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom de la matière <span class="text-danger">*</span></label>
                            <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror" 
                                   value="{{ old('nom') }}" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Type --}}
                        <div class="mb-3">
                            <label for="type" class="form-label">Type de la matière <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">-- Choisir un type --</option>
                                <option value="scientifique" {{ old('type') == 'scientifique' ? 'selected' : '' }}>Scientifique</option>
                                <option value="litteraire" {{ old('type') == 'litteraire' ? 'selected' : '' }}>Littéraire</option>
                                <option value="autres" {{ old('type') == 'autres' ? 'selected' : '' }}>Autres</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Coefficient --}}
                        <div class="mb-3">
                            <label for="coefficient" class="form-label">Coefficient <span class="text-danger">*</span></label>
                            <input type="number" name="coefficient" id="coefficient" 
                                   class="form-control @error('coefficient') is-invalid @enderror" 
                                   value="{{ old('coefficient', 1) }}" required min="1">
                            @error('coefficient')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                         {{-- Niveau
                        <div class="mb-3">
                            <label for="niveau" class="form-label">Niveau <span class="text-danger">*</span></label>
                            <input type="text" name="niveau" id="niveau" class="form-control @error('niveau') is-invalid @enderror" 
                                   value="{{ old('niveau') }}" required>
                            @error('niveau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                         --}}


                        {{-- Classe --}}
                        <div class="mb-3">
                            <label for="classe_id" class="form-label">Classe <span class="text-danger">*</span></label>
                            <select name="classe_id" id="classe_id" class="form-select @error('classe_id') is-invalid @enderror" required>
                                <option value="">-- Choisir une classe --</option>
                                @foreach ($classes as $classe)
                                    <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                        {{ $classe->nom }} 
                                    </option>
                                @endforeach
                            </select>
                            @error('classe_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Boutons --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('matieres.index') }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success">Enregistrer</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
