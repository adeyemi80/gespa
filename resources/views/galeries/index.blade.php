@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>📸 Galeries</h3>

        <a href="{{ route('galeries.create') }}"
           class="btn btn-primary">
            ➕ Nouvelle galerie
        </a>
    </div>

    <div class="row">

        @foreach($galeries as $galerie)

            <div class="col-12 col-md-4 mb-3">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <h5 class="card-title">
                            {{ $galerie->titre }}
                        </h5>

                        <p class="text-muted">
                            {{ $galerie->description }}
                        </p>

                        <a href="{{ route('galeries.show', $galerie->id) }}"
                           class="btn btn-sm btn-dark">
                            Voir
                        </a>

                        <a href="{{ route('galeries.edit', $galerie) }}"
                           class="btn btn-sm btn-warning">
                            Modifier
                        </a>

                        <form action="{{ route('galeries.destroy', $galerie) }}"
                              method="POST"
                              class="d-inline">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Supprimer cette galerie ?')">
                                Supprimer
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>

@endsection