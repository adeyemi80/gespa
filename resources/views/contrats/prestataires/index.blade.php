@extends('tableau.neutre')

@section('title', 'Contrats Prestataires')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 fw-bold text-dark">
                <i class="bi bi-file-earmark-contract me-2"></i>Contrats Prestataires
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <!-- ✅ CORRECT -->
<span>Aucun contrat trouvé. <a href="{{ route('contrats-prestataires.create', ) }}">Créer un nouveau contrat</a></span>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($contrats->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Établissement</th>
                            <th>Prestataire</th>
                            <th>Montant Total</th>
                            <th>État</th>
                            <th>Date Création</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contrats as $contrat)
                            <tr>
                                <td class="ps-4 fw-bold">#{{ $contrat->id }}</td>
                                <td>
                                    <span class="text-dark fw-500">{{ $contrat->etablissement }}</span>
                                </td>
                                <td>{{ $contrat->prestataire_nom }}</td>
                                <td>
                                    <strong>{{ number_format($contrat->montant_total, 0, ',', ' ') }} FCFA</strong>
                                </td>
                                <td>
                                    @if ($contrat->etat === 'brouillon')
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-pencil-square me-1"></i>Brouillon
                                        </span>
                                    @elseif ($contrat->etat === 'validé')
                                        <span class="badge bg-info">
                                            <i class="bi bi-check-circle me-1"></i>Validé
                                        </span>
                                    @elseif ($contrat->etat === 'terminé')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check2-all me-1"></i>Terminé
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle me-1"></i>Annulé
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $contrat->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('contrats-prestataires.show', $contrat) }}" 
                                           class="btn btn-outline-primary" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('contrats-prestataires.edit', $contrat) }}" 
                                           class="btn btn-outline-secondary" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="{{ route('contrats-prestataires.preview', $contrat) }}" 
                                           class="btn btn-outline-info" title="Aperçu">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="{{ route('contrats-prestataires.pdf', $contrat) }}" 
                                           class="btn btn-outline-success" title="Télécharger PDF">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $contrats->links() }}
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="bi bi-info-circle me-2"></i>
            <span>Aucun contrat trouvé. <a href="{{ route('contrats.prestataires.create') }}">Créer un nouveau contrat</a></span>
        </div>
    @endif
</div>
@endsection