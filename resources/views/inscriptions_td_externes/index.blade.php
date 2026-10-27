@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container-fluid py-4">

    {{-- En-tête --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                <i class="fas fa-user-graduate text-primary me-2"></i>
                Inscriptions TD externes
            </h3>
            <p class="text-muted mb-0">
                Gestion des élèves externes inscrits aux travaux dirigés.
            </p>
        </div>

        <a href="{{ route('inscriptions-td-externes.create') }}"
           class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus-circle me-2"></i>
            Nouvelle inscription
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
        <div class="alert alert-danger shadow-sm">
            <strong>Une erreur est survenue :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tableau principal --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-list text-primary me-2"></i>
                    Liste des inscriptions
                </h5>

                <span class="badge bg-primary rounded-pill">
                    {{ $inscriptions->total() }} inscription(s)
                </span>
            </div>
        </div>

        <div class="card-body p-4 pt-0">

            {{-- Filtres --}}
            <form method="GET"
                  action="{{ route('inscriptions-td-externes.index') }}"
                  class="bg-light rounded-3 p-3 mb-4">

                <div class="row g-3 align-items-end">

                    <div class="col-md-4">
                        <label for="recherche" class="form-label fw-semibold">
                            Rechercher
                        </label>

                        <input type="text"
                               name="recherche"
                               id="recherche"
                               class="form-control"
                               value="{{ request('recherche') }}"
                               placeholder="Nom, prénom ou matricule">
                    </div>

                    <div class="col-md-3">
                        <label for="annee_id" class="form-label fw-semibold">
                            Année scolaire
                        </label>

                        <select name="annee_id" id="annee_id" class="form-select">
                            <option value="">Toutes les années</option>

                            @foreach(($annees ?? collect()) as $annee)
                                <option value="{{ $annee->id }}"
                                    @selected((string) request('annee_id') === (string) $annee->id)>
                                    {{ $annee->libelle ?? $annee->nom ?? $annee->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="classe_id" class="form-label fw-semibold">
                            Classe
                        </label>

                        <select name="classe_id" id="classe_id" class="form-select">
                            <option value="">Toutes les classes</option>

                            @foreach(($classes ?? collect()) as $classe)
                                <option value="{{ $classe->id }}"
                                    @selected((string) request('classe_id') === (string) $classe->id)>
                                    {{ $classe->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fas fa-search"></i>
                        </button>

                        <a href="{{ route('inscriptions-td-externes.index') }}"
                           class="btn btn-outline-secondary"
                           title="Réinitialiser les filtres">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>

                </div>
            </form>

            {{-- Tableau --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Élève externe</th>
                            <th>Matricule</th>
                            <th>Année</th>
                            <th>Classe</th>
                            <th>Frais TD</th>
                            <th>Montant total</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($inscriptions as $inscription)
                        <tr>
                            <td>
                                {{ $inscriptions->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $inscription->eleve->nom ?? '—' }}
                                    {{ $inscription->eleve->prenom ?? '' }}
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $inscription->eleve->matricule ?? '—' }}
                                </span>
                            </td>

                            <td>
                                {{ $inscription->annee->libelle
                                    ?? $inscription->annee->nom
                                    ?? $inscription->annee_id }}
                            </td>

                            <td>
                                {{ $inscription->classe->nom ?? '—' }}
                            </td>

                            <td>
                                {{ $inscription->frais->nom
                                    ?? $inscription->frais->libelle
                                    ?? 'Frais TD' }}
                            </td>

                            <td class="fw-semibold text-nowrap">
                                {{ number_format((float) $inscription->montant_total, 0, ',', ' ') }}
                                FCFA
                            </td>

                            <td>
                                @if($inscription->statut === 'active')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border">
                                        {{ ucfirst($inscription->statut ?? 'Inconnue') }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-1">

                                    <a href="{{ route('inscriptions-td-externes.show', $inscription->id) }}"
                                       class="btn btn-sm btn-outline-info"
                                       title="Consulter">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('inscriptions-td-externes.edit', $inscription->id) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('inscriptions-td-externes.destroy', $inscription->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Voulez-vous vraiment supprimer cette inscription TD externe ?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">
                                    Aucune inscription TD externe trouvée.
                                </p>

                                <a href="{{ route('inscriptions-td-externes.create') }}"
                                   class="btn btn-sm btn-primary mt-3">
                                    Ajouter une inscription
                                </a>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $inscriptions->withQueryString()->links() }}
            </div>

        </div>
    </div>
</div>
@endsection