@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container-fluid py-4">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-info text-white">

                    <div class="d-flex justify-content-between align-items-center">

                        <h4 class="mb-0">
                            <i class="fas fa-eye me-2"></i>
                            Détail du versement
                        </h4>

                        <div>

                            <a href="{{ route('versements.edit', $versement) }}"
                               class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                                Modifier
                            </a>

                            <a href="{{ route('versements.index') }}"
                               class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left"></i>
                                Retour
                            </a>

                        </div>

                    </div>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <label class="form-label text-muted fw-bold">
                                <i class="fas fa-user me-1"></i>
                                Investisseur
                            </label>

                            <div class="form-control bg-light">

                                {{ $versement->investissement->investisseur->nom ?? '—' }}
                                {{ $versement->investissement->investisseur->prenom ?? '' }}

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label text-muted fw-bold">
                                <i class="fas fa-chart-line me-1"></i>
                                Investissement
                            </label>

                            <div class="form-control bg-light">

                                <span class="badge bg-primary">
                                    #{{ $versement->investissement_id }}
                                </span>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label text-muted fw-bold">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Date du versement
                            </label>

                            <div class="form-control bg-light">
                                {{ \Carbon\Carbon::parse($versement->date_versement)->format('d/m/Y') }}
                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label text-muted fw-bold">
                                <i class="fas fa-coins me-1"></i>
                                Montant
                            </label>

                            <div class="form-control bg-light fw-bold text-success fs-5">
                                {{ number_format($versement->montant,0,',',' ') }} F CFA
                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label text-muted fw-bold">
                                <i class="fas fa-credit-card me-1"></i>
                                Mode de paiement
                            </label>

                            <div class="form-control bg-light">

                                @if($versement->mode_paiement)

                                    <span class="badge bg-secondary">
                                        {{ $versement->mode_paiement }}
                                    </span>

                                @else

                                    <span class="text-muted">Non renseigné</span>

                                @endif

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label text-muted fw-bold">
                                <i class="fas fa-receipt me-1"></i>
                                Référence
                            </label>

                            <div class="form-control bg-light">
                                {{ $versement->reference ?: 'Non renseignée' }}
                            </div>

                        </div>

                        <div class="col-12">

                            <label class="form-label text-muted fw-bold">
                                <i class="fas fa-comment-alt me-1"></i>
                                Observation
                            </label>

                            <div class="form-control bg-light" style="min-height:120px;">

                                {!! nl2br(e($versement->observation ?: 'Aucune observation')) !!}

                            </div>

                        </div>

                    </div>

                </div>

                <div class="card-footer bg-light">

                    <div class="row">

                        <div class="col-md-6 text-muted small">

                            <i class="fas fa-plus-circle text-success"></i>
                            <strong>Créé le :</strong>
                            {{ $versement->created_at->format('d/m/Y à H:i') }}

                        </div>

                        <div class="col-md-6 text-md-end text-muted small">

                            @if($versement->updated_at && $versement->updated_at->ne($versement->created_at))

                                <i class="fas fa-edit text-warning"></i>
                                <strong>Dernière modification :</strong>
                                {{ $versement->updated_at->format('d/m/Y à H:i') }}

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection