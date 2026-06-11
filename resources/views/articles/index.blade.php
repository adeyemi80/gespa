@extends('classes.layout')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>📦 Articles / Stock</h3>
        <a href="{{ route('articles.create') }}" class="btn btn-primary">➕ Ajouter Article</a>
    </div>

    {{-- Alertes de stock --}}
    @php
        $alertes = $articles->filter(fn($a) => $a->quantite <= $a->seuil_alerte);
    @endphp
    @if($alertes->count())
        <div class="alert alert-warning">
            ⚠️ Articles en dessous du seuil : 
            {{ $alertes->pluck('nom')->join(', ') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Quantité</th>
                    <th>Seuil d'alerte</th>
                    <th>Description</th>
                    <th>Mouvements</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                <tr>
                    <td>{{ $article->id }}</td>
                    <td>{{ $article->nom }}</td>
                    <td>{{ $article->type?->nom ?? '—' }}</td>
                    <td>{{ $article->quantite }}</td>
                    <td>{{ $article->seuil_alerte }}</td>
                    <td>{{ $article->description }}</td>
                    <td>
                        <ul class="list-unstyled mb-0">
                            @foreach($article->mouvements as $m)
                                <li>
                                    <strong>{{ ucfirst($m->type) }}</strong> : {{ $m->quantite }} 
                                    (({{ \Carbon\Carbon::parse($m->date_mouvement)->format('d/m/Y') }})
)
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="d-flex gap-1 justify-content-center">
                        <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-warning">✏️ Edit</a>

                        <form method="POST" action="{{ route('articles.destroy', $article) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Supprimer cet article ?')">🗑️</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-muted">Aucun article trouvé</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
