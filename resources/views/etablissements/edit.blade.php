@extends('tableau.neutre')

@section('title', 'Modifier: ' . $etablissement->nom)

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="mb-4">
        <a href="{{ route('etablissements.index') }}" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left me-2"></i>Retour à la liste
        </a>
        
        <h1 class="h3 fw-bold text-dark">
            <i class="bi bi-pencil me-2"></i>Modifier: {{ $etablissement->nom }}
        </h1>
        <small class="text-muted">Mettez à jour les informations de l'établissement</small>
    </div>

    <!-- Messages de succès -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Formulaire -->
    <form action="{{ route('etablissements.update', $etablissement) }}" method="POST" class="needs-validation" novalidate>
        @csrf
        @method('PUT')

        @include('etablissements.form', ['etablissement' => $etablissement])
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