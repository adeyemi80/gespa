@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">✏️ Éditer Type</h4>
        </div>
        <div class="card-body">

            {{-- Erreurs --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('types.update', $type) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nom" class="form-label fw-bold">Nom du type</label>
                    <input type="text" id="nom" name="nom" class="form-control" value="{{ old('nom', $type->nom) }}" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">✅ Mettre à jour</button>
                    <a href="{{ route('types.index') }}" class="btn btn-secondary">🔙 Retour</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
