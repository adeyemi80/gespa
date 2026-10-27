<div class="container-fluid py-4">

    {{-- EN-TÊTE --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-body p-4 text-white"
             style="background: linear-gradient(135deg, #0d6efd, #084298);">

            <div class="d-flex flex-column flex-md-row
                        justify-content-between align-items-md-center gap-3">

                <div>
                    <h3 class="fw-bold mb-1">
                        <i class="bi bi-mortarboard me-2"></i>
                        État des paiements de scolarité
                    </h3>

                    <p class="mb-0 opacity-75">
                        Situation individuelle des scolarités et des frais d'inscription.
                    </p>
                </div>

                <button type="button"
                        wire:click="exporterPdf"
                        wire:loading.attr="disabled"
                        class="btn btn-light fw-semibold">

                    <span wire:loading.remove wire:target="exporterPdf">
                        <i class="bi bi-file-earmark-pdf me-2"></i>
                        @if(count($selection) > 0)
                            Exporter la sélection ({{ count($selection) }})
                        @else
                            Exporter la liste en PDF
                        @endif
                    </span>

                    <span wire:loading wire:target="exporterPdf">
                        Génération du PDF...
                    </span>
                </button>

            </div>
        </div>
    </div>

    {{-- FILTRES --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">

            <h5 class="fw-bold mb-3">
                <i class="bi bi-funnel me-2"></i>
                Filtrer et classer les inscriptions
            </h5>

            <div class="row g-3">

                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold">Année scolaire</label>
                    <select class="form-select" wire:model.live="anneeId">
                        <option value="">Sélectionner une année</option>
                        @foreach($annees as $annee)
                            <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold">Cycle</label>
                    <select class="form-select" wire:model.live="cycleId">
                        <option value="">Tous les cycles</option>
                        @foreach($cycles as $cycle)
                            <option value="{{ $cycle->id }}">{{ $cycle->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold">Classe</label>
                    <select class="form-select" wire:model.live="classeId">
                        <option value="">Toutes les classes</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold">Élève</label>
                    <select class="form-select" wire:model.live="eleveId">
                        <option value="">Tous les élèves</option>
                        @foreach($eleves as $eleve)
                            <option value="{{ $eleve->id }}">
                                {{ $eleve->nom }} {{ $eleve->prenom }}
                                @if($eleve->matricule) — {{ $eleve->matricule }} @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- TAUX --}}
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold">
                        Taux de scolarité payée
                    </label>
                    <select class="form-select" wire:model.live="tauxMax">
                        <option value="">Tous les taux</option>
                        <option value="0">Rien payé (0 %)</option>
                        <option value="10">Au plus 10 %</option>
                        <option value="25">Au plus 25 %</option>
                        <option value="50">Au plus 50 %</option>
                        <option value="75">Au plus 75 %</option>
                        <option value="99.9">Non soldé (moins de 100 %)</option>
                    </select>
                </div>

                {{-- TRI --}}
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold">Classement</label>
                    <select class="form-select" wire:model.live="tri">
                        <option value="nom">Ordre alphabétique</option>
                        <option value="paye_desc">Scolarité payée : décroissant</option>
                        <option value="paye_asc">Scolarité payée : croissant</option>
                        <option value="taux_desc">Taux de paiement : décroissant</option>
                        <option value="taux_asc">Taux de paiement : croissant</option>
                        <option value="reste_desc">Reste à payer : décroissant</option>
                    </select>
                </div>

            </div>

            <div class="mt-3 small text-muted">
                Les frais d'inscription et de réinscription ne sont retenus
                que pour les nouveaux inscrits. Les inscriptions sans scolarité
                attendue sont exclues du filtre par taux.
            </div>

        </div>
    </div>

    {{-- STATISTIQUES GÉNÉRALES --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3">
                    <div class="text-muted small mb-2">Nombre d'inscriptions</div>
                    <h3 class="fw-bold mb-0">{{ number_format($totaux['effectif'], 0, ',', ' ') }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3">
                    <div class="text-muted small mb-2">Nouveaux inscrits</div>
                    <h3 class="fw-bold mb-0">{{ number_format($totaux['nouveaux'], 0, ',', ' ') }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3">
                    <div class="text-muted small mb-2">Total payé</div>
                    <h4 class="fw-bold text-success mb-0">
                        {{ number_format($totaux['payeGeneral'], 0, ',', ' ') }}
                        <small class="fs-6">FCFA</small>
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3">
                    <div class="text-muted small mb-2">Total restant</div>
                    <h4 class="fw-bold text-danger mb-0">
                        {{ number_format($totaux['resteGeneral'], 0, ',', ' ') }}
                        <small class="fs-6">FCFA</small>
                    </h4>
                </div>
            </div>
        </div>

    </div>

    {{-- STATISTIQUES DE RECOUVREMENT --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 p-4">
            <h5 class="fw-bold mb-1">
                <i class="bi bi-graph-up-arrow me-2"></i>
                Statistiques de recouvrement
            </h5>
            <div class="text-muted small">
                Calculées sur l'ensemble des inscriptions de l'année, du cycle,
                de la classe ou de l'élève sélectionné.
            </div>
        </div>

        <div class="card-body pt-0">

            @php
                $couleurTaux = fn ($t) => $t >= 75 ? 'success' : ($t >= 40 ? 'warning' : 'danger');
                $blocs = [
                    [
                        'titre' => 'Scolarité',
                        'taux' => $totaux['tauxScolarite'],
                        'attendu' => $totaux['attenduScolarite'],
                        'paye' => $totaux['payeScolarite'],
                        'reste' => $totaux['resteScolarite'],
                    ],
                    [
                        'titre' => 'Frais d\'inscription',
                        'taux' => $totaux['tauxInscription'],
                        'attendu' => $totaux['attenduInscription'],
                        'paye' => $totaux['payeInscription'],
                        'reste' => $totaux['resteInscription'],
                    ],
                    [
                        'titre' => 'Global',
                        'taux' => $totaux['tauxGeneral'],
                        'attendu' => $totaux['attenduGeneral'],
                        'paye' => $totaux['payeGeneral'],
                        'reste' => $totaux['resteGeneral'],
                    ],
                ];
            @endphp

            <div class="row g-3">
                @foreach($blocs as $b)
                    <div class="col-lg-4">
                        <div class="p-3 rounded-3 bg-light h-100">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <span class="fw-semibold">{{ $b['titre'] }}</span>
                                <span class="fs-4 fw-bold text-{{ $couleurTaux($b['taux']) }}">
                                    {{ number_format($b['taux'], 1, ',', ' ') }} %
                                </span>
                            </div>

                            <div class="progress my-2" style="height: 10px;">
                                <div class="progress-bar bg-{{ $couleurTaux($b['taux']) }}"
                                     role="progressbar"
                                     style="width: {{ min(100, $b['taux']) }}%"></div>
                            </div>

                            <div class="small text-muted">
                                Attendu : <strong>{{ number_format($b['attendu'], 0, ',', ' ') }}</strong> FCFA<br>
                                Payé : <strong class="text-success">{{ number_format($b['paye'], 0, ',', ' ') }}</strong> FCFA<br>
                                Reste : <strong class="text-danger">{{ number_format($b['reste'], 0, ',', ' ') }}</strong> FCFA
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- RÉPARTITION PAR TRANCHE --}}
            <h6 class="fw-bold mt-4 mb-3">
                Répartition des inscriptions par taux de scolarité payée
            </h6>

            @php $maxTranche = max(1, collect($tranches)->max('count')); @endphp

            <div class="row g-2">
                @foreach($tranches as $t)
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="p-2 rounded-3 border text-center h-100">
                            <div class="small text-muted">{{ $t['label'] }}</div>
                            <div class="fs-4 fw-bold text-{{ $t['color'] }}">{{ $t['count'] }}</div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-{{ $t['color'] }}"
                                     style="width: {{ ($t['count'] / $maxTranche) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    {{-- TABLEAU DÉTAILLÉ --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                <div>
                    <h5 class="fw-bold mb-1">Détail des paiements par inscription</h5>
                    <p class="text-muted small mb-0">
                        {{ $lignes->count() }} inscription(s) affichée(s)
                        @if($tauxMax !== null && $tauxMax !== '')
                            — scolarité payée à
                            @if((float) $tauxMax == 0) 0 %
                            @elseif((float) $tauxMax == 99.9) moins de 100 %
                            @else {{ $tauxMax }} % au plus
                            @endif
                        @endif
                    </p>
                </div>

                <div class="d-flex gap-2 align-items-start">
                    <button type="button" class="btn btn-outline-primary btn-sm"
                            wire:click="selectionnerTout">
                        <i class="bi bi-check2-square me-1"></i>
                        Tout sélectionner / désélectionner
                    </button>

                    @if(count($selection) > 0)
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                wire:click="viderSelection">
                            Vider ({{ count($selection) }})
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 40px;">
                                <input type="checkbox" class="form-check-input"
                                       wire:click="selectionnerTout"
                                       @checked($lignes->isNotEmpty() && count($selection) === $lignes->count())>
                            </th>
                            <th>Élève</th>
                            <th>Matricule</th>
                            <th>Cycle / Classe</th>
                            <th>Type</th>
                            <th class="text-end">Scolarité attendue</th>
                            <th class="text-end">Scolarité payée</th>
                            <th class="text-end">Reste scolarité</th>
                            <th style="min-width: 120px;">Taux</th>
                            <th class="text-end">Inscription attendue</th>
                            <th class="text-end">Inscription payée</th>
                            <th class="text-end pe-3">Reste inscription</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($lignes as $ligne)

                            @php
                                $taux = $ligne['taux_scolarite'];
                                $c = $taux === null ? 'secondary' : $couleurTaux($taux);
                            @endphp

                            <tr wire:key="ligne-{{ $ligne['inscription_id'] }}">
                                <td class="ps-3">
                                    <input type="checkbox" class="form-check-input"
                                           wire:model.live="selection"
                                           value="{{ $ligne['inscription_id'] }}">
                                </td>

                                <td>
                                    <div class="fw-semibold">{{ $ligne['nom'] }}</div>
                                </td>

                                <td>{{ $ligne['matricule'] }}</td>

                                <td>
                                    <div>{{ $ligne['cycle'] }}</div>
                                    <small class="text-muted">{{ $ligne['classe'] }}</small>
                                </td>

                                <td>
                                    @if($ligne['est_nouveau'])
                                        <span class="badge bg-success">Nouveau</span>
                                    @else
                                        <span class="badge bg-secondary">Ancien</span>
                                    @endif
                                </td>

                                <td class="text-end">
                                    {{ number_format($ligne['attendu_scolarite'], 0, ',', ' ') }}
                                </td>

                                <td class="text-end text-success">
                                    {{ number_format($ligne['paye_scolarite'], 0, ',', ' ') }}
                                </td>

                                <td class="text-end text-danger">
                                    {{ number_format($ligne['reste_scolarite'], 0, ',', ' ') }}
                                </td>

                                <td>
                                    @if($taux === null)
                                        <span class="text-muted small">—</span>
                                    @else
                                        <div class="small fw-semibold text-{{ $c }}">
                                            {{ number_format($taux, 1, ',', ' ') }} %
                                        </div>
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar bg-{{ $c }}"
                                                 style="width: {{ min(100, $taux) }}%"></div>
                                        </div>
                                    @endif
                                </td>

                                <td class="text-end">
                                    {{ number_format($ligne['attendu_inscription'], 0, ',', ' ') }}
                                </td>

                                <td class="text-end text-success">
                                    {{ number_format($ligne['paye_inscription'], 0, ',', ' ') }}
                                </td>

                                <td class="text-end pe-3 text-danger">
                                    {{ number_format($ligne['reste_inscription'], 0, ',', ' ') }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="12" class="text-center py-5">
                                    <i class="bi bi-search fs-3 text-muted"></i>
                                    <p class="text-muted mt-2 mb-0">
                                        Aucune inscription ne correspond aux filtres sélectionnés.
                                    </p>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                    @if($lignes->isNotEmpty())
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="5" class="ps-3">TOTAL (liste affichée)</td>

                                <td class="text-end">
                                    {{ number_format($totauxAffiches['attenduScolarite'], 0, ',', ' ') }}
                                </td>
                                <td class="text-end text-success">
                                    {{ number_format($totauxAffiches['payeScolarite'], 0, ',', ' ') }}
                                </td>
                                <td class="text-end text-danger">
                                    {{ number_format($totauxAffiches['resteScolarite'], 0, ',', ' ') }}
                                </td>
                                <td>{{ number_format($totauxAffiches['tauxScolarite'], 1, ',', ' ') }} %</td>
                                <td class="text-end">
                                    {{ number_format($totauxAffiches['attenduInscription'], 0, ',', ' ') }}
                                </td>
                                <td class="text-end text-success">
                                    {{ number_format($totauxAffiches['payeInscription'], 0, ',', ' ') }}
                                </td>
                                <td class="text-end pe-3 text-danger">
                                    {{ number_format($totauxAffiches['resteInscription'], 0, ',', ' ') }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif

                </table>

            </div>
        </div>
    </div>

</div>