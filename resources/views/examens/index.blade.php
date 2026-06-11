@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">
    <h2>Liste des examens blancs</h2>

    <a href="{{ route('examens-blancs.create') }}" class="btn btn-primary mb-3">
        + Nouvel examen
    </a>
 @csrf
                {{-- Message de succès --}}
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 w-75 text-center" role="alert" style="z-index: 1050;">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
    @foreach($examens as $examen)
        <div class="card mb-3">
            <div class="card-body">
                <h5>{{ $examen->type }} | {{ $examen->date_debut }} → {{ $examen->date_fin }}</h5>

                <p>
                    Classes :
                    @foreach($examen->classes as $classe)
                        <span class="badge bg-info">{{ $classe->niveau }}</span>
                    @endforeach
                </p>

                <a href="{{ route('examens-blancs.show', $examen->id) }}" class="btn btn-primary">PARTICIPANTS & RESULTATS</a>
                <a href="{{ route('examens-blancs.edit', $examen->id) }}" class="btn btn-primary">MODIFIER LA DATE DE L'EB</a>

                <form action="{{ route('examens-blancs.destroy', $examen->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger"> SUPPRIMER L'EB</button>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection