@extends('tableau.neutre')

@section('title', 'Contrat #' . $contrat->id)

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary mb-3">
    ⬅️ Retour
</button>

<div class="container-fluid py-4" style="max-width: 1000px; margin: 0 auto;">
    <!-- En-tête du contrat -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 fw-bold text-dark">
                <i class="bi bi-file-earmark-check me-2"></i>Contrat de prestation de srevice de {{ $contrat->prestataire_nom }} du {{ $contrat->date_signature }}
            </h1>
            <small class="text-muted">{{ $contrat->etablissement }} - {{ $contrat->prestataire_nom }}</small>
        </div>
        <div class="col-md-4 text-end">
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
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Barre d'actions -->
    <div class="mb-4">
        <div class="btn-group mb-3" role="group">
            <a href="{{ route('contrats-prestataires.edit', $contrat->id) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil me-2"></i>Modifier
            </a>
            <a href="{{ route('contrats-prestataires.preview', $contrat->id) }}" class="btn btn-outline-info">
                <i class="bi bi-eye me-2"></i>Aperçu
            </a>
            <a href="{{ route('contrats-prestataires.pdf', $contrat->id) }}" class="btn btn-outline-success">
                <i class="bi bi-download me-2"></i>Télécharger PDF
            </a>
        </div>

        <!-- Actions d'état -->
        <div class="btn-group mb-3 ms-2" role="group">
            @if ($contrat->etat === 'brouillon')
                <form action="{{ route('contrats-prestataires.valider', $contrat) }}" method="POST" class="d-inline">
                   @csrf
                   @method('PATCH')
                    <button type="submit" class="btn btn-success" onclick="return confirm('Valider ce contrat ?')">
                        <i class="bi bi-check-circle me-2"></i>Valider
                    </button>
                </form>
            @endif

            @if (in_array($contrat->etat, ['brouillon', 'validé']))
                <form action="{{ route('contrats-prestataires.terminer', $contrat->id) }}"
      method="POST"
      class="d-inline">

    @csrf
    @method('PATCH')

    <button type="submit"
            class="btn btn-primary"
            onclick="return confirm('Marquer comme terminé ?')">
        <i class="bi bi-check2-all me-2"></i>
        Terminé
    </button>
</form>
            @endif

            @if (in_array($contrat->etat, ['brouillon', 'validé']))
                <form action="{{ route('contrats-prestataires.annuler', $contrat) }}" method="POST" class="d-inline">
                   @csrf
                   @method('PATCH')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Annuler ce contrat ?')">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </button>
                </form>

                <form action="{{ route('contrats-prestataires.destroy', $contrat) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce contrat ?')">
                        <i class="bi bi-trash me-2"></i>Supprimer
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Contenu principal - Style A4 -->
    <div class="card border-0 shadow-sm" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
        <div class="card-body p-5" style="background: white; line-height: 1.6;">
            
            <!-- Informations Établissement -->
            <div class="mb-5">
                <h5 class="fw-bold text-dark mb-3" style="font-size: 1.1rem; border-bottom: 2px solid #dee2e6; padding-bottom: 10px;">
                    <i class="bi bi-building me-2"></i>Établissement
                </h5>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-3">
                            <strong class="text-muted d-block mb-1">Dénomination :</strong>
                            <span class="text-dark">{{ $contrat->etablissement }}</span>
                        </p>
                        <p class="mb-3">
                            <strong class="text-muted d-block mb-1">Représentant :</strong>
                            <span class="text-dark">{{ $contrat->representant }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-3">
                            <strong class="text-muted d-block mb-1">Fonction :</strong>
                            <span class="text-dark">{{ $contrat->fonction }}</span>
                        </p>
                    </div>
                </div>
                <p class="mb-0">
                    <strong class="text-muted d-block mb-1">Adresse :</strong>
                    <span class="text-dark">{{ $contrat->adresse_etablissement }}</span>
                </p>
            </div>

            <!-- Informations Prestataire -->
            <div class="mb-5">
                <h5 class="fw-bold text-dark mb-3" style="font-size: 1.1rem; border-bottom: 2px solid #dee2e6; padding-bottom: 10px;">
                    <i class="bi bi-person-check me-2"></i>Prestataire
                </h5>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-3">
                            <strong class="text-muted d-block mb-1">Nom et prénom :</strong>
                            <span class="text-dark">{{ $contrat->prestataire_nom }}</span>
                        </p>
                        <p class="mb-3">
                            <strong class="text-muted d-block mb-1">Téléphone :</strong>
                            <span class="text-dark">{{ $contrat->telephone }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-3">
                            <strong class="text-muted d-block mb-1">NPI / IFU :</strong>
                            <span class="text-dark">{{ $contrat->ifu ?? 'Non fourni' }}</span>
                        </p>
                    </div>
                </div>
                <p class="mb-0">
                    <strong class="text-muted d-block mb-1">Adresse :</strong>
                    <span class="text-dark">{{ $contrat->prestataire_adresse }}</span>
                </p>
            </div>

            <!-- Objet du Contrat -->
            <div class="mb-5">
                <h5 class="fw-bold text-dark mb-3" style="font-size: 1.1rem; border-bottom: 2px solid #dee2e6; padding-bottom: 10px;">
                    <i class="bi bi-file-text me-2"></i>Objet du Contrat
                </h5>
                <p class="text-dark">{{ $contrat->objet_contrat }}</p>
            </div>

            <!-- Montants -->
            <div class="mb-5">
                <h5 class="fw-bold text-dark mb-3" style="font-size: 1.1rem; border-bottom: 2px solid #dee2e6; padding-bottom: 10px;">
                    <i class="bi bi-cash-coin me-2"></i>Montants du Marché
                </h5>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-light border-0 mb-3">
                            <div class="card-body p-3">
                                <small class="text-muted d-block">Montant Total</small>
                                <h5 class="text-dark fw-bold mb-1">{{ number_format($contrat->montant_total, 0, ',', ' ') }} FCFA</h5>
                                <small class="text-muted">{{ ucfirst($contrat->montant_total_lettre) }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info bg-opacity-10 border-0 mb-3">
                            <div class="card-body p-3">
                                <small class="text-muted d-block">Acompte Versé</small>
                                <h5 class="text-info fw-bold mb-1">{{ number_format($contrat->acompte, 0, ',', ' ') }} FCFA</h5>
                                <small class="text-muted">{{ ucfirst($contrat->acompte_lettre) }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success bg-opacity-10 border-0 mb-3">
                            <div class="card-body p-3">
                                <small class="text-muted d-block">Reliquat à Payer</small>
                                <h5 class="text-success fw-bold mb-1">{{ number_format($contrat->reliquat, 0, ',', ' ') }} FCFA</h5>
                                <small class="text-muted">{{ ucfirst($contrat->reliquat_lettre) }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dates -->
            <div class="mb-5">
                <h5 class="fw-bold text-dark mb-3" style="font-size: 1.1rem; border-bottom: 2px solid #dee2e6; padding-bottom: 10px;">
                    <i class="bi bi-calendar-event me-2"></i>Dates Importantes
                </h5>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-3">
                            <strong class="text-muted d-block mb-1">Date Limite de Livraison :</strong>
                            <span class="text-dark fw-semibold">{{ $contrat->date_limite_livraison->format('d/m/Y') }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-3">
                            <strong class="text-muted d-block mb-1">Lieu de Signature :</strong>
                            <span class="text-dark fw-semibold">{{ $contrat->lieu_signature }}</span>
                        </p>
                    </div>
                </div>
                <p class="mb-0">
                    <strong class="text-muted d-block mb-1">Date de Signature :</strong>
                    <span class="text-dark fw-semibold">{{ $contrat->date_signature->format('d/m/Y') }}</span>
                </p>
            </div>

            @if ($contrat->mention_manuelle)
                <div class="mb-5">
                    <h5 class="fw-bold text-dark mb-3" style="font-size: 1.1rem; border-bottom: 2px solid #dee2e6; padding-bottom: 10px;">
                        <i class="bi bi-chat-square-text me-2"></i>Mentions Additionnelles
                    </h5>
                    <p class="text-dark">{{ $contrat->mention_manuelle }}</p>
                </div>
            @endif

        </div>
    </div>

    <!-- Espace pour les métadonnées -->
    <div class="mt-4">
        <small class="text-muted">
            <i class="bi bi-info-circle me-1"></i>
            ID: #{{ $contrat->id }} | 
            Crée: {{ $contrat->created_at->format('d/m/Y à H:i') }} | 
            Modifié: {{ $contrat->updated_at->format('d/m/Y à H:i') }}
        </small>
    </div>
</div>

@endsection