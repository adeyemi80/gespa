@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="p-4 bg-white shadow rounded">
        <h1 class="mb-4 text-warning">✏️ Modifier un parent</h1>

        {{-- Messages d'erreurs --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('parens.update', $paren) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input type="text"
                           name="nom_parent"
                           class="form-control"
                           value="{{ old('nom_parent', $paren->nom_parent) }}"
                           required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Prénom</label>
                    <input type="text"
                           name="prenom_parent"
                           class="form-control"
                           value="{{ old('prenom_parent', $paren->prenom_parent) }}"
                           required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <input type="text"
                           name="telephone_parent"
                           class="form-control"
                           value="{{ old('telephone_parent', $paren->telephone_parent) }}"
                           required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Adresse</label>
                    <input type="text"
                           name="adresse_parent"
                           class="form-control"
                           value="{{ old('adresse_parent', $paren->adresse_parent) }}">
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('parens.index') }}" class="btn btn-secondary">
                    ⬅ Retour à la liste
                </a>

                <button type="submit" class="btn btn-warning">
                    💾 Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
