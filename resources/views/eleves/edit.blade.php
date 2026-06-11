@extends('tableau.neutre')

@section('title', 'Modifier un élève')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- Message succès --}}
            @if (session('success'))
                <div id="success-alert" class="alert alert-success alert-dismissible fade show mb-4">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">✏️ Modifier {{ $eleve->nom }} {{ $eleve->prenom }}</h4>
                       <div class="col-md-4">
    <label class="form-label fw-bold">Matricule</label>
    <input type="text"
           name="matricule"
           class="form-control"
           value="{{ old('matricule', $eleve->matricule) }}"
           readonly>
</div>
                    </div>
                    <a href="{{ route('eleves.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> Liste élèves
                    </a>
                </div>

                <div class="card-body p-4">

                    {{-- ⚠️ IMPORTANT POUR UPLOAD --}}
                    <form action="{{ route('eleves.update', $eleve) }}" method="POST" enctype="multipart/form-data">
                        @csrf 
                        @method('PUT')

                        {{-- 📸 PHOTO --}}
                        <div class="text-center mb-4">
                            @if($eleve->photo)
                                <img src="{{ asset('storage/' . $eleve->photo) }}" width="120" class="rounded shadow mb-2">
                            @else
                                <div class="text-muted mb-2">Aucune photo</div>
                            @endif

                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror">
                            @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- 👦 IDENTITÉ --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" 
                                       value="{{ old('nom', $eleve->nom) }}" required>
                                @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Prénom <span class="text-danger">*</span></label>
                                <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror" 
                                       value="{{ old('prenom', $eleve->prenom) }}" required>
                                @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- 📅 DATE & SEXE --}}
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Date naissance</label>
                                <input type="date" name="date_naissance" class="form-control @error('date_naissance') is-invalid @enderror" 
                                       value="{{ old('date_naissance', $eleve->date_naissance) }}">
                                @error('date_naissance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Sexe <span class="text-danger">*</span></label>
                                <select name="sexe" class="form-select @error('sexe') is-invalid @enderror" required>
                                    <option value="">-- Choisir --</option>
                                    <option value="M" {{ old('sexe', $eleve->sexe) == 'M' ? 'selected' : '' }}>👨 Masculin</option>
                                    <option value="F" {{ old('sexe', $eleve->sexe) == 'F' ? 'selected' : '' }}>👩 Féminin</option>
                                </select>
                                @error('sexe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nationalité <span class="text-danger">*</span></label>
                                <input type="text" name="nationalite" class="form-control @error('nationalite') is-invalid @enderror" 
                                       value="{{ old('nationalite', $eleve->nationalite) }}" required>
                                @error('nationalite') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- 📍 LOCALISATION --}}
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Lieu naissance <span class="text-danger">*</span></label>
                                <input type="text" name="lieu_naissance" class="form-control @error('lieu_naissance') is-invalid @enderror" 
                                       value="{{ old('lieu_naissance', $eleve->lieu_naissance) }}" required>
                                @error('lieu_naissance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Matricule</label>
                                <input type="text" class="form-control" value="{{ $eleve->matricule }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Numero EDUCMASTER</label>
                                <input type="text" class="form-control" value="{{ $eleve->numeducmaster }}" readonly>
                            </div>
                        </div>

                        {{-- 🏫 CLASSE & STATUT --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Classe <span class="text-danger">*</span></label>
                                <select name="classe_id" class="form-select @error('classe_id') is-invalid @enderror" required>
                                    @foreach($classes as $classe)
                                        <option value="{{ $classe->id }}" {{ old('classe_id', $eleve->classe_id) == $classe->id ? 'selected' : '' }}>
                                            {{ $classe->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('classe_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Statut <span class="text-danger">*</span></label>
                                <select name="statut" class="form-select @error('statut') is-invalid @enderror" required>
                                    <option value="passant" {{ old('statut', $eleve->statut) == 'passant' ? 'selected' : '' }}>Passant</option>
                                    <option value="redoublant" {{ old('statut', $eleve->statut) == 'redoublant' ? 'selected' : '' }}>Redoublant</option>
                                </select>
                                @error('statut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Année <span class="text-danger">*</span></label>
                                <select name="annee_id" class="form-select @error('annee_id') is-invalid @enderror" required>
                                    @foreach($annees as $annee)
                                        <option value="{{ $annee->id }}" {{ old('annee_id', $eleve->annee_id) == $annee->id ? 'selected' : '' }}>
                                            {{ $annee->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('annee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Boutons --}}
                        <div class="text-end">
                            <button type="submit" class="btn btn-success btn-lg px-5 me-3">
                                <i class="bi bi-check-lg"></i> Mettre à jour
                            </button>
                            <a href="{{ route('eleves.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="bi bi-x-lg"></i> Annuler
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const alert = document.getElementById('success-alert');
    if (alert) setTimeout(() => alert.classList.remove('show'), 5000);
});
</script>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection