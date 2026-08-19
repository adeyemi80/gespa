@extends('tableau.neutre')

@section('title', 'Modifier le Contrat #' . $contrat->id)

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 fw-bold text-dark">
                <i class="bi bi-pencil-square me-2"></i>Modifier le Contrat #{{ $contrat->id }}
            </h1>
            <small class="text-muted">Créé le {{ $contrat->created_at->format('d/m/Y à H:i') }}</small>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">
                <i class="bi bi-exclamation-triangle me-2"></i>Erreurs de validation
            </h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('contrats-prestataires.update', ['contrat' => $contrat->id]) }}" method="POST">
    @csrf
    @method('PUT')
        <div class="row">
            <!-- Colonne Gauche: Formulaire -->
            <div class="col-lg-8">
                <!-- Section Établissement -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="mb-0 text-dark fw-bold">
                            <i class="bi bi-building me-2"></i>Établissement
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="etablissement" class="form-label">Dénomination de l'établissement <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('etablissement') is-invalid @enderror" 
                                   id="etablissement" 
                                   name="etablissement" 
                                   value="{{ old('etablissement', $contrat->etablissement) }}"
                                   required>
                            @error('etablissement')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="adresse_etablissement" class="form-label">Adresse <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('adresse_etablissement') is-invalid @enderror" 
                                      id="adresse_etablissement" 
                                      name="adresse_etablissement" 
                                      rows="2"
                                      required>{{ old('adresse_etablissement', $contrat->adresse_etablissement) }}</textarea>
                            @error('adresse_etablissement')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="representant" class="form-label">Représentant (M./Mme) <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('representant') is-invalid @enderror" 
                                       id="representant" 
                                       name="representant" 
                                       value="{{ old('representant', $contrat->representant) }}"
                                       required>
                                @error('representant')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fonction" class="form-label">Fonction <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('fonction') is-invalid @enderror" 
                                       id="fonction" 
                                       name="fonction" 
                                       value="{{ old('fonction', $contrat->fonction) }}"
                                       required>
                                @error('fonction')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Prestataire -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="mb-0 text-dark fw-bold">
                            <i class="bi bi-person-check me-2"></i>Prestataire
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="prestataire_nom" class="form-label">Nom et prénom <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('prestataire_nom') is-invalid @enderror" 
                                   id="prestataire_nom" 
                                   name="prestataire_nom" 
                                   value="{{ old('prestataire_nom', $contrat->prestataire_nom) }}"
                                   required>
                            @error('prestataire_nom')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="prestataire_adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('prestataire_adresse') is-invalid @enderror" 
                                      id="prestataire_adresse" 
                                      name="prestataire_adresse" 
                                      rows="2"
                                      required>{{ old('prestataire_adresse', $contrat->prestataire_adresse) }}</textarea>
                            @error('prestataire_adresse')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="tel" 
                                       class="form-control @error('telephone') is-invalid @enderror" 
                                       id="telephone" 
                                       name="telephone" 
                                       value="{{ old('telephone', $contrat->telephone) }}"
                                       required>
                                @error('telephone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="ifu" class="form-label">NPI / IFU</label>
                                <input type="text" 
                                       class="form-control @error('ifu') is-invalid @enderror" 
                                       id="ifu" 
                                       name="ifu" 
                                       value="{{ old('ifu', $contrat->ifu) }}">
                                @error('ifu')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Objet du Contrat -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="mb-0 text-dark fw-bold">
                            <i class="bi bi-file-text me-2"></i>Objet du Contrat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="objet_contrat" class="form-label">Description de la prestation <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('objet_contrat') is-invalid @enderror" 
                                      id="objet_contrat" 
                                      name="objet_contrat" 
                                      rows="4"
                                      required>{{ old('objet_contrat', $contrat->objet_contrat) }}</textarea>
                            @error('objet_contrat')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section Montants -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="mb-0 text-dark fw-bold">
                            <i class="bi bi-cash-coin me-2"></i>Montants du Marché
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="montant_total" class="form-label">Montant Total (F CFA) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('montant_total') is-invalid @enderror" 
                                       id="montant_total" 
                                       name="montant_total" 
                                       value="{{ old('montant_total', $contrat->montant_total) }}"
                                       step="0.01"
                                       min="0"
                                       required>
                                @error('montant_total')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="montant_total_lettre" class="form-label">En toutes lettres <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('montant_total_lettre') is-invalid @enderror" 
                                       id="montant_total_lettre" 
                                       name="montant_total_lettre" 
                                       value="{{ old('montant_total_lettre', $contrat->montant_total_lettre) }}"
                                       required>
                                @error('montant_total_lettre')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="acompte" class="form-label">Acompte Versé (F CFA) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('acompte') is-invalid @enderror" 
                                       id="acompte" 
                                       name="acompte" 
                                       value="{{ old('acompte', $contrat->acompte) }}"
                                       step="0.01"
                                       min="0"
                                       required>
                                @error('acompte')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="acompte_lettre" class="form-label">En toutes lettres <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('acompte_lettre') is-invalid @enderror" 
                                       id="acompte_lettre" 
                                       name="acompte_lettre" 
                                       value="{{ old('acompte_lettre', $contrat->acompte_lettre) }}"
                                       required>
                                @error('acompte_lettre')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-info small">
                            <strong>Reliquat actuel :</strong> {{ number_format($contrat->reliquat, 2, ',', ' ') }} FCFA
                        </div>
                    </div>
                </div>

                <!-- Section Dates -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="mb-0 text-dark fw-bold">
                            <i class="bi bi-calendar-event me-2"></i>Dates Importantes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="date_limite_livraison" class="form-label">Date Limite de Livraison <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('date_limite_livraison') is-invalid @enderror" 
                                   id="date_limite_livraison" 
                                   name="date_limite_livraison" 
                                   value="{{ old('date_limite_livraison', $contrat->date_limite_livraison->format('Y-m-d')) }}"
                                   required>
                            @error('date_limite_livraison')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="lieu_signature" class="form-label">Lieu de Signature <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('lieu_signature') is-invalid @enderror" 
                                       id="lieu_signature" 
                                       name="lieu_signature" 
                                       value="{{ old('lieu_signature', $contrat->lieu_signature) }}"
                                       required>
                                @error('lieu_signature')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="date_signature" class="form-label">Date de Signature <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('date_signature') is-invalid @enderror" 
                                       id="date_signature" 
                                       name="date_signature" 
                                       value="{{ old('date_signature', $contrat->date_signature->format('Y-m-d')) }}"
                                       required>
                                @error('date_signature')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Mentions Additionnelles -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="mb-0 text-dark fw-bold">
                            <i class="bi bi-chat-square-text me-2"></i>Mentions Additionnelles
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="mention_manuelle" class="form-label">Mention Manuscrite (optionnel)</label>
                            <textarea class="form-control @error('mention_manuelle') is-invalid @enderror" 
                                      id="mention_manuelle" 
                                      name="mention_manuelle" 
                                      rows="3">{{ old('mention_manuelle', $contrat->mention_manuelle) }}</textarea>
                            @error('mention_manuelle')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Boutons d'Actions -->
                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                        <i class="bi bi-check-circle me-2"></i>Enregistrer les Modifications
                    </button>
                    <a href="{{ route('contrats-prestataires.show', $contrat) }}" class="btn btn-secondary btn-lg">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </a>
                </div>
            </div>

            <!-- Colonne Droite: État du Contrat -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-info-circle me-2"></i>État du Contrat
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong class="text-dark">État actuel :</strong>
                            <br>
                            @if ($contrat->etat === 'brouillon')
                                <span class="badge bg-warning text-dark mt-2">
                                    <i class="bi bi-pencil-square me-1"></i>Brouillon
                                </span>
                            @elseif ($contrat->etat === 'validé')
                                <span class="badge bg-info text-white mt-2">
                                    <i class="bi bi-check-circle me-1"></i>Validé
                                </span>
                            @elseif ($contrat->etat === 'terminé')
                                <span class="badge bg-success text-white mt-2">
                                    <i class="bi bi-check2-all me-1"></i>Terminé
                                </span>
                            @else
                                <span class="badge bg-danger text-white mt-2">
                                    <i class="bi bi-x-circle me-1"></i>Annulé
                                </span>
                            @endif
                        </div>

                        <hr>

                        <div class="mb-3">
                            <strong class="text-dark">Informations</strong>
                            <ul class="list-unstyled small text-muted mt-2">
                                <li><i class="bi bi-calendar me-1"></i> Créé le {{ $contrat->created_at->format('d/m/Y') }}</li>
                                <li><i class="bi bi-pencil me-1"></i> Modifié le {{ $contrat->updated_at->format('d/m/Y') }}</li>
                            </ul>
                        </div>

                        <hr>

                        <div class="alert alert-info small mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            Vous êtes en mode modification. Vos changements seront enregistrés une fois que vous cliquez sur "Enregistrer".
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection