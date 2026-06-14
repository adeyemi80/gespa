@extends('tableau.neutre')

@section('content')
<button
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
    class="btn btn-secondary mb-3">
    ⬅️ Retour
</button>
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span><i class="bi bi-tag"></i> Tarifs TD</span>
            <a href="{{ route('td-tarifs.create') }}" class="btn btn-light btn-sm">
                <i class="bi bi-plus-lg"></i> Nouveau tarif
            </a>
        </div>

        {{-- Filtres --}}
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('td-tarifs.index') }}" class="row g-2 align-items-end">

                {{-- Année --}}
                <div class="col-md-3">
                    <label class="form-label mb-1 text-muted small">Année</label>
                    <select name="annee_id" class="form-select form-select-sm">
                        <option value="">Toutes les années</option>
                        @foreach($annees as $annee)
                            <option value="{{ $annee->id }}"
                                {{ $anneeId == $annee->id ? 'selected' : '' }}>
                                {{ $annee->libelle ?? $annee->nom ?? $annee->id }}
                                @if($annee->en_cours) ✅ @endif
                            </option>
                        @endforeach
                    </select>
                    @if($anneeEnCours)
                        <div class="form-text text-success" style="font-size:11px;">
                            <i class="bi bi-check-circle"></i>
                            {{ $anneeEnCours->libelle ?? $anneeEnCours->nom }}
                        </div>
                    @endif
                </div>

                {{-- Catégorie --}}
                <div class="col-md-3">
                    <label class="form-label mb-1 text-muted small">Catégorie</label>
                    <select name="categorie" class="form-select form-select-sm">
                        <option value="">Toutes</option>
                        @foreach(\App\Models\TdTarif::CATEGORIES as $val => $label)
                            <option value="{{ $val }}"
                                {{ request('categorie') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Type --}}
                <div class="col-md-2">
                    <label class="form-label mb-1 text-muted small">Type</label>
                    <select name="type" class="form-select form-select-sm">
                        <option value="">Tous</option>
                        @foreach(\App\Models\TdTarif::TYPES as $val => $label)
                            <option value="{{ $val }}"
                                {{ request('type') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Boutons --}}
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-funnel"></i> Filtrer
                    </button>
                    <a href="{{ route('td-tarifs.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-x-lg"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        {{-- Tableau --}}
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Année</th>
                        <th>Catégorie</th>
                        <th>Type</th>
                        <th class="text-end">Montant</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tarifs as $tarif)
                        <tr>
                            <td class="text-muted small">{{ $tarif->id }}</td>
                            <td>{{ $tarif->annee->libelle ?? $tarif->annee->nom ?? '—' }}</td>
                            <td>{{ $tarif->label_categorie }}</td>
                            <td>
                                <span class="badge
                                    @if($tarif->type === 'seance') bg-info text-dark
                                    @elseif($tarif->type === 'mois') bg-warning text-dark
                                    @else bg-success
                                    @endif">
                                    {{ $tarif->label_type }}
                                </span>
                            </td>
                            <td class="text-end fw-semibold">
                                {{ number_format($tarif->montant, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="text-end">
                                <a href="{{ route('td-tarifs.show', $tarif) }}"
                                   class="btn btn-sm btn-outline-info" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('td-tarifs.edit', $tarif) }}"
                                   class="btn btn-sm btn-outline-warning" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('td-tarifs.destroy', $tarif) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer ce tarif ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                                Aucun tarif trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tarifs->hasPages())
            <div class="card-footer">
                {{ $tarifs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection