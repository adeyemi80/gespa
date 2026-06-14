@extends('tableau.neutre')

@section('title', 'Paiements TD')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container-fluid py-4">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">Paiements TD</h4>
        <a href="{{ route('td-paiements.create', ['annee_id' => $annee_id]) }}"
           class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Nouveau paiement
        </a>
    </div>

    {{-- Alertes --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filtres --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('td-paiements.index') }}" class="row g-3 align-items-end">

                <div class="col-md-3">
                    <label class="form-label">Année scolaire</label>
                    <select name="annee_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Toutes</option>
                        @foreach($annees as $annee)
                            <option value="{{ $annee->id }}"
                                {{ $annee->id == $annee_id ? 'selected' : '' }}>
                                {{ $annee->libelle ?? $annee->nom ?? $annee->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Rechercher un élève</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control"
                           placeholder="Nom ou prénom…">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="bi bi-search me-1"></i> Filtrer
                    </button>
                </div>

                @if(request()->hasAny(['search', 'eleve_id']))
                    <div class="col-md-2">
                        <a href="{{ route('td-paiements.index', ['annee_id' => $annee_id]) }}"
                           class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-lg me-1"></i> Réinitialiser
                        </a>
                    </div>
                @endif

            </form>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Élève</th>
                        <th>Année</th>
                        <th class="text-end">Montant</th>
                        <th>Référence</th>
                        <th>Observation</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($query as $paiement)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</td>
                            <td>
                                <span class="fw-semibold">{{ $paiement->eleve->nom }}</span>
                                {{ $paiement->eleve->prenom }}
                            </td>
                            <td>{{ $paiement->annee->libelle ?? $paiement->annee->nom ?? $paiement->annee_id }}</td>
                            <td class="text-end fw-bold">
                                {{ number_format($paiement->montant, 0, ',', ' ') }} F
                            </td>
                            <td>{{ $paiement->reference ?? '—' }}</td>
                            <td class="text-muted small">
                                {{ \Illuminate\Support\Str::limit($paiement->observation, 40) ?? '—' }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('td-paiements.show', $paiement) }}"
                                   class="btn btn-sm btn-outline-secondary"
                                   title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('td-paiements.edit', $paiement) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('td-paiements.destroy', $paiement) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Supprimer ce paiement ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Aucun paiement trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($query->count())
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="fw-bold text-end">Total affiché :</td>
                            <td class="text-end fw-bold text-success">
                                {{ number_format($total, 0, ',', ' ') }} F
                            </td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $query->links() }}
    </div>

</div>
@endsection