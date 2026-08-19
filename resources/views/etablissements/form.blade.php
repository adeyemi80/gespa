<!-- Formulaire Établissement (réutilisable) -->

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                
                <!-- Informations Générales -->
                <div class="mb-4">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="bi bi-info-circle me-2"></i>Informations Générales
                    </h5>
                    
                    <div class="mb-3">
                        <label for="nom" class="form-label fw-semibold">
                            Dénomination de l'établissement <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('nom') is-invalid @enderror" 
                               id="nom" 
                               name="nom" 
                               value="{{ old('nom', $etablissement->nom ?? '') }}" 
                               placeholder="Ex: Complexe Scolaire Le Glorieux"
                               required>
                        @error('nom')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label fw-semibold">Téléphone</label>
                            <input type="text" 
                                   class="form-control @error('telephone') is-invalid @enderror" 
                                   id="telephone" 
                                   name="telephone" 
                                   value="{{ old('telephone', $etablissement->telephone ?? '') }}"
                                   placeholder="+229 97 XX XX XX">
                            @error('telephone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $etablissement->email ?? '') }}"
                                   placeholder="contact@etablissement.bj">
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="adresse" class="form-label fw-semibold">Adresse</label>
                        <textarea class="form-control @error('adresse') is-invalid @enderror" 
                                  id="adresse" 
                                  name="adresse" 
                                  rows="3"
                                  placeholder="Adresse complète de l'établissement">{{ old('adresse', $etablissement->adresse ?? '') }}</textarea>
                        @error('adresse')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Identifiants -->
                <div class="mb-4">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="bi bi-file-earmark-text me-2"></i>Identifiants Administratifs
                    </h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ifu" class="form-label fw-semibold">IFU</label>
                            <input type="text" 
                                   class="form-control @error('ifu') is-invalid @enderror" 
                                   id="ifu" 
                                   name="ifu" 
                                   value="{{ old('ifu', $etablissement->ifu ?? '') }}"
                                   placeholder="Identifiant Fiscal Unique">
                            @error('ifu')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">
                                <i class="bi bi-info-circle me-1"></i>Numéro d'identification fiscale
                            </small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="npi" class="form-label fw-semibold">NPI</label>
                            <input type="text" 
                                   class="form-control @error('npi') is-invalid @enderror" 
                                   id="npi" 
                                   name="npi" 
                                   value="{{ old('npi', $etablissement->npi ?? '') }}"
                                   placeholder="Numéro de Personne Juridique">
                            @error('npi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">
                                <i class="bi bi-info-circle me-1"></i>Numéro d'identification juridique
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Représentant -->
                <div class="mb-4">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="bi bi-person-check me-2"></i>Représentant Légal
                    </h5>

                    <div class="mb-3">
                        <label for="representant" class="form-label fw-semibold">
                            Nom et Prénom <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('representant') is-invalid @enderror" 
                               id="representant" 
                               name="representant" 
                               value="{{ old('representant', $etablissement->representant ?? '') }}"
                               placeholder="Ex: Jean Dupont"
                               required>
                        @error('representant')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="fonction" class="form-label fw-semibold">
                            Fonction <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('fonction') is-invalid @enderror" 
                               id="fonction" 
                               name="fonction" 
                               value="{{ old('fonction', $etablissement->fonction ?? '') }}"
                               placeholder="Ex: Directeur, Directrice Générale, Proviseur"
                               required>
                        @error('fonction')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
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
                        <i class="bi bi-exclamation-circle me-1"></i>Champs obligatoires
                    </small>
                    <ul class="list-unstyled small">
                        <li class="mb-1">✓ Dénomination de l'établissement</li>
                        <li class="mb-1">✓ Nom du représentant</li>
                        <li class="mb-1">✓ Fonction du représentant</li>
                    </ul>
                </div>

                <hr>

                <div class="mb-3">
                    <small class="text-muted d-block mb-2">Conseils</small>
                    <ul class="list-unstyled small text-muted">
                        <li class="mb-2">
                            <i class="bi bi-lightbulb me-1"></i>Utiliser le nom officiel de l'établissement
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-lightbulb me-1"></i>Fournir les identifiants administratifs si disponibles
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-lightbulb me-1"></i>Vérifier les coordonnées de contact
                        </li>
                    </ul>
                </div>

                <hr>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle me-2"></i>{{ isset($etablissement) ? 'Mettre à jour' : 'Créer l\'établissement' }}
                    </button>
                    <a href="{{ route('etablissements.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Annuler
                    </a>
                </div>
            </div>
        </div>

        <!-- Informations supplémentaires -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 text-dark fw-bold">
                    <i class="bi bi-info-circle me-2"></i>Informations
                </h6>
            </div>
            <div class="card-body small text-muted">
                <p class="mb-2">
                    <strong>Pourquoi ces informations ?</strong>
                </p>
                <p class="mb-3">
                    Les données de l'établissement sont utilisées pour générer les contrats de prestation et autres documents officiels.
                </p>
                <p class="mb-2">
                    <strong>Identificateurs :</strong>
                </p>
                <ul class="list-unstyled">
                    <li>• <strong>IFU :</strong> Identifiant Fiscal Unique</li>
                    <li>• <strong>NPI :</strong> Numéro de Personne Juridique</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Styles personnalisés -->
<style>
    .form-label {
        color: #333;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .invalid-feedback {
        font-size: 0.875rem;
        color: #dc3545;
    }

    .text-danger {
        color: #dc3545;
    }
</style>