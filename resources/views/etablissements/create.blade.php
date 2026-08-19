@extends('tableau.neutre')

@section('title', 'Nouvel Établissement')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="mb-4">
        <a href="{{ route('etablissements.index') }}" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left me-2"></i>Retour à la liste
        </a>
        
        <h1 class="h3 fw-bold text-dark">
            <i class="bi bi-building me-2"></i>Créer un nouvel établissement
        </h1>
        <small class="text-muted">Enregistrez les informations de votre établissement</small>
    </div>

    <!-- Formulaire -->
    <form action="{{ route('etablissements.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf

        @include('etablissements.form', ['etablissement' => null])
    </form>
</div>

@endsection

@push('scripts')
<script>
    // Validation Bootstrap
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    }());
</script>
@endpush