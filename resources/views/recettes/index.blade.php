{{-- resources/views/recettes/index.blade.php --}}
@extends('tableau.neutre')

@section('content')
<div class="container-fluid py-4">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h1 class="h3 fw-bold mb-1">Recettes</h1>
            <p class="text-muted mb-0 small">Liste des paiements et encaissements enregistrés</p>
        </div>
        {{--<a href="{{ route('recettes.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Nouvelle recette
        </a>--}}
    </div>

    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Filtres --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('recettes.index') }}" class="row g-3 align-items-end">
                <div class="col-auto">
                    <label class="form-label small text-muted mb-1">Année</label>
                    <select name="annee_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Toutes les années</option>
                        @foreach ($annees as $annee)
                            <option value="{{ $annee->id }}" @selected(request('annee_id') == $annee->id)>
                                {{ $annee->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto">
                    <label class="form-label small text-muted mb-1">Catégorie</label>
                    <select name="categorie_recette_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Toutes les catégories</option>
                        @foreach ($categories as $categorie)
                            <option value="{{ $categorie->id }}" @selected(request('categorie_recette_id') == $categorie->id)>
                                {{ $categorie->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if (request('annee_id') || request('categorie_recette_id'))
                    <div class="col-auto">
                        <a href="{{ route('recettes.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-1"></i> Réinitialiser
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="table-light">
                        <th class="ps-4 py-3 text-muted small text-uppercase" style="letter-spacing:.03em;">Date</th>
                        <th class="py-3 text-muted small text-uppercase" style="letter-spacing:.03em;">Catégorie</th>
                        <th class="py-3 text-muted small text-uppercase" style="letter-spacing:.03em;">Année</th>
                        <th class="py-3 text-muted small text-uppercase" style="letter-spacing:.03em;">Mode paiement</th>
                        <th class="py-3 text-muted small text-uppercase" style="letter-spacing:.03em;">N° reçu</th>
                        <th class="py-3 text-end text-muted small text-uppercase" style="letter-spacing:.03em;">Montant versé</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recettes as $recette)
                        <tr>
                            <td class="ps-4">{{ $recette->date_paiement->format('d/m/Y') }}</td>
                            <td>
                                @if ($recette->categorieRecette)
                                    <span class="badge bg-primary-subtle text-primary-emphasis fw-semibold">
                                        {{ $recette->categorieRecette->nom }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $recette->annee?->nom ?? '—' }}</td>
                            <td>
                                @if ($recette->mode_paiement)
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                        {{ $recette->mode_paiement }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $recette->numero_recu ?? '—' }}</td>
                            <td class="text-end fw-semibold text-success">
                                {{ number_format($recette->montant_verse, 2, ',', ' ') }}
                            </td>
                            {{--<td class="pe-4 text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('recettes.show', $recette) }}" class="btn btn-outline-primary" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('recettes.edit', $recette) }}" class="btn btn-outline-warning" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('recettes.destroy', $recette) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Supprimer cette recette ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>--}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                                Aucune recette trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4 d-flex justify-content-center">
        {{ $recettes->links() }}
    </div>
</div>
@endsection