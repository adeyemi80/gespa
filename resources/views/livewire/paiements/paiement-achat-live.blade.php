<div>
    {{-- Messages --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-2"><i class="bi bi-exclamation-triangle me-2"></i>Veuillez corriger les erreurs suivantes :</div>
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    @if ($successMessage)
        <div class="alert alert-success alert-dismissible fade show">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                <div>
                    <strong>Paiement enregistré avec succès !</strong>
                    @if ($numeroRecuGenere)
                        <div>N° reçu : <strong>{{ $numeroRecuGenere }}</strong></div>
                    @endif
                </div>
            </div>
            <button type="button" class="btn-close" wire:click="$set('successMessage', '')"></button>
        </div>
    @endif

    {{-- 1. Filtres --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-check me-2"></i>Sélection de l'élève</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Année scolaire</label>
                    <select class="form-select" wire:model.live="anneeId">
                        <option value="">-- Sélectionner l'année --</option>
                        @foreach ($annees as $annee)
                            <option value="{{ $annee['id'] }}">{{ $annee['nom'] }} @if (!empty($annee['en_cours'])) (En cours) @endif</option>
                        @endforeach
                    </select>
                    @error('anneeId')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Cycle</label>
                    <select class="form-select" wire:model.live="cycleId" @disabled(!$anneeId)>
                        <option value="">-- Sélectionner le cycle --</option>
                        @foreach ($cycles as $cycle)
                            <option value="{{ $cycle['id'] }}">{{ $cycle['nom'] }}</option>
                        @endforeach
                    </select>
                    @error('cycleId')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Classe</label>
                    <select class="form-select" wire:model.live="classeId" @disabled(!$cycleId)>
                        <option value="">-- Sélectionner la classe --</option>
                        @foreach ($classes as $classe)
                            <option value="{{ $classe['id'] }}">{{ $classe['nom'] }}</option>
                        @endforeach
                    </select>
                    @error('classeId')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Type d'élève</label>
                    <select class="form-select" wire:model.live="typeEleve" @disabled(!$classeId)>
                        <option value="">-- Choisir --</option>
                        <option value="ancien">Ancien élève</option>
                        <option value="nouveau">Nouveau élève</option>
                        <option value="externe_td">Élève externe TD</option>
                    </select>
                    @error('typeEleve')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Ancien élève --}}
    @if ($typeEleve === 'ancien')
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white"><h5 class="mb-0"><i class="bi bi-person me-2"></i>Sélectionner l'ancien élève</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Élève</label>
                        <select class="form-select" wire:model.live="inscriptionId">
                            <option value="">-- Sélectionner l'élève --</option>
                            @forelse ($inscriptions as $inscription)
                                <option value="{{ $inscription['id'] }}">{{ $inscription['nom'] }} @if (!empty($inscription['matricule'])) — Matricule : {{ $inscription['matricule'] }} @endif</option>
                            @empty
                                <option value="" disabled>Aucun élève inscrit dans cette classe</option>
                            @endforelse
                        </select>
                        @error('inscriptionId')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Classe</label>
                        <input class="form-control bg-light" value="{{ collect($classes)->firstWhere('id', $classeId)['nom'] ?? '—' }}" readonly>
                    </div>
                </div>
                @if ($inscriptionId)
                    <div class="row g-3 mt-2">
                        <div class="col-md-4"><label class="form-label">Nom</label><input class="form-control" value="{{ $nom }}" readonly></div>
                        <div class="col-md-4"><label class="form-label">Prénom</label><input class="form-control" value="{{ $prenom }}" readonly></div>
                        <div class="col-md-4"><label class="form-label">Téléphone</label><input class="form-control" value="{{ $telephone }}" readonly></div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- 3. Élève externe TD --}}
    @if ($typeEleve === 'externe_td')
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white"><h5 class="mb-0"><i class="bi bi-person-vcard me-2"></i>Inscription d'un élève externe aux TD</h5></div>
            <div class="card-body">
                <div class="alert alert-info">
                    Sélectionnez une inscription TD externe active. Seuls les frais de catégorie TD (catégorie 17) seront proposés et le paiement sera enregistré dans <code>paiement_achats</code> et <code>recettes</code>.
                </div>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Élève externe inscrit aux TD <span class="text-danger">*</span></label>
                        <select class="form-select @error('inscriptionTdExterneId') is-invalid @enderror" wire:model.live="inscriptionTdExterneId">
                            <option value="">-- Sélectionner un élève externe --</option>
                            @forelse ($inscriptionsTdExternes as $inscriptionTd)
                                <option value="{{ $inscriptionTd['id'] }}">
                                    {{ $inscriptionTd['matricule'] }} — {{ $inscriptionTd['nom'] }} {{ $inscriptionTd['prenom'] }}
                                </option>
                            @empty
                                <option value="" disabled>Aucune inscription TD externe active dans cette classe pour cette année</option>
                            @endforelse
                        </select>
                        @error('inscriptionTdExterneId')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Classe</label>
                        <input class="form-control bg-light" value="{{ collect($classes)->firstWhere('id', $classeId)['nom'] ?? '—' }}" readonly>
                    </div>
                </div>
                @if ($inscriptionTdExterneId)
                    <div class="row g-3 mt-2">
                        <div class="col-md-4"><label class="form-label">Nom</label><input class="form-control" value="{{ $nom }}" readonly></div>
                        <div class="col-md-4"><label class="form-label">Prénom</label><input class="form-control" value="{{ $prenom }}" readonly></div>
                        <div class="col-md-4"><label class="form-label">Téléphone</label><input class="form-control" value="{{ $telephone }}" readonly></div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- 4. Nouveau élève --}}
    @if ($typeEleve === 'nouveau')
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white"><h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>Nouveau élève</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Nom <span class="text-danger">*</span></label>
                        <input class="form-control" wire:model.defer="nouveauEleve.nom" placeholder="Nom de l'élève">
                        @error('nouveauEleve.nom')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Prénom <span class="text-danger">*</span></label>
                        <input class="form-control" wire:model.defer="nouveauEleve.prenom" placeholder="Prénom de l'élève">
                        @error('nouveauEleve.prenom')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Sexe</label>
                        <select class="form-select" wire:model.defer="nouveauEleve.sexe">
                            <option value="">-- Sélectionner --</option><option value="M">Masculin</option><option value="F">Féminin</option>
                        </select>
                        @error('nouveauEleve.sexe')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Date de naissance</label>
                        <input type="date" class="form-control" wire:model.defer="nouveauEleve.date_naissance">
                        @error('nouveauEleve.date_naissance')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Lieu de naissance</label>
                        <input class="form-control" wire:model.defer="nouveauEleve.lieu_naissance" placeholder="Lieu de naissance">
                        @error('nouveauEleve.lieu_naissance')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Téléphone</label>
                        <input class="form-control" wire:model.defer="nouveauEleve.telephone" placeholder="Téléphone">
                        @error('nouveauEleve.telephone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Adresse</label>
                        <input class="form-control" wire:model.defer="nouveauEleve.adresse" placeholder="Adresse">
                        @error('nouveauEleve.adresse')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Classe</label>
                        <input class="form-control bg-light" value="{{ collect($classes)->firstWhere('id', $classeId)['nom'] ?? '' }}" readonly>
                        <input type="hidden" wire:model="classeId">
                    </div>
                </div>
                <div class="mt-4">
                    <button type="button" class="btn btn-success" wire:click="enregistrerNouvelEleve" wire:loading.attr="disabled" wire:target="enregistrerNouvelEleve">
                        <span wire:loading.remove wire:target="enregistrerNouvelEleve"><i class="bi bi-person-plus me-1"></i>Enregistrer l'élève</span>
                        <span wire:loading wire:target="enregistrerNouvelEleve"><span class="spinner-border spinner-border-sm me-1"></span>Enregistrement...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- 5. Anciens reçus : uniquement pour les élèves ordinaires --}}
    @if (in_array($typeEleve, ['ancien', 'nouveau'], true))
        <div class="card border-0 shadow-sm rounded-4 mt-4 mb-4 overflow-hidden">
            <div class="card-header bg-white border-0 p-3" role="button" data-bs-toggle="collapse" data-bs-target="#anciensRecusAchatCollapse" aria-expanded="false" aria-controls="anciensRecusAchatCollapse" style="cursor:pointer;">
                <div class="d-flex justify-content-between align-items-center gap-3">
                    <div>
                        <h5 class="mb-1"><i class="bi bi-receipt me-2 text-primary"></i>Anciens reçus de paiement</h5>
                        <p class="text-muted small mb-0">Consultez et réimprimez les reçus de cet élève, toutes années confondues.</p>
                    </div>
                    <i class="bi bi-chevron-down text-primary fs-5" id="iconeAnciensRecusAchat"></i>
                </div>
            </div>
            <div class="collapse" id="anciensRecusAchatCollapse">
                <div class="card-body">
                    @error('anciensRecus')<div class="alert alert-danger">{{ $message }}</div>@enderror
                    @if (!$inscriptionId)
                        <div class="alert alert-info mb-0">Sélectionnez un ancien élève pour consulter ses reçus.</div>
                    @elseif (empty($anciensRecus))
                        <div class="alert alert-secondary mb-0">Aucun reçu de paiement trouvé pour cet élève.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle">
                                <thead class="table-light"><tr><th>N° reçu</th><th>Date</th><th>Année scolaire</th><th>Classe</th><th>Frais</th><th>Mode de paiement</th><th class="text-end">Montant</th><th class="text-center">Action</th></tr></thead>
                                <tbody>
                                    @foreach ($anciensRecus as $recu)
                                        <tr wire:key="ancien-recu-achat-{{ md5($recu['numero_recu']) }}">
                                            <td class="fw-bold">{{ $recu['numero_recu'] }}</td><td>{{ $recu['date'] }}</td><td>{{ $recu['annee'] }}</td><td>{{ $recu['classe'] }}</td><td>{{ $recu['frais'] }}</td><td>{{ $recu['mode_paiement'] }}</td>
                                            <td class="text-end fw-bold">{{ number_format($recu['total'], 0, ',', ' ') }} FCFA</td>
                                            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-primary" wire:click="reimprimerAncienRecu(@js($recu['numero_recu']))" wire:loading.attr="disabled" wire:target="reimprimerAncienRecu"><i class="bi bi-printer me-1"></i>Réimprimer</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light"><tr><th colspan="6">Nombre de reçus : {{ count($anciensRecus) }}</th><th colspan="2" class="text-end">Total affiché : {{ number_format(collect($anciensRecus)->sum('total'), 0, ',', ' ') }} FCFA</th></tr></tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- 6. Frais disponibles --}}
    @if ($typeEleve && $classeId && (($typeEleve === 'externe_td' && $inscriptionTdExterneId) || ($typeEleve !== 'externe_td' && $inscriptionId)))
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-warning d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-cash-coin me-2"></i>{{ $typeEleve === 'externe_td' ? 'Frais de TD à payer' : 'Frais à payer' }}</h5>
                @if (!empty($achats))<span class="badge bg-dark">{{ count($achats) }} frais sélectionné(s)</span>@endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th class="text-center">#</th><th>Frais</th><th>Catégorie</th><th class="text-end">Montant</th><th class="text-end">Déjà payé</th><th class="text-end">Reste</th><th class="text-center">Action</th></tr></thead>
                        <tbody>
                            @forelse ($fraisDisponibles as $index => $frais)
                                <tr wire:key="frais-disponible-{{ $frais['frais_id'] }}" class="{{ !empty($frais['selectionne']) ? 'table-primary' : '' }}">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td><div class="fw-bold">{{ $frais['nom'] ?? '—' }}</div>@if (!empty($frais['description']))<div class="small text-muted">{{ $frais['description'] }}</div>@endif</td>
                                    <td><span class="badge bg-secondary">{{ $frais['categorie'] ?? ($typeEleve === 'externe_td' ? 'TD' : '—') }}</span></td>
                                    <td class="text-end">{{ number_format((float) ($frais['montant'] ?? 0), 0, ',', ' ') }} FCFA</td>
                                    <td class="text-end text-success">{{ number_format((float) ($frais['deja_paye'] ?? 0), 0, ',', ' ') }} FCFA</td>
                                    <td class="text-end fw-bold text-danger">{{ number_format((float) ($frais['reste'] ?? 0), 0, ',', ' ') }} FCFA</td>
                                    <td class="text-center">
                                        @if (!empty($frais['selectionne']))
                                            <button type="button" class="btn btn-sm btn-danger" wire:click="toggleFrais({{ $index }})"><i class="bi bi-check-circle me-1"></i>Retirer</button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-primary" wire:click="toggleFrais({{ $index }})"><i class="bi bi-plus-circle me-1"></i>Ajouter</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-4"><i class="bi bi-info-circle me-2"></i>{{ $typeEleve === 'externe_td' ? 'Aucun frais TD restant à payer pour cette inscription.' : 'Aucun frais disponible pour cet élève.' }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- 7. Frais sélectionnés --}}
    @if (!empty($achats))
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white"><h5 class="mb-0"><i class="bi bi-cart-check me-2"></i>Frais sélectionnés</h5></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead><tr><th>Frais</th><th class="text-end">Montant</th><th class="text-end">Déjà payé</th><th class="text-end">Reste</th><th class="text-center">Action</th></tr></thead>
                        <tbody>
                            @foreach ($achats as $index => $achat)
                                <tr wire:key="achat-selectionne-{{ $achat['frais_id'] }}">
                                    <td><strong>{{ $achat['nom'] ?? '—' }}</strong>@if (!empty($achat['categorie']))<div class="small text-muted">{{ $achat['categorie'] }}</div>@endif</td>
                                    <td class="text-end">{{ number_format((float) ($achat['montant'] ?? 0), 0, ',', ' ') }} FCFA</td>
                                    <td class="text-end text-success">{{ number_format((float) ($achat['deja_paye'] ?? 0), 0, ',', ' ') }} FCFA</td>
                                    <td class="text-end fw-bold">{{ number_format((float) ($achat['reste'] ?? 0), 0, ',', ' ') }} FCFA</td>
                                    <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" wire:click="supprimerAchat({{ $index }})" title="Retirer"><i class="bi bi-trash"></i></button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- 8. Paiement --}}
    @if (!empty($achats))
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white"><h5 class="mb-0"><i class="bi bi-wallet2 me-2"></i>Enregistrement du paiement</h5></div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h6 class="fw-bold mb-3">Informations du paiement</h6>
                        <div class="mb-3"><label class="form-label">Nom</label><input class="form-control" wire:model.defer="nom" placeholder="Nom"></div>
                        <div class="mb-3"><label class="form-label">Prénom</label><input class="form-control" wire:model.defer="prenom" placeholder="Prénom"></div>
                        <div class="mb-3"><label class="form-label">Téléphone</label><input class="form-control" wire:model.defer="telephone" placeholder="Téléphone"></div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mode de paiement</label>
                            <select class="form-select" wire:model.defer="modePaiement">
                                <option value="">-- Choisir --</option><option value="especes">Espèces</option><option value="mobile_money">Mobile Money</option><option value="virement">Virement bancaire</option><option value="cheque">Chèque</option>
                            </select>
                            @error('modePaiement')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3"><label class="form-label fw-bold">Date de paiement</label><input type="date" class="form-control" wire:model.defer="datePaiement">@error('datePaiement')<div class="text-danger small mt-1">{{ $message }}</div>@enderror</div>
                    </div>
                    <div class="col-lg-6">
                        <h6 class="fw-bold mb-3">Récapitulatif</h6>
                        <div class="card bg-light border-0"><div class="card-body">
                            <div class="d-flex justify-content-between mb-3"><span>Total des frais sélectionnés</span><strong>{{ number_format((float) $totalFrais, 0, ',', ' ') }} FCFA</strong></div>
                            <div class="d-flex justify-content-between mb-3"><span>Déjà payé sur les frais sélectionnés</span><strong class="text-success">{{ number_format((float) $totalDejaPaye, 0, ',', ' ') }} FCFA</strong></div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3"><span class="fw-bold">Reste avant paiement</span><strong class="text-danger">{{ number_format((float) $resteAvantPaiement, 0, ',', ' ') }} FCFA</strong></div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Montant à payer <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg"><input type="number" min="0.01" step="1" class="form-control text-end fw-bold" wire:model.live="montantPaye" placeholder="0"><span class="input-group-text">FCFA</span></div>
                                @error('montantPaye')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="d-flex justify-content-between"><span class="fw-bold">Reste après paiement</span><strong class="text-primary fs-5">{{ number_format((float) $resteApresPaiement, 0, ',', ' ') }} FCFA</strong></div>
                        </div></div>
                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-primary btn-lg" wire:click="enregistrer" wire:loading.attr="disabled" wire:target="enregistrer" @disabled(empty($achats) || (float) $montantPaye <= 0)>
                                <span wire:loading.remove wire:target="enregistrer"><i class="bi bi-check-circle me-2"></i>Enregistrer le paiement</span>
                                <span wire:loading wire:target="enregistrer"><span class="spinner-border spinner-border-sm me-2"></span>Enregistrement en cours...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- 9. Dernier reçu --}}
    @if ($numeroRecuGenere)
        <div class="card shadow-sm border-success mb-4">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div><h5 class="text-success mb-1"><i class="bi bi-receipt me-2"></i>Paiement enregistré</h5><div class="text-muted">Numéro de reçu : <strong>{{ $numeroRecuGenere }}</strong></div></div>
                <button type="button" class="btn btn-outline-success" wire:click="imprimerRecu(@js($numeroRecuGenere))" wire:loading.attr="disabled" wire:target="imprimerRecu">
                    <span wire:loading.remove wire:target="imprimerRecu"><i class="bi bi-printer me-2"></i>Réimprimer le reçu</span>
                    <span wire:loading wire:target="imprimerRecu"><span class="spinner-border spinner-border-sm me-2"></span>Impression...</span>
                </button>
            </div>
        </div>
    @endif

    {{-- Indicateur de chargement --}}
    <div wire:loading wire:target="chargerClasses,updatedClasseId,updatedTypeEleve,updatedInscriptionId,updatedInscriptionTdExterneId,chargerFrais" class="position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index:9999;">
        <div class="alert alert-info shadow"><span class="spinner-border spinner-border-sm me-2"></span>Chargement en cours...</div>
    </div>
</div>

@once
<script>
    document.addEventListener('livewire:init', function () {
        function initialiserAnciensRecusAchat() {
            const contenu = document.getElementById('anciensRecusAchatCollapse');
            const icone = document.getElementById('iconeAnciensRecusAchat');
            if (!contenu || !icone || contenu.dataset.ecouteurInstalle) return;
            contenu.dataset.ecouteurInstalle = 'true';
            contenu.addEventListener('show.bs.collapse', function () {
                icone.classList.remove('bi-chevron-down');
                icone.classList.add('bi-chevron-up');
            });
            contenu.addEventListener('hide.bs.collapse', function () {
                icone.classList.remove('bi-chevron-up');
                icone.classList.add('bi-chevron-down');
            });
        }
        initialiserAnciensRecusAchat();
        Livewire.hook('morph.updated', function () { initialiserAnciensRecusAchat(); });
    });
</script>
@endonce