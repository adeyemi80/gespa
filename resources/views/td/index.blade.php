@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">

            <h2 class="mb-3 text-primary">Gestion des TD</h2>
            {{-- ✅ Message de succès --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

            <p><strong>Année en cours :</strong> {{ $annee->nom }}</p>
            <p><strong>Date :</strong> {{ $date }}</p>

            <form action="{{ route('td.charger-classe') }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-3">
                    <label for="classe_id" class="form-label">Sélectionnez une classe</label>
                    <select name="classe_id" id="classe_id" class="form-select" required>
                        <option value="">-- Choisir --</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Afficher les élèves</button>
            </form>
        </div>
    </div>
</div>
@endsection
