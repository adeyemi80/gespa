@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>📂 Détails du Type : {{ $type->nom }}</h3>
        <a href="{{ route('types.index') }}" class="btn btn-secondary">🔙 Retour</a>
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold">Nom du type :</h5>
            <p>{{ $type->nom }}</p>
        </div>
    </div>

    <h5 class="mb-3">Articles associés</h5>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-primary text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nom de l'article</th>
                        <th>Quantité</th>
                        <th>Seuil d'alerte</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($type->articles as $article)
                        <tr class="text-center">
                            <td>{{ $article->id }}</td>
                            <td>{{ $article->nom }}</td>
                            <td>{{ $article->quantite }}</td>
                            <td>{{ $article->seuil_alerte }}</td>
                            <td>{{ $article->description ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr class="text-center">
                            <td colspan="5" class="text-muted">Aucun article associé à ce type.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
