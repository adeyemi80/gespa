@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">

    <h3>📄 Média</h3>

    <p>
        <strong>{{ $media->titre }}</strong>
    </p>

    @if($media->type == 'image')

        <img src="{{ asset('storage/'.$media->fichier) }}"
             class="img-fluid rounded shadow">

    @else

        <video controls class="w-100 rounded shadow">
            <source src="{{ asset('storage/'.$media->fichier) }}">
        </video>

    @endif

    <hr>

    <a href="{{ route('galeries.show', $media->galerie_id) }}"
       class="btn btn-secondary">
        Retour galerie
    </a>

</div>

@endsection