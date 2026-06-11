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
                    Modifier l'année scolaire
                </div>
                <div class="float-end">
                    <a href="{{ route('annees.index') }}" class="btn btn-primary btn-sm">&larr; Retour</a>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('annees.update', $annee) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nom --}}
                    <p>
                        <div class="mb-3 row">
                            <label for="nom" class="col-md-4 col-form-label text-md-end text-start">Nom</label>
                            <div class="col-md-6">
                                <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                       value="{{ old('nom', $annee->nom) }}" required>
                                @error('nom')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </p>

                    {{-- Date début --}}
                    <p>
                        <div class="mb-3 row">
                            <label for="debut" class="col-md-4 col-form-label text-md-end text-start">Date de début</label>
                            <div class="col-md-6">
                                <input type="date" name="debut" class="form-control @error('debut') is-invalid @enderror"
                                       value="{{ old('debut', $annee->debut) }}" required>
                                @error('debut')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </p>

                    {{-- Date fin --}}
                    <p>
                        <div class="mb-3 row">
                            <label for="fin" class="col-md-4 col-form-label text-md-end text-start">Date de fin</label>
                            <div class="col-md-6">
                                <input type="date" name="fin" class="form-control @error('fin') is-invalid @enderror"
                                       value="{{ old('fin', $annee->fin) }}" required>
                                @error('fin')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </p>

                    {{-- En cours --}}
                    <p>
                        <div class="mb-3 row">
                            <label for="active" class="col-md-4 col-form-label text-md-end text-start">En cours</label>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="active" value="1"
                                           id="active" {{ old('active', $annee->active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active">
                                        Oui (coche si l’année est active)
                                    </label>
                                </div>
                                @error('active')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </p>

                    {{-- Bouton --}}
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-4 offset-md-4 btn btn-success" value="Mettre à jour">
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

{{-- Disparition auto du message flash --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const alert = document.getElementById('success-alert');
        if (alert) {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 4000);
        }
    });
</script>

@endsection
