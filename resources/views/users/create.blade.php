@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container min-vh-100 d-flex justify-content-center align-items-center bg-light py-5">
    <div class="card shadow-lg border-0 rounded-4" style="max-width: 600px; width: 100%;">
        <div class="card-header bg-white border-0 text-center py-4">
            <div class="d-flex justify-content-center mb-3">
                <img src="{{ asset('images/hero.jpg') }}" alt="Logo" width="80" class="img-fluid">
            </div>
            <h4 class="fw-bold text-primary mb-0">Création un Compte</h4>
            <p class="text-muted small mt-1 mb-0">Veuillez renseigner vos informations</p>
        </div>
 {{-- Message de succès --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            ✅ {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

        <div class="card-body px-4 py-4">
            {{-- Affichage des erreurs --}}
            @if ($errors->any())
                <div class="alert alert-danger rounded-3">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('users.store') }}" class="needs-validation" novalidate>
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nom" class="form-label fw-semibold">Nom</label>
                        <input id="nom" type="text" name="nom" value="{{ old('nom') }}"
                               class="form-control form-control-lg rounded-3 shadow-sm" required autofocus autocomplete="nom">
                    </div>

                   <div class="col-md-6 mb-3">
                        <label for="prenom" class="form-label fw-semibold">Prénom</label>
                        <input id="prenom" type="text" name="prenom" value="{{ old('prenom') }}"
                               class="form-control form-control-lg rounded-3 shadow-sm" required autocomplete="prenom">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                    <label for="email" class="form-label fw-semibold">Adresse Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           class="form-control form-control-lg rounded-3 shadow-sm" required autocomplete="username">
                </div>

                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label fw-semibold">Mot de Passe</label>
                        <input id="password" type="password" name="password"
                               class="form-control form-control-lg rounded-3 shadow-sm" required autocomplete="new-password">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirmer le Mot de Passe</label>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                               class="form-control form-control-lg rounded-3 shadow-sm" required autocomplete="new-password">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label fw-semibold">Rôle</label>
                    <select name="role" id="role" class="form-select form-select-lg rounded-3 shadow-sm" required>
                        <option value="">-- Choisir un rôle --</option>
                        <option value="admin">Administrateur</option>
                        <option value="enseignant">Enseignant</option>
                        <option value="secretaire">Secrétaire</option>
                        <option value="comptable">Comptable</option>
                        <option value="directeur">Directeur</option>
                        <option value="censeur">Censeur</option>
                        <option value="surveillant">Surveillant</option>
                        <option value="parent">Parent</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('login') }}" class="text-decoration-none small text-primary fw-semibold">
                        Déjà inscrit ?
                    </a>

                    <button type="submit" class="btn btn-primary btn-lg rounded-3 shadow-sm fw-semibold px-4">
                        Créer le compte
                    </button>
                </div>
            </form>
        </div>

        <div class="card-footer bg-white text-center border-0 py-3">
            <small class="text-muted">© {{ date('Y') }} — Votre École | Tous droits réservés</small>
        </div>
    </div>
</div>

{{-- Style moderne et raffiné --}}
<style>
    body {
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        font-family: 'Poppins', sans-serif;
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .btn-primary {
        background: linear-gradient(90deg, #0d6efd, #007bff);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(90deg, #0056b3, #004085);
    }
</style>
@endsection
