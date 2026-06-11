@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-warning text-dark d-flex align-items-center">
            <i class="bi bi-pencil-square me-2 fs-4"></i>
            <h3 class="mb-0">Modifier Dépense</h3>
        </div>

        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('depenses.update', $depense) }}" method="POST">
                @csrf
                @method('PUT')
                @include('depenses.partials.form', ['depense' => $depense])

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-warning me-2">
                        <i class="bi bi-check-circle"></i> Mettre à jour
                    </button>
                    <a href="{{ route('depenses.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
