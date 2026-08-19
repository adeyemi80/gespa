@extends('tableau.neutre')

@section('title', $etablissement->nom)

@section('content')
<div class="container-fluid py-4" style="max-width: 1000px; margin: 0 auto;">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-md-8">
            <a href="{{ route('etablissements.index') }}" class="btn btn-secondary mb-3">
                <i class="bi bi-arrow-left me-2"></i>Retour à la liste
            </a>
            
            <h1 class="h3 fw-bold text-dark">
                <i class="bi bi-building me-2"></i>{{ $etablissement->nom }}
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group" role="group">
                <a href="{{ route('etablissements.edit', $etablissement) }}" class="btn btn-outline-warning">
                    <i class="bi bi-pencil me-2"></i>Modifier
                </a>
                <form action="{{ route('etablissements.destroy', $etablissement) }}" 
                      method="POST" 
                      class="d-inline"
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet établissement ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-trash me-2"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Informations Générales -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="bi bi-info-circle me-2"></i>Informations Générales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-3">
                                <strong class="text-muted d-block mb-1">Dénomination :</strong>
                                <span class="text-dark">{{ $etablissement->nom }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-3">
                                <strong class="text-muted d-block mb-1">Adresse :</strong>
                                <span class="text-dark">{{ $etablissement->adresse ?? '—' }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-3">
                                <strong class="text-muted d-block mb-1">Téléphone :</strong>
                                @if ($etablissement->telephone)
                                    <a href="tel:{{ $etablissement->telephone }}" class="text-decoration-none">
                                        {{ $etablissement->telephone }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-3">
                                <strong class="text-muted d-block mb-1">Email :</strong>
                                @if ($etablissement->email)
                                    <a href="mailto:{{ $etablissement->email }}" class="text-decoration-none">
                                        {{ $etablissement->email }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Identifiants Administratifs -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="bi bi-file-earmark-text me-2"></i>Identifiants Administratifs
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="mb-0">
                                <strong class="text-muted d-block mb-1">IFU (Identifiant Fiscal Unique) :</strong>
                                @if ($etablissement->ifu)
                                    <span class="text-dark fw-semibold">{{ $etablissement->ifu }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Non fourni
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-0">
                                <strong class="text-muted d-block mb-1">NPI (Numéro de Personne Juridique) :</strong>
                                @if ($etablissement->npi)
                                    <span class="text-dark fw-semibold">{{ $etablissement->npi }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Non fourni
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Représentant Légal -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="bi bi-person-check me-2"></i>Représentant Légal
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="mb-0">
                                <strong class="text-muted d-block mb-1">Nom et Prénom :</strong>
                                <span class="text-dark fw-semibold">{{ $etablissement->representant }}</span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-0">
                                <strong class="text-muted d-block mb-1">Fonction :</strong>
                                <span class="text-dark fw-semibold">{{ $etablissement->fonction }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barre latérale -->
        <div class="col-lg-4">
            <!-- Résumé -->
            <div class="card border-0 shadow-sm sticky-top mb-4" style="top: 20px;">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-file-earmark me-2"></i>Résumé
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-buildings me-1"></i>Type
                        </small>
                        <p class="mb-0">Établissement scolaire</p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-person-circle me-1"></i>Représentant
                        </small>
                        <div class="mb-1">
                            <p class="mb-0 fw-semibold">{{ $etablissement->representant }}</p>
                            <small class="text-muted">{{ $etablissement->fonction }}</small>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-calendar me-1"></i>Dates
                        </small>
                        <ul class="list-unstyled small">
                            <li class="mb-2">
                                <strong>Créé :</strong><br>
                                {{ $etablissement->created_at->format('d/m/Y à H:i') }}
                            </li>
                            <li>
                                <strong>Modifié :</strong><br>
                                {{ $etablissement->updated_at->format('d/m/Y à H:i') }}
                            </li>
                        </ul>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        <a href="{{ route('etablissements.edit', $etablissement) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil me-2"></i>Modifier
                        </a>
                        <a href="{{ route('etablissements.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statut -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 text-dark fw-bold">
                        <i class="bi bi-check-circle me-2"></i>Statut
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-success mb-0" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Établissement enregistré</strong>
                        <p class="mb-0 mt-2 small">
                            Cet établissement est actif et peut être utilisé pour créer des contrats de prestation.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection