@extends('tableau.neutre')

@section('title', 'Élèves externes TD')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container-fluid py-4">

    {{-- En-tête --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-primary mb-1">
                <i class="fas fa-user-graduate me-2"></i>
                Gestion des élèves externes TD
            </h3>
            <p class="text-muted mb-0">
                Gestion des élèves participant aux travaux dirigés hors inscription scolaire classique.
            </p>
        </div>

        <a href="{{ route('eleves-externes-td.create') }}"
           class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-user-plus me-2"></i>
            Nouvel élève externe
        </a>
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <strong>Une erreur est survenue.</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Statistiques --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="fas fa-users text-primary fa-lg"></i>
                    </div>

                    <div>
                        <div class="text-muted small">Résultats affichés</div>
                        <div class="fs-4 fw-bold">
                            {{ $eleves->total() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="fas fa-user-check text-success fa-lg"></i>
                    </div>

                    <div>
                        <div class="text-muted small">Élèves actifs dans les résultats</div>
                        <div class="fs-4 fw-bold">
                            {{ $eleves->getCollection()->where('actif', true)->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-secondary bg-opacity-10 p-3 me-3">
                        <i class="fas fa-user-slash text-secondary fa-lg"></i>
                    </div>

                    <div>
                        <div class="text-muted small">Élèves inactifs dans les résultats</div>
                        <div class="fs-4 fw-bold">
                            {{ $eleves->getCollection()->where('actif', false)->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Recherche et filtres --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-light border-0 rounded-top-4 py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-search me-2 text-primary"></i>
                Recherche et filtres
            </h5>
        </div>

        <div class="card-body">

            <form method="GET" action="{{ route('eleves-externes-td.index') }}">

                <div class="row g-3 align-items-end">

                    <div class="col-lg-6">
                        <label for="recherche" class="form-label fw-semibold">
                            Rechercher un élève
                        </label>

                        <input
                            type="text"
                            name="recherche"
                            id="recherche"
                            class="form-control"
                            placeholder="Matricule, nom, prénom ou téléphone..."
                            value="{{ request('recherche') }}"
                        >
                    </div>

                    <div class="col-lg-3">
                        <label for="sexe" class="form-label fw-semibold">
                            Sexe
                        </label>

                        <select name="sexe" id="sexe" class="form-select">
                            <option value="">Tous les sexes</option>
                            <option value="Masculin" @selected(request('sexe') === 'Masculin')>
                                Masculin
                            </option>
                            <option value="Féminin" @selected(request('sexe') === 'Féminin')>
                                Féminin
                            </option>
                            <option value="M" @selected(request('sexe') === 'M')>
                                M
                            </option>
                            <option value="F" @selected(request('sexe') === 'F')>
                                F
                            </option>
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <label for="actif" class="form-label fw-semibold">
                            Statut
                        </label>

                        <select name="actif" id="actif" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="1" @selected(request('actif') === '1')>
                                Actif
                            </option>
                            <option value="0" @selected(request('actif') === '0')>
                                Inactif
                            </option>
                        </select>
                    </div>

                    <div class="col-12 d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>
                            Rechercher
                        </button>

                        <a href="{{ route('eleves-externes-td.index') }}"
                           class="btn btn-outline-secondary">
                            <i class="fas fa-redo me-1"></i>
                            Réinitialiser
                        </a>
                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- Tableau des élèves --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-list me-2 text-primary"></i>
                    Liste des élèves externes
                </h5>

                <span class="badge bg-primary rounded-pill">
                    {{ $eleves->total() }} résultat(s)
                </span>
            </div>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">N°</th>
                            <th>Matricule</th>
                            <th>Nom et prénom</th>
                            <th>Sexe</th>
                            <th>Téléphone</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($eleves as $eleve)

                            <tr>
                                <td class="ps-3">
                                    {{ $eleves->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $eleve->matricule }}
                                    </span>
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $eleve->nom }} {{ $eleve->prenom }}
                                    </div>

                                    @if($eleve->date_naissance)
                                        <small class="text-muted">
                                            Né(e) le
                                            {{ $eleve->date_naissance->format('d/m/Y') }}
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    {{ $eleve->sexe ?: '—' }}
                                </td>

                                <td>
                                    {{ $eleve->telephone ?: '—' }}
                                </td>

                                <td>
                                    @if($eleve->actif)
                                        <span class="badge bg-success">
                                            Actif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            Inactif
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex justify-content-center gap-1 flex-wrap">

                                        <a href="{{ route('eleves-externes-td.show', $eleve->id) }}"
                                           class="btn btn-sm btn-outline-info"
                                           title="Consulter">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('eleves-externes-td.edit', $eleve->id) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form
                                            action="{{ route('eleves-externes-td.statut', $eleve->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Voulez-vous vraiment changer le statut de cet élève ?');"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-warning"
                                                    title="Activer ou désactiver">
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                        </form>

                                        <form
                                            action="{{ route('eleves-externes-td.destroy', $eleve->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Attention : cette suppression est définitive. Continuer ?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center py-5">

                                    <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>

                                    <h6 class="fw-bold">
                                        Aucun élève externe trouvé
                                    </h6>

                                    <p class="text-muted mb-3">
                                        Aucun élève ne correspond aux critères sélectionnés.
                                    </p>

                                    <a href="{{ route('eleves-externes-td.create') }}"
                                       class="btn btn-primary">
                                        <i class="fas fa-user-plus me-2"></i>
                                        Ajouter un élève
                                    </a>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>

        @if($eleves->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $eleves->links() }}
            </div>
        @endif

    </div>

</div>
@endsection