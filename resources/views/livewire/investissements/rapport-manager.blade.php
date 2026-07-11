@php
    $onglets = [
        'apercu' => "Vue d'ensemble",
        'investisseurs' => 'Investisseurs',
        'investissements' => 'Investissements',
        'versements' => 'Versements',
        'benefices' => 'Bénéfices',
        'retraits' => 'Retraits de capital',
        'situation' => 'Situation investisseur',
    ];

    $iconesOnglets = [
        'apercu' => '📊',
        'investisseurs' => '👥',
        'investissements' => '💼',
        'versements' => '💵',
        'benefices' => '📈',
        'retraits' => '↩️',
        'situation' => '🔍',
    ];
@endphp

<div class="py-4" style="background-color: #f8f9fc; min-height: 100%;">
    <div class="container">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-dark fw-bold">Rapports</h1>
                <p class="text-muted small mb-0">Suivi financier des investissements et des investisseurs</p>
            </div>
        </div>

        {{-- Onglets en pills --}}
        <div class="d-flex flex-wrap gap-2 mb-4">
            @foreach ($onglets as $cle => $libelle)
                <button
                    type="button"
                    wire:click="changerOnglet('{{ $cle }}')"
                    class="btn btn-sm rounded-pill px-3 {{ $onglet === $cle ? 'btn-primary text-white shadow-sm' : 'btn-light text-secondary border' }}"
                >
                    <span class="me-1">{{ $iconesOnglets[$cle] }}</span>{{ $libelle }}
                </button>
            @endforeach
        </div>

        {{-- Filtres de période --}}
        @if (in_array($onglet, ['investissements', 'versements', 'benefices', 'retraits']))
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-3 col-lg-2">
                            <label class="form-label small text-muted fw-medium">Du</label>
                            <input type="date" wire:model.live="dateDebut" class="form-control">
                        </div>

                        <div class="col-12 col-md-3 col-lg-2">
                            <label class="form-label small text-muted fw-medium">Au</label>
                            <input type="date" wire:model.live="dateFin" class="form-control">
                        </div>

                        @if ($dateDebut || $dateFin)
                            <div class="col-12 col-md-3 col-lg-2">
                                <button wire:click="resetFiltres" type="button" class="btn btn-outline-secondary w-100">
                                    Réinitialiser
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- ============================== --}}
        {{-- VUE D'ENSEMBLE --}}
        {{-- ============================== --}}
        @if ($onglet === 'apercu')
            @php
                $stats = $this->statistiquesGlobales;

                $cartes = [
                    ['label' => 'Investisseurs', 'valeur' => $stats['investisseurs'], 'unite' => '', 'couleur' => 'primary', 'icone' => '👥'],
                    ['label' => 'Capital investi', 'valeur' => $stats['capital'], 'unite' => 'F CFA', 'couleur' => 'info', 'icone' => '💼'],
                    ['label' => 'Total versements', 'valeur' => $stats['versements'], 'unite' => 'F CFA', 'couleur' => 'primary', 'icone' => '💵'],
                    ['label' => 'Bénéfices générés', 'valeur' => $stats['benefices'], 'unite' => 'F CFA', 'couleur' => 'warning', 'icone' => '📈'],
                    ['label' => 'Bénéfices payés', 'valeur' => $stats['benefices_payes'], 'unite' => 'F CFA', 'couleur' => 'success', 'icone' => '✅'],
                    ['label' => 'Retraits de capital', 'valeur' => $stats['retraits'], 'unite' => 'F CFA', 'couleur' => 'danger', 'icone' => '↩️'],
                ];
            @endphp

            <div class="row g-3">
                @foreach ($cartes as $carte)
                    <div class="col-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100 border-start border-{{ $carte['couleur'] }} border-4">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-{{ $carte['couleur'] }}-subtle d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width: 48px; height: 48px; font-size: 1.25rem;">
                                    {{ $carte['icone'] }}
                                </div>
                                <div>
                                    <div class="text-muted small">{{ $carte['label'] }}</div>
                                    <div class="fs-5 fw-bold text-{{ $carte['couleur'] }}">
                                        {{ number_format($carte['valeur'], 0, ',', ' ') }} {{ $carte['unite'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- ============================== --}}
        {{-- INVESTISSEURS --}}
        {{-- ============================== --}}
        @if ($onglet === 'investisseurs')
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3">
                    <span class="fw-semibold text-dark">👥 Liste des investisseurs</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Investisseur</th>
                                <th>Nb. investissements</th>
                                <th class="text-end">Capital total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($investisseurs as $investisseur)
                                <tr wire:key="inv-{{ $investisseur->id }}">
                                    <td>{{ $investisseur->nom }} {{ $investisseur->prenom }}</td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary rounded-pill">
                                            {{ $investisseur->investissements_count }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-semibold text-dark">
                                        {{ number_format($investisseur->investissements_sum_montant ?? 0, 0, ',', ' ') }} F CFA
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Aucun investisseur.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                {{ $investisseurs->links() }}
            </div>
        @endif

        {{-- ============================== --}}
        {{-- INVESTISSEMENTS --}}
        {{-- ============================== --}}
        @if ($onglet === 'investissements')
            <div class="card border-0 shadow-sm mb-3 bg-info-subtle">
                <div class="card-body d-flex align-items-center gap-2">
                    <span class="fs-5">💼</span>
                    <span class="text-dark">Total sur la période :</span>
                    <span class="fw-bold text-info-emphasis fs-5">{{ number_format($totalInvestissements, 0, ',', ' ') }} F CFA</span>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Investisseur</th>
                                <th class="text-end">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($investissements as $investissement)
                                <tr wire:key="invmt-{{ $investissement->id }}">
                                    <td>{{ \Carbon\Carbon::parse($investissement->date_investissement)->format('d/m/Y') }}</td>
                                    <td>{{ $investissement->investisseur->nom ?? '—' }} {{ $investissement->investisseur->prenom ?? '' }}</td>
                                    <td class="text-end fw-semibold text-dark">{{ number_format($investissement->montant, 0, ',', ' ') }} F CFA</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Aucun investissement trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                {{ $investissements->links() }}
            </div>
        @endif

        {{-- ============================== --}}
        {{-- VERSEMENTS --}}
        {{-- ============================== --}}
        @if ($onglet === 'versements')
            <div class="card border-0 shadow-sm mb-3 bg-primary-subtle">
                <div class="card-body d-flex align-items-center gap-2">
                    <span class="fs-5">💵</span>
                    <span class="text-dark">Total sur la période :</span>
                    <span class="fw-bold text-primary fs-5">{{ number_format($totalVersements, 0, ',', ' ') }} F CFA</span>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Investisseur</th>
                                <th class="text-end">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($versements as $versement)
                                <tr wire:key="vers-{{ $versement->id }}">
                                    <td>{{ \Carbon\Carbon::parse($versement->date_versement)->format('d/m/Y') }}</td>
                                    <td>{{ $versement->investissement->investisseur->nom ?? '—' }} {{ $versement->investissement->investisseur->prenom ?? '' }}</td>
                                    <td class="text-end fw-semibold text-dark">{{ number_format($versement->montant, 0, ',', ' ') }} F CFA</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Aucun versement trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                {{ $versements->links() }}
            </div>
        @endif

        {{-- ============================== --}}
        {{-- BÉNÉFICES --}}
        {{-- ============================== --}}
        @if ($onglet === 'benefices')
            <div class="row g-3 mb-3">
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
                        <div class="card-body">
                            <div class="text-muted small">📈 Bénéfices sur la période</div>
                            <div class="fs-5 fw-bold text-warning-emphasis">{{ number_format($totalBenefices, 0, ',', ' ') }} F CFA</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm h-100 border-start border-info border-4">
                        <div class="card-body">
                            <div class="text-muted small">📤 Total distribué (toutes périodes)</div>
                            <div class="fs-5 fw-bold text-info-emphasis">{{ number_format($totalDistribue, 0, ',', ' ') }} F CFA</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
                        <div class="card-body">
                            <div class="text-muted small">✅ Total payé (toutes périodes)</div>
                            <div class="fs-5 fw-bold text-success">{{ number_format($totalPaye, 0, ',', ' ') }} F CFA</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Période</th>
                                <th class="text-end">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($benefices as $benefice)
                                <tr wire:key="bnf-{{ $benefice->id }}">
                                    <td>
                                        {{ \Carbon\Carbon::parse($benefice->date_debut)->format('d/m/Y') }}
                                        &rarr;
                                        {{ \Carbon\Carbon::parse($benefice->date_fin)->format('d/m/Y') }}
                                    </td>
                                    <td class="text-end fw-semibold text-dark">{{ number_format($benefice->montant, 0, ',', ' ') }} F CFA</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-4 text-muted">Aucun bénéfice trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                {{ $benefices->links() }}
            </div>
        @endif

        {{-- ============================== --}}
        {{-- RETRAITS DE CAPITAL --}}
        {{-- ============================== --}}
        @if ($onglet === 'retraits')
            <div class="card border-0 shadow-sm mb-3 bg-danger-subtle">
                <div class="card-body d-flex align-items-center gap-2">
                    <span class="fs-5">↩️</span>
                    <span class="text-dark">Total sur la période :</span>
                    <span class="fw-bold text-danger fs-5">{{ number_format($totalRetraits, 0, ',', ' ') }} F CFA</span>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Investisseur</th>
                                <th class="text-end">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($retraits as $retrait)
                                <tr wire:key="ret-{{ $retrait->id }}">
                                    <td>{{ \Carbon\Carbon::parse($retrait->date_retrait)->format('d/m/Y') }}</td>
                                    <td>{{ $retrait->investissement->investisseur->nom ?? '—' }} {{ $retrait->investissement->investisseur->prenom ?? '' }}</td>
                                    <td class="text-end fw-semibold text-dark">{{ number_format($retrait->montant, 0, ',', ' ') }} F CFA</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Aucun retrait trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                {{ $retraits->links() }}
            </div>
        @endif

        {{-- ============================== --}}
        {{-- SITUATION D'UN INVESTISSEUR --}}
        {{-- ============================== --}}
        @if ($onglet === 'situation')
            @if (! $investisseurId)
                <div class="card border-0 shadow-sm">
                    <div class="card-body position-relative">
                        <label class="form-label fw-medium">🔍 Rechercher un investisseur</label>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="rechercheInvestisseur"
                            placeholder="Nom, prénom ou téléphone..."
                            class="form-control"
                        >

                        @if ($this->suggestionsInvestisseurs->isNotEmpty())
                            <div class="list-group mt-2">
                                @foreach ($this->suggestionsInvestisseurs as $suggestion)
                                    <button
                                        type="button"
                                        wire:click="selectionnerInvestisseur({{ $suggestion->id }})"
                                        class="list-group-item list-group-item-action text-start"
                                    >
                                        <div class="fw-medium">{{ $suggestion->nom }} {{ $suggestion->prenom }}</div>
                                        <small class="text-muted">{{ $suggestion->telephone ?: 'sans téléphone' }}</small>
                                    </button>
                                @endforeach
                            </div>
                        @elseif (filled($rechercheInvestisseur))
                            <div class="mt-2 text-muted small">Aucun investisseur trouvé.</div>
                        @endif
                    </div>
                </div>
            @else
                @php
                    $inv = $this->investisseurSelectionne;
                @endphp

                @if ($inv)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h5 mb-0 fw-bold">👤 {{ $inv->nom }} {{ $inv->prenom }}</h2>
                        <button wire:click="changerInvestisseur" type="button" class="btn btn-outline-secondary btn-sm">
                            Changer d'investisseur
                        </button>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-4">
                            <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
                                <div class="card-body">
                                    <div class="text-muted small">💼 Capital investi</div>
                                    <div class="fs-5 fw-bold text-primary">{{ number_format($this->situationCapital, 0, ',', ' ') }} F CFA</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
                                <div class="card-body">
                                    <div class="text-muted small">📈 Bénéfices attribués</div>
                                    <div class="fs-5 fw-bold text-success">{{ number_format($this->situationBenefices, 0, ',', ' ') }} F CFA</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="card border-0 shadow-sm h-100 border-start border-secondary border-4">
                                <div class="card-body">
                                    <div class="text-muted small">📁 Nombre d'investissements</div>
                                    <div class="fs-5 fw-bold text-secondary">{{ $inv->investissements->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Investissement</th>
                                        <th class="text-end">Total versé</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($this->situationVersements as $investissement)
                                        <tr wire:key="sit-{{ $investissement->id }}">
                                            <td>#{{ $investissement->id }}</td>
                                            <td class="text-end fw-semibold text-dark">
                                                {{ number_format($investissement->versements_sum_montant ?? 0, 0, ',', ' ') }} F CFA
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-4 text-muted">
                                                Aucun investissement pour cet investisseur.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="alert alert-danger mb-0">Investisseur introuvable.</div>
                @endif
            @endif
        @endif

    </div>
</div>