@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container-fluid py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h3 class="fw-bold mb-1">
                📂 {{ $galerie->titre }}
            </h3>

            <p class="text-muted mb-0">
                {{ $galerie->description }}
            </p>
        </div>

        <div>
            <a href="{{ route('medias.create', $galerie->id) }}"
               class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>
                Ajouter un média
            </a>

            <a href="{{ route('galeries.index') }}"
               class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Retour
            </a>
        </div>

    </div>

    <hr>

    {{-- Médias --}}
    <div class="row g-4">

        @forelse($medias as $media)

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-2">

                        {{-- IMAGE --}}
                        @if($media->type === 'image')

                            <img src="{{ asset('storage/'.$media->fichier) }}"
                                 class="img-fluid rounded w-100"
                                 style="height:250px; object-fit:cover;">

                        {{-- VIDEO --}}
                        @else

                            <video controls
                                   class="w-100 rounded"
                                   style="height:250px; object-fit:cover;">

                                <source src="{{ asset('storage/'.$media->fichier) }}">

                                Votre navigateur ne supporte pas la vidéo.

                            </video>

                        @endif

                    </div>

                    {{-- Footer --}}
                    <div class="card-footer bg-white border-0">

                        <small class="text-muted d-block mb-2">
                            {{ $media->titre ?? 'Sans titre' }}
                        </small>

                        <div class="d-flex justify-content-between">

                            <a href="{{ route('medias.show', $media->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                Voir
                            </a>

                            <form action="{{ route('medias.destroy', $media->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Supprimer ce média ?')">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-outline-danger">
                                    Supprimer
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">

                <div class="alert alert-info text-center shadow-sm">

                    <h5 class="mb-1">
                        📭 Aucun média dans cette galerie
                    </h5>

                    <p class="mb-0">
                        Cliquez sur “Ajouter un média” pour commencer.
                    </p>

                </div>

            </div>

        @endforelse

    </div>

</div>

@endsection