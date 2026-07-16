<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestion des dépenses</h1>
        <button type="button" class="btn btn-primary" wire:click="ouvrirModalCreation">
            <i class="bi bi-plus-lg"></i> Nouvelle dépense
        </button>
    </div>

    {{-- Alertes auto-dismiss --}}
    @if ($messageSucces)
        <div wire:key="alerte-succes-{{ now()->timestamp }}" x-data="{ visible: true }" x-show="visible"
             x-init="setTimeout(() => visible = false, 4000)"
             class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $messageSucces }}
            <button type="button" class="btn-close" @click="visible = false"></button>
        </div>
    @endif

    @if ($messageErreur)
        <div wire:key="alerte-erreur-{{ now()->timestamp }}" x-data="{ visible: true }" x-show="visible"
             x-init="setTimeout(() => visible = false, 5000)"
             class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $messageErreur }}
            <button type="button" class="btn-close" @click="visible = false"></button>
        </div>
    @endif

    {{-- Filtres --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" wire:model.live.debounce.400ms="recherche" class="form-control"
                           placeholder="Rechercher (libellé, N° pièce, bénéficiaire)...">
                </div>
                <div class="col-md-2">
                    <select wire:model.live="categorieFiltre" class="form-select">
                        <option value="">Toutes catégories</option>
                        @foreach ($categories as $categorie)
                            <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="statutFiltre" class="form-select">
                        <option value="">Tous statuts</option>
                        <option value="brouillon">Brouillon</option>
                        <option value="validee">Validée</option>
                        <option value="payee">Payée</option>
                        <option value="rejetee">Rejetée</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="anneeFiltre" class="form-select">
                        <option value="">Toutes années</option>
                        @foreach ($annees as $annee)
                            <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 text-end">
                    @if ($recherche || $categorieFiltre || $statutFiltre)
                        <button type="button" class="btn btn-outline-secondary"
                                wire:click="$set('recherche', ''); $set('categorieFiltre', ''); $set('statutFiltre', '')">
                            Réinitialiser les filtres
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>N° pièce</th>
                        <th>Libellé</th>
                        <th>Catégorie / Type</th>
                        <th class="text-end">Montant</th>
                        <th>Date</th>
                        <th class="text-center">Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($depenses as $depense)
                        <tr wire:key="depense-{{ $depense->id }}">
                            <td>{{ $depense->numero_piece }}</td>
                            <td>{{ $depense->libelle }}</td>
                            <td class="small text-muted">
                                {{ $depense->typeDepense->categorie->nom }} / {{ $depense->typeDepense->nom }}
                            </td>
                            <td class="text-end">{{ number_format($depense->montant, 0, ',', ' ') }} FCFA</td>
                            <td>{{ $depense->date_depense->format('d/m/Y') }}</td>
                            <td class="text-center">
                                @switch($depense->statut)
                                    @case('brouillon')
                                        <span class="badge bg-secondary">Brouillon</span>
                                        @break
                                    @case('validee')
                                        <span class="badge bg-info text-dark">Validée</span>
                                        @break
                                    @case('payee')
                                        <span class="badge bg-success">Payée</span>
                                        @break
                                    @case('rejetee')
                                        <span class="badge bg-danger">Rejetée</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" title="Modifier"
                                            wire:click="ouvrirModalEdition({{ $depense->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    @if ($depense->statut === 'brouillon')
                                        <button type="button" class="btn btn-sm btn-outline-success" title="Valider"
                                                wire:click="valider({{ $depense->id }})"
                                                wire:confirm="Valider cette dépense ?">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    @endif

                                    @if ($depense->statut === 'validee')
                                        <button type="button" class="btn btn-sm btn-outline-success" title="Marquer payée"
                                                wire:click="marquerPayee({{ $depense->id }})"
                                                wire:confirm="Marquer cette dépense comme payée ?">
                                            <i class="bi bi-cash-coin"></i>
                                        </button>
                                    @endif

                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer"
                                            wire:click="confirmerSuppression({{ $depense->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Aucune dépense trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $depenses->links() }}
    </div>

    {{-- Modale création / édition --}}
    @if ($showModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);" wire:key="modal-formulaire">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form wire:submit="enregistrer">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                {{ $modeEdition ? 'Modifier la dépense' : 'Nouvelle dépense' }}
                            </h5>
                            <button type="button" class="btn-close" wire:click="fermerModal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">N° de pièce</label>
                                    <input type="text" class="form-control" value="{{ $numero_piece }}" disabled readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Année scolaire <span class="text-danger">*</span></label>
                                    <select wire:model="annee_id" class="form-select @error('annee_id') is-invalid @enderror">
                                        <option value="">-- Sélectionner --</option>
                                        @foreach ($annees as $annee)
                                            <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                                        @endforeach
                                    </select>
                                    @error('annee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                                    <select wire:model.live="categorie_id" class="form-select @error('categorie_id') is-invalid @enderror">
                                        <option value="">-- Sélectionner --</option>
                                        @foreach ($categories as $categorie)
                                            <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                                        @endforeach
                                    </select>
                                    @error('categorie_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Type de dépense <span class="text-danger">*</span></label>
                                    <select wire:model="type_depense_id" class="form-select @error('type_depense_id') is-invalid @enderror"
                                            {{ $categorie_id ? '' : 'disabled' }}>
                                        <option value="">-- Sélectionner --</option>
                                        @foreach ($this->typesDisponibles as $type)
                                            <option value="{{ $type->id }}">{{ $type->nom }}</option>
                                        @endforeach
                                    </select>
                                    @error('type_depense_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Libellé <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="libelle" class="form-control @error('libelle') is-invalid @enderror">
                                    @error('libelle') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea wire:model="description" rows="2" class="form-control @error('description') is-invalid @enderror"></textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Montant (FCFA) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" wire:model="montant" class="form-control @error('montant') is-invalid @enderror">
                                    @error('montant') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="date" wire:model="date_depense" class="form-control @error('date_depense') is-invalid @enderror">
                                    @error('date_depense') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Mode de paiement <span class="text-danger">*</span></label>
                                    <select wire:model="mode_paiement" class="form-select @error('mode_paiement') is-invalid @enderror">
                                        <option value="especes">Espèces</option>
                                        <option value="cheque">Chèque</option>
                                        <option value="virement">Virement</option>
                                        <option value="mobile_money">Mobile Money</option>
                                    </select>
                                    @error('mode_paiement') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Bénéficiaire</label>
                                    <input type="text" wire:model="beneficiaire" class="form-control @error('beneficiaire') is-invalid @enderror">
                                    @error('beneficiaire') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Référence de paiement</label>
                                    <input type="text" wire:model="reference_paiement" class="form-control @error('reference_paiement') is-invalid @enderror"
                                           placeholder="N° chèque, ID transaction...">
                                    @error('reference_paiement') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Pièces justificatives</label>
                                    <input type="file" wire:model="nouvellesPieces" multiple class="form-control @error('nouvellesPieces.*') is-invalid @enderror">
                                    <div class="form-text">PDF, JPG ou PNG, 5 Mo max par fichier.</div>
                                    @error('nouvellesPieces.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

                                    <div wire:loading wire:target="nouvellesPieces" class="small text-muted mt-1">
                                        Téléversement en cours...
                                    </div>

                                    @if (count($piecesExistantes))
                                        <ul class="list-group mt-2">
                                            @foreach ($piecesExistantes as $piece)
                                                <li class="list-group-item d-flex justify-content-between align-items-center"
                                                    wire:key="piece-{{ $piece->id }}">
                                                    <span><i class="bi bi-paperclip"></i> {{ $piece->nom_fichier }}</span>
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                            wire:click="supprimerPieceJustificative({{ $piece->id }})"
                                                            wire:confirm="Supprimer cette pièce justificative ?">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" wire:click="fermerModal">Annuler</button>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="enregistrer">
                                <span wire:loading wire:target="enregistrer" class="spinner-border spinner-border-sm me-1"></span>
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Modale confirmation suppression --}}
    @if ($showSuppressionModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);" wire:key="modal-suppression">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmer la suppression</h5>
                        <button type="button" class="btn-close" wire:click="annulerSuppression"></button>
                    </div>
                    <div class="modal-body">
                        Es-tu sûr de vouloir supprimer cette dépense ? Cette action est irréversible et supprimera
                        également les pièces justificatives associées.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="annulerSuppression">Annuler</button>
                        <button type="button" class="btn btn-danger" wire:click="supprimer">
                            <i class="bi bi-trash"></i> Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>