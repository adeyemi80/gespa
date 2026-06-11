@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">

    <div class="card shadow-sm">
        <div class="card-body text-center">

            <img src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('images/default.png') }}"
                 width="120" class="rounded-circle mb-3">

            <h4>{{ $user->nom }} {{ $user->prenom }}</h4>
            <p class="text-muted">{{ $user->email }}</p>

            <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>

            <div class="mt-3">
                <a href="{{ route('profil.edit', $user->id) }}" class="btn btn-warning">✏️ Modifier</a>
                <a href="{{ route('profil.index') }}" class="btn btn-secondary">Retour</a>
            </div>

        </div>
    </div>

</div>
@endsection