{{-- resources/views/auth/reset-password.blade.php --}}
@extends('tableau.neutre')

@section('content')
<div class="container min-vh-100 d-flex justify-content-center align-items-center bg-light py-5">
    <div class="card shadow-lg border-0 rounded-4" style="max-width: 450px; width: 100%;">
        <div class="card-header bg-white border-0 text-center py-4">
            <div class="d-flex justify-content-center mb-3">
                <img src="{{ asset('images/hero.jpg') }}" alt="Logo" width="80" class="img-fluid">
            </div>
            <h4 class="fw-bold text-primary mb-0">Nouveau mot de passe</h4>
            <p class="text-muted small mt-1 mb-0">Choisis un nouveau mot de passe sécurisé</p>
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

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Adresse Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                           class="form-control form-control-lg rounded-3 shadow-sm" required autofocus autocomplete="username">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Nouveau mot de passe</label>
                    <div class="input-group input-group-lg">
                        <input id="password" type="password" name="password"
                               class="form-control rounded-start-3 shadow-sm" required autocomplete="new-password">
                        <button type="button" id="togglePassword" class="btn btn-outline-secondary rounded-end-3" tabindex="-1">
                            <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirmer le mot de passe</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                           class="form-control form-control-lg rounded-3 shadow-sm" required autocomplete="new-password">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg rounded-3 shadow-sm fw-semibold">
                        Réinitialiser le mot de passe
                    </button>
                </div>
            </form>
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

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon = document.getElementById('togglePasswordIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    });
</script>
@endsection