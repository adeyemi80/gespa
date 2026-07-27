{{-- resources/views/auth/forgot-password.blade.php --}}
@extends('tableau.neutre')

@section('content')
<div class="container min-vh-100 d-flex justify-content-center align-items-center bg-light py-5">
    <div class="card shadow-lg border-0 rounded-4" style="max-width: 450px; width: 100%;">
        <div class="card-header bg-white border-0 text-center py-4">
            <div class="d-flex justify-content-center mb-3">
                <img src="{{ asset('images/hero.jpg') }}" alt="Logo" width="80" class="img-fluid">
            </div>
            <h4 class="fw-bold text-primary mb-0">Mot de passe oublié</h4>
            <p class="text-muted small mt-1 mb-0">
                Indique ton adresse email, un lien de réinitialisation te sera envoyé.
            </p>
        </div>

        <div class="card-body px-4 py-4">
            @if ($errors->any())
                <div class="alert alert-danger rounded-3">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success rounded-3">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Adresse Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           class="form-control form-control-lg rounded-3 shadow-sm" required autofocus>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg rounded-3 shadow-sm fw-semibold">
                        Envoyer le lien de réinitialisation
                    </button>
                </div>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none small text-primary fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Retour à la connexion
                </a>
            </div>
        </div>

        <div class="card-footer bg-white text-center border-0 py-3">
            <small class="text-muted">© {{ date('Y') }} — Votre École | Tous droits réservés</small>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        font-family: 'Poppins', sans-serif;
    }
    .card { transition: all 0.3s ease; }
    .card:hover { transform: translateY(-5px); }
    .btn-primary {
        background: linear-gradient(90deg, #0d6efd, #007bff);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(90deg, #0056b3, #004085);
    }
</style>
@endsection