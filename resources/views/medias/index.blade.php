@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<!--<marquee class="marquee-content" scrollamount="1" behavior="scroll" direction="up" width="1100" height="500">-->
<div class="container-fluid py-3">

    <h3 class="fw-bold mb-4">
        📁 Toutes les galeries
    </h3>

    @foreach($galeries as $galerie)

        <div class="card shadow-sm border-0 mb-5">

            {{-- HEADER GALERIE --}}
            <div class="card-header bg-primary text-white">

                <h4 class="mb-0">
                    📂 {{ $galerie->titre }}
                </h4>

            </div>

            {{-- MEDIAS --}}
            <div class="card-body">

                <div class="row g-4">

                    @forelse($galerie->medias as $media)

                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">

                            <div class="card h-100 border-0 shadow-sm">

                                {{-- IMAGE --}}
                                @if($media->type == 'image')

                                    <img src="{{ asset('storage/'.$media->fichier) }}"
                                         class="card-img-top"
                                         style="
                                            height:250px;
                                            object-fit:cover;
                                         ">

                                {{-- VIDEO --}}
                                @else

                                    <video controls
                                           class="w-100 rounded-top"
                                           style="
                                                height:250px;
                                                object-fit:cover;
                                           ">

                                        <source src="{{ asset('storage/'.$media->fichier) }}">

                                    </video>

                                @endif

                                {{-- TITRE MEDIA --}}
                                <div class="card-footer text-center bg-white">

                                    <small class="fw-bold">
                                        {{ $media->titre ?? 'Sans titre' }}
                                    </small>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="col-12">

                            <div class="alert alert-info text-center">

                                Aucun média dans cette galerie.

                            </div>

                        </div>

                    @endforelse

                </div>

            </div>

            {{-- DESCRIPTION GALERIE --}}
            <div class="card-footer bg-light">

                <p class="mb-0 text-muted">
                    {{ $galerie->description }}
                </p>

            </div>

        </div>

    @endforeach

</div>
<!--</marquee>-->
@endsection