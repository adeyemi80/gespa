@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Modifier le trimestre</h5>
                </div>
                <div class="card-body">

                    {{-- Affichage des erreurs --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
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

                    <form action="{{ route('trimestres.update', $trimestre->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Année --}}
                        <div class="mb-3">
                            <label>Année Scolaire</label>
                            <select name="annee_id" class="form-select" required>
                                <option value="">-- Choisir une année --</option>
                                @foreach($annees as $annee)
                                    <option value="{{ $annee->id }}"
                                        {{ old('annee_id', $trimestre->annees->first()->id ?? '') == $annee->id ? 'selected' : '' }}>
                                        {{ $annee->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Nom du trimestre --}}
                        <div class="mb-3">
                            <label>Nom du trimestre</label>
                            <select name="nom" class="form-select" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="Premier Trimestre" {{ old('nom', $trimestre->nom) == 'Premier Trimestre' ? 'selected' : '' }}>Premier Trimestre</option>
                                <option value="Deuxième Trimestre" {{ old('nom', $trimestre->nom) == 'Deuxième Trimestre' ? 'selected' : '' }}>Deuxième Trimestre</option>
                                <option value="Troisième Trimestre" {{ old('nom', $trimestre->nom) == 'Troisième Trimestre' ? 'selected' : '' }}>Troisième Trimestre</option>
                            </select>
                        </div>

                        {{-- Période --}}
                        <div class="mb-3">
                            <label>Période (ex : octobre-décembre)</label>
                            <input type="text" name="periode" class="form-control" placeholder="octobre-décembre"
                                value="{{ old('periode', $trimestre->periode) }}">
                            @error('periode')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Ordre --}}
                        <div class="mb-3">
                            <label>Ordre</label>
                            <select name="ordre" class="form-select" required>
                                <option value="1" {{ old('ordre', $trimestre->ordre) == 1 ? 'selected' : '' }}>1er</option>
                                <option value="2" {{ old('ordre', $trimestre->ordre) == 2 ? 'selected' : '' }}>2e</option>
                                <option value="3" {{ old('ordre', $trimestre->ordre) == 3 ? 'selected' : '' }}>3e</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Mettre à jour</button>
                        <a href="{{ route('trimestres.index') }}" class="btn btn-secondary mt-3">Annuler</a>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
