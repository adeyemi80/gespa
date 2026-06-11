@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="row justify-content-center mt-3">
    <div class="col-md-8">

        @if (session('success'))
            <div id="success-alert" class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card text-bg-dark">
            <div class="card-header">
                <div class="float-start">
                    Ajouter une année scolaire
                </div>
                {{--<div class="float-end">
                    <a href="{{ route('annees.index') }}" class="btn btn-primary btn-sm">&larr; Retour</a>
                </div>--}}
            </div>

            <div class="card-body">
                <form action="{{ route('annees.store') }}" method="POST">
                    @csrf

                    {{-- Nom --}}
                    <p>
                        <div class="mb-3 row">
                            <label for="nom" class="col-md-4 col-form-label text-md-end text-start">Nom</label>
                            <div class="col-md-6">
                                <input type="text" id="nom" name="nom"
                                       class="form-control @error('nom') is-invalid @enderror"
                                       value="{{ old('nom') }}" required>
                                @error('nom')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </p>

                    {{-- Début --}}
                    <p>
                        <div class="mb-3 row">
                            <label for="debut" class="col-md-4 col-form-label text-md-end text-start">Date de début</label>
                            <div class="col-md-6">
                                <input type="date" id="debut" name="debut"
                                       class="form-control @error('debut') is-invalid @enderror"
                                       value="{{ old('debut') }}" required>
                                @error('debut')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </p>

                    {{-- Fin --}}
                    <p>
                        <div class="mb-3 row">
                            <label for="fin" class="col-md-4 col-form-label text-md-end text-start">Date de fin</label>
                            <div class="col-md-6">
                                <input type="date" id="fin" name="fin"
                                       class="form-control @error('fin') is-invalid @enderror"
                                       value="{{ old('fin') }}" required>
                                @error('fin')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </p>

                    {{-- En Cours --}}
                    <p>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">Année en cours</label>
                            <div class="col-md-6 d-flex align-items-center">
                                <input type="checkbox" name="en_cours" value="1"
                                       {{ old('en_cours', false) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </p>

                    {{-- Bouton enregistrer --}}
                    <div class="mb-3 row">
                        <input type="submit" value="Enregistrer" class="col-md-3 offset-md-5 btn btn-success">
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

{{-- Disparition auto du message de succès --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const alert = document.getElementById('success-alert');
        if (alert) {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = 0;
                setTimeout(() => alert.remove(), 500);
            }, 4000);
        }
    });
</script>

@endsection
