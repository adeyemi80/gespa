@extends('tableau.neutre')


@section('title', 'Fiche de l’élève externe TD')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

        <div>
            <h3 class="fw-bold text-primary mb-1">
                <i class="fas fa-address-card me-2"></i>
                Fiche de l'élève externe
            </h3>

            <p class="text-muted mb-0">
                Informations détaillées de l'élève inscrit aux TD.
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">

            <a href="{{ route('eleves-externes-td.index') }}"
               class="btn btn-outline-secondary rounded-pill">
                <i class="fas fa-arrow-left me-2"></i>
                Retour
            </a>

            <a href="{{ route('eleves-externes-td.edit', $eleve->id) }}"
               class="btn btn-primary rounded-pill">
                <i class="fas fa-edit me-2"></i>
                Modifier
            </a>

        </div>

    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Carte d'identité --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">

        <div class="card-body p-4">

            <div class="row align-items-center g-4">

                <div class="col-md-auto text-center">

                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto"
                         style="width:110px;height:110px;">

                        <i class="fas fa-user-graduate fa-3x text-primary"></i>

                    </div>

                </div>

                <div class="col">

                    <h3 class="fw-bold mb-2">
                        {{ $eleve->nom }} {{ $eleve->prenom }}
                    </h3>

                    <div class="text-muted mb-2">
                        Matricule :
                        <span class="fw-semibold text-dark">
                            {{ $eleve->matricule }}
                        </span>
                    </div>

                    <div class="d-flex flex-wrap gap-2">

                        @if($eleve->actif)
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i>
                                Élève actif
                            </span>
                        @else
                            <span class="badge bg-secondary px-3 py-2">
                                <i class="fas fa-ban me-1"></i>
                                Élève inactif
                            </span>
                        @endif

                        @if($eleve->sexe)
                            <span class="badge bg-light text-dark border px-3 py-2">
                                {{ $eleve->sexe }}
                            </span>
                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Informations personnelles --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-light border-0 rounded-top-4 py-3">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-user me-2 text-primary"></i>
                Informations personnelles
            </h5>
        </div>

        <div class="card-body p-4">

            <div class="row g-4">

                <div class="col-md-6">
                    <div class="text-muted small mb-1">Nom</div>
                    <div class="fw-semibold">{{ $eleve->nom }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small mb-1">Prénom(s)</div>
                    <div class="fw-semibold">{{ $eleve->prenom }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small mb-1">Date de naissance</div>
                    <div class="fw-semibold">
                        {{ $eleve->date_naissance?->format('d/m/Y') ?? 'Non renseignée' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small mb-1">Sexe</div>
                    <div class="fw-semibold">{{ $eleve->sexe ?: 'Non renseigné' }}</div>
                </div>

            </div>

        </div>

    </div>

    {{-- Coordonnées --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-light border-0 rounded-top-4 py-3">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-phone-alt me-2 text-primary"></i>
                Coordonnées
            </h5>
        </div>

        <div class="card-body p-4">

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        Téléphone de l'élève
                    </div>

                    <div class="fw-semibold">
                        @if($eleve->telephone)
                            <a href="tel:{{ $eleve->telephone }}" class="text-decoration-none">
                                {{ $eleve->telephone }}
                            </a>
                        @else
                            Non renseigné
                        @endif
                    </div>

                </div>

                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        Téléphone du parent
                    </div>

                    <div class="fw-semibold">
                        @if($eleve->telephone_parent)
                            <a href="tel:{{ $eleve->telephone_parent }}" class="text-decoration-none">
                                {{ $eleve->telephone_parent }}
                            </a>
                        @else
                            Non renseigné
                        @endif
                    </div>

                </div>

                <div class="col-12">

                    <div class="text-muted small mb-1">
                        Adresse
                    </div>

                    <div class="fw-semibold">
                        {{ $eleve->adresse ?: 'Non renseignée' }}
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Observations --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-light border-0 rounded-top-4 py-3">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-comment-alt me-2 text-primary"></i>
                Observations
            </h5>
        </div>

        <div class="card-body p-4">

            @if($eleve->observations)
                <p class="mb-0" style="white-space: pre-line;">{{ $eleve->observations }}</p>
            @else
                <p class="text-muted mb-0">
                    Aucune observation enregistrée.
                </p>
            @endif

        </div>

    </div>

    {{-- Métadonnées --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-6">
                    <small class="text-muted">Enregistré le</small>
                    <div class="fw-semibold">
                        {{ $eleve->created_at?->format('d/m/Y à H:i') ?? '—' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Dernière modification</small>
                    <div class="fw-semibold">
                        {{ $eleve->updated_at?->format('d/m/Y à H:i') ?? '—' }}
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection