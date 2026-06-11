@extends('tableau.neutre')

@section('title', 'Ajouter un élève')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- Message de succès --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 w-50 text-center shadow" role="alert" style="z-index: 1050;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
            {{-- Messages erreurs --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4">
                    <h6>⚠️ Veuillez corriger les erreurs :</h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0">👶 Nouvel élève</h4>
                </div>

                <div class="card-body p-4">

                    {{-- ⚠️ IMPORTANT --}}
                    <form action="{{ route('eleves.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- 📸 PHOTO --}}
                        <div class="text-center mb-4">
                            <div class="mb-2 text-muted">Photo de l'élève</div>
                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror">
                            @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- 👦 IDENTITÉ --}}
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nom *</label>
                                <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" 
                                       value="{{ old('nom') }}" required>
                                @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Prénom *</label>
                                <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror" 
                                       value="{{ old('prenom') }}" required>
                                @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Numéro EducMaster</label>
                                <input type="text" name="numeducmaster" class="form-control @error('numeducmaster') is-invalid @enderror" 
                                       value="{{ old('numeducmaster') }}">
                                @error('numeducmaster') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- 📅 DATE & SEXE --}}
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Date naissance</label>
                                <input type="date" name="date_naissance" class="form-control @error('date_naissance') is-invalid @enderror" 
                                       value="{{ old('date_naissance') }}">
                                @error('date_naissance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Sexe *</label>
                                <select name="sexe" class="form-select @error('sexe') is-invalid @enderror" required>
                                    <option value="">-- Choisir --</option>
                                    <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>👨 Masculin</option>
                                    <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>👩 Féminin</option>
                                </select>
                                @error('sexe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nationalité *</label>
                                <input type="text" name="nationalite" class="form-control @error('nationalite') is-invalid @enderror" 
                                       value="{{ old('nationalite', 'Béninoise') }}" required>
                                @error('nationalite') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- 📍 LOCALISATION --}}
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Lieu naissance *</label>
                                <input type="text" name="lieu_naissance" class="form-control @error('lieu_naissance') is-invalid @enderror" 
                                       value="{{ old('lieu_naissance', 'Cotonou') }}" required>
                                @error('lieu_naissance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- 🏫 CYCLE / CLASSE / STATUT / ANNÉE --}}
                        <div class="row mb-4">

                            <div class="col-md-3">
                                <label>Cycle</label>
                                <select id="cycle" class="form-select">
                                    <option value="">-- Choisir --</option>
                                    @foreach($cycles as $cycle)
                                        <option value="{{ $cycle->id }}">{{ $cycle->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Classe *</label>
                                <select id="classe" name="classe_id" class="form-select @error('classe_id') is-invalid @enderror" required>
                                    <option value="">-- Choisir une classe --</option>
                                </select>
                                @error('classe_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-2">
                                <label>Statut *</label>
                                <select name="statut" class="form-select @error('statut') is-invalid @enderror" required>
                                    <option value="passant">Passant</option>
                                    <option value="redoublant">Redoublant</option>
                                </select>
                            </div>

                           <div class="col-md-4">
    <label>Année *</label>

    <select name="annee_id"
            class="form-select @error('annee_id') is-invalid @enderror"
            required>

        @if($anneeEnCours)
            <option value="{{ $anneeEnCours->id }}" selected>
                {{ $anneeEnCours->nom }}
            </option>
        @else
            <option value="">
                Sélectionner une année
            </option>
        @endif

    </select>
</div>

                        </div>

                        {{-- Bouton --}}
                        <div class="text-end">
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="bi bi-plus-circle"></i> Ajouter élève
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script>
document.getElementById('cycle').addEventListener('change', function () {
    let cycleId = this.value;
    let classeSelect = document.getElementById('classe');

    classeSelect.innerHTML = '<option>Chargement...</option>';

    if (cycleId) {
        fetch(`/cycles/${cycleId}/classes`)
            .then(res => res.json())
            .then(data => {
                classeSelect.innerHTML = '<option value="">-- Choisir une classe --</option>';
                data.forEach(classe => {
                    classeSelect.innerHTML += `<option value="${classe.id}">${classe.nom}</option>`;
                });
            });
    } else {
        classeSelect.innerHTML = '<option value="">-- Choisir une classe --</option>';
    }
});
</script>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection