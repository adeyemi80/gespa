
@extends('tableau.neutre')

@section('content')

<div class="container-fluid py-4">

    <!-- Bouton Retour -->
    <div class="mb-4">
        <button
            onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
            class="btn btn-secondary btn-lg shadow-sm">
            <i class="bi bi-arrow-left-circle"></i> Retour
        </button>
    </div>

    <!-- Titre -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

        <h2 class="fw-bold mb-0">
            <i class="bi bi-people-fill text-primary"></i>
            Liste des investisseurs
        </h2>

        <a href="{{ route('investisseurs.create') }}"
           class="btn btn-primary btn-lg shadow-sm">
            <i class="bi bi-person-plus-fill"></i>
            Nouvel investisseur
        </a>

    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}

            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ session('error') }}

            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    <!-- Recherche -->
    <div class="card shadow border-0 mb-4">

        <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-search"></i>
                Recherche
            </h5>
        </div>

        <div class="card-body">

            <form method="GET" action="{{ route('investisseurs.index') }}">

                <div class="row align-items-end">

                    <div class="col-md-8 mb-3">

                        <label class="form-label fw-bold fs-5">
                            Nom, prénom, téléphone ou email
                        </label>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Rechercher un investisseur..."
                               class="form-control form-control-lg">

                    </div>

                    <div class="col-md-4 mb-3">

                        <div class="d-flex gap-2">

                            <button class="btn btn-dark btn-lg flex-fill">
                                <i class="bi bi-search"></i>
                                Rechercher
                            </button>

                            @if(request()->filled('search'))
                                <a href="{{ route('investisseurs.index') }}"
                                   class="btn btn-outline-secondary btn-lg">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </a>
                            @endif

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <!-- Tableau -->
    <div class="card shadow border-0">

        <div class="card-header bg-primary text-white">

            <h4 class="mb-0 fw-bold">
                <i class="bi bi-table"></i>
                Liste des investisseurs
            </h4>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-bordered align-middle mb-0">

                    <thead class="table-light">

                    <tr class="text-center">

                        <th style="font-size:17px;">Nom et prénom</th>
                        <th style="font-size:17px;">Téléphone</th>
                        <th style="font-size:17px;">Email</th>
                        <th style="font-size:17px;">Profession</th>
                        <th style="font-size:17px;">Investissements</th>
                        <th style="width:230px;font-size:17px;">Actions</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($investisseurs as $investisseur)

                        <tr>

                            <td class="fw-semibold fs-5">
                                {{ $investisseur->nom }}
                                {{ $investisseur->prenom }}
                            </td>

                            <td class="fs-5">
                                {{ $investisseur->telephone ?: '—' }}
                            </td>

                            <td class="fs-5">
                                {{ $investisseur->email ?: '—' }}
                            </td>

                            <td class="fs-5">
                                {{ $investisseur->profession ?: '—' }}
                            </td>

                            <td class="text-center">

                                <span class="badge bg-primary fs-6 px-3 py-2">
                                    {{ $investisseur->investissements_count }}
                                </span>

                            </td>

                            <td class="text-center">

                                <a href="{{ route('investisseurs.show',$investisseur) }}"
                                   class="btn btn-info btn-sm text-white">
                                    <i class="bi bi-eye-fill"></i>
                                </a>

                                <a href="{{ route('investisseurs.edit',$investisseur) }}"
                                   class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>

                                <form action="{{ route('investisseurs.destroy',$investisseur) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Confirmer la suppression de cet investisseur ?');">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center py-5 fs-4 text-muted">
                                <i class="bi bi-inbox"></i><br>
                                Aucun investisseur trouvé.
                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $investisseurs->links() }}
    </div>

</div>

@endsection