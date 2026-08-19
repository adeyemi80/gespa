{{-- resources/views/livewire/budget-synthese.blade.php --}}
<div class="container-fluid py-4">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h1 class="h3 fw-bold mb-1">Budget global</h1>
            <p class="text-muted mb-0 small">Synthèse recettes / dépenses par année scolaire</p>
        </div>
        
        {{-- dans le header de resources/views/livewire/budget-synthese.blade.php --}}
<div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1">Budget global</h1>
        <p class="text-muted mb-0 small">Synthèse recettes / dépenses par année scolaire</p>
    </div>
    <div class="d-flex gap-2">
        <select wire:model.live="anneeId" class="form-select shadow-sm" style="min-width: 200px;">
            @foreach ($annees as $annee)
                <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
            @endforeach
        </select>
        <a href="{{ route('budget-synthese.export-pdf', ['annee_id' => $anneeId]) }}"
           target="_blank"
           class="btn btn-outline-danger shadow-sm">
            <i class="bi bi-file-earmark-pdf me-1"></i> Exporter PDF
        </a>
    </div>
</div>
    </div>

    {{-- ============ RECETTES ============ --}}
    <div class="d-flex align-items-center gap-2 mb-3">
        <span class="badge rounded-pill bg-success-subtle text-success-emphasis px-3 py-2">
            <i class="bi bi-graph-up-arrow me-1"></i> Recettes
        </span>
        <h2 class="h5 fw-bold mb-0">Budget des recettes</h2>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #0d6efd !important;">
                <div class="card-body">
                    <p class="text-uppercase text-muted small mb-1 fw-semibold" style="font-size: .72rem; letter-spacing: .04em;">Total prévu</p>
                    <p class="h4 fw-bold mb-0 text-primary">{{ number_format($totalPrevu, 0, ',', ' ') }} <span class="fs-6 text-muted">FCFA</span></p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #198754 !important;">
                <div class="card-body">
                    <p class="text-uppercase text-muted small mb-1 fw-semibold" style="font-size: .72rem; letter-spacing: .04em;">Total réalisé</p>
                    <p class="h4 fw-bold mb-0 text-success">{{ number_format($totalRealise, 0, ',', ' ') }} <span class="fs-6 text-muted">FCFA</span></p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid {{ $totalEcart >= 0 ? '#198754' : '#dc3545' }} !important;">
                <div class="card-body">
                    <p class="text-uppercase text-muted small mb-1 fw-semibold" style="font-size: .72rem; letter-spacing: .04em;">Écart</p>
                    <p class="h4 fw-bold mb-0 {{ $totalEcart >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $totalEcart >= 0 ? '+' : '' }}{{ number_format($totalEcart, 0, ',', ' ') }} <span class="fs-6 text-muted">FCFA</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #6c757d !important;">
                <div class="card-body">
                    <p class="text-uppercase text-muted small mb-1 fw-semibold" style="font-size: .72rem; letter-spacing: .04em;">Taux de réalisation</p>
                    <p class="h4 fw-bold mb-1">{{ $tauxRecettes }}%</p>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: {{ min($tauxRecettes, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="table-light">
                        <th class="ps-4 py-3 text-muted small text-uppercase" style="letter-spacing:.03em;">Code</th>
                        <th class="py-3 text-muted small text-uppercase" style="letter-spacing:.03em;">Catégorie</th>
                        <th class="py-3 text-muted small text-uppercase" style="letter-spacing:.03em;">Cycle</th>
                        <th class="py-3 text-end text-muted small text-uppercase" style="letter-spacing:.03em;">Prévu</th>
                        <th class="py-3 text-end text-muted small text-uppercase" style="letter-spacing:.03em;">Réalisé</th>
                        <th class="py-3 text-end text-muted small text-uppercase" style="letter-spacing:.03em;">Écart</th>
                        <th class="py-3 pe-4 text-end text-muted small text-uppercase" style="letter-spacing:.03em; width: 160px;">Taux</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($budgetsRecettes as $ligne)
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-primary-subtle text-primary-emphasis fw-semibold">{{ $ligne['code'] }}</span>
                            </td>
                            <td class="fw-medium">{{ $ligne['nom'] }}</td>
                            <td class="fw-medium">
    {{ $ligne['nom'] }}
    <div class="mt-1">
        @foreach ($ligne['par_cycle'] as $nomCycle => $montant)
            @if ($montant > 0)
                <span class="badge bg-light text-dark border me-1" style="font-size: .68rem;">
                    {{ $nomCycle }} : {{ number_format($montant, 0, ',', ' ') }}
                </span>
            @endif
        @endforeach
    </div>
</td>
                            <td class="text-end">{{ number_format($ligne['prevu'], 0, ',', ' ') }}</td>
                            <td class="text-end">{{ number_format($ligne['realise'], 0, ',', ' ') }}</td>
                            <td class="text-end fw-semibold {{ $ligne['ecart'] >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $ligne['ecart'] >= 0 ? '+' : '' }}{{ number_format($ligne['ecart'], 0, ',', ' ') }}
                            </td>
                            <td class="pe-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: {{ min($ligne['taux'], 100) }}%"></div>
                                    </div>
                                    <small class="text-muted fw-semibold" style="min-width: 40px;">{{ $ligne['taux'] }}%</small>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                                Aucune donnée disponible pour cette année.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($budgetsRecettes->isNotEmpty())
                    <tfoot>
                        <tr class="table-light fw-bold">
                            <td colspan="2" class="ps-4 py-3">TOTAL</td>
                            <td class="text-end py-3">{{ number_format($totalPrevu, 0, ',', ' ') }}</td>
                            <td class="text-end py-3">{{ number_format($totalRealise, 0, ',', ' ') }}</td>
                            <td class="text-end py-3 {{ $totalEcart >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $totalEcart >= 0 ? '+' : '' }}{{ number_format($totalEcart, 0, ',', ' ') }}
                            </td>
                            <td class="pe-4 py-3">{{ $tauxRecettes }}%</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
{{-- Répartition des recettes par cycle --}}
<div class="row g-3 mb-4">
    @foreach ($cycles as $cycle)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-uppercase text-muted small mb-1 fw-semibold">{{ $cycle->nom }}</p>
                    <p class="h5 fw-bold mb-0 text-success">
                        {{ number_format($totauxRecettesParCycle[$cycle->nom] ?? 0, 0, ',', ' ') }} FCFA
                    </p>
                </div>
            </div>
        </div>
    @endforeach
</div>
    {{-- ============ DEPENSES ============ --}}
    <div class="d-flex align-items-center gap-2 mb-3">
        <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis px-3 py-2">
            <i class="bi bi-graph-down-arrow me-1"></i> Dépenses
        </span>
        <h2 class="h5 fw-bold mb-0">Budget des dépenses</h2>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #0d6efd !important;">
                <div class="card-body">
                    <p class="text-uppercase text-muted small mb-1 fw-semibold" style="font-size: .72rem; letter-spacing: .04em;">Total alloué</p>
                    <p class="h4 fw-bold mb-0 text-primary">{{ number_format($totalAlloue, 0, ',', ' ') }} <span class="fs-6 text-muted">FCFA</span></p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #dc3545 !important;">
                <div class="card-body">
                    <p class="text-uppercase text-muted small mb-1 fw-semibold" style="font-size: .72rem; letter-spacing: .04em;">Total utilisé</p>
                    <p class="h4 fw-bold mb-0 text-danger">{{ number_format($totalUtilise, 0, ',', ' ') }} <span class="fs-6 text-muted">FCFA</span></p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #6c757d !important;">
                <div class="card-body">
                    <p class="text-uppercase text-muted small mb-1 fw-semibold" style="font-size: .72rem; letter-spacing: .04em;">Total restant</p>
                    <p class="h4 fw-bold mb-0">{{ number_format($totalRestant, 0, ',', ' ') }} <span class="fs-6 text-muted">FCFA</span></p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #6c757d !important;">
                <div class="card-body">
                    <p class="text-uppercase text-muted small mb-1 fw-semibold" style="font-size: .72rem; letter-spacing: .04em;">Taux d'utilisation</p>
                    <p class="h4 fw-bold mb-1">{{ $tauxDepenses }}%</p>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-danger" style="width: {{ min($tauxDepenses, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="table-light">
                        <th class="ps-4 py-3 text-muted small text-uppercase" style="letter-spacing:.03em;">Code</th>
                        <th class="py-3 text-muted small text-uppercase" style="letter-spacing:.03em;">Catégorie</th>
                         <th class="py-3 text-muted small text-uppercase" style="letter-spacing:.03em;">Cycle</th>
                        <th class="py-3 text-end text-muted small text-uppercase" style="letter-spacing:.03em;">Alloué</th>
                        <th class="py-3 text-end text-muted small text-uppercase" style="letter-spacing:.03em;">Utilisé</th>
                        <th class="py-3 text-end text-muted small text-uppercase" style="letter-spacing:.03em;">Restant</th>
                        <th class="py-3 pe-4 text-end text-muted small text-uppercase" style="letter-spacing:.03em; width: 160px;">Taux</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($budgetsDepenses as $ligne)
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-secondary-subtle text-secondary-emphasis fw-semibold">{{ $ligne['code'] }}</span>
                            </td>
                            <td class="fw-medium">{{ $ligne['nom'] }}</td>
                            <td class="fw-medium">
    {{ $ligne['nom'] }}
    <div class="mt-1">
        @foreach ($ligne['par_cycle'] as $nomCycle => $montant)
            @if ($montant > 0)
                <span class="badge bg-light text-dark border me-1" style="font-size: .68rem;">
                    {{ $nomCycle }} : {{ number_format($montant, 0, ',', ' ') }}
                </span>
            @endif
        @endforeach
    </div>
</td>
                            <td class="text-end">{{ number_format($ligne['alloue'], 0, ',', ' ') }}</td>
                            <td class="text-end">{{ number_format($ligne['utilise'], 0, ',', ' ') }}</td>
                            <td class="text-end">{{ number_format($ligne['restant'], 0, ',', ' ') }}</td>
                            <td class="pe-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 6px;">
                                        <div class="progress-bar bg-danger" style="width: {{ min($ligne['taux'], 100) }}%"></div>
                                    </div>
                                    <small class="text-muted fw-semibold" style="min-width: 40px;">{{ $ligne['taux'] }}%</small>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                                Aucune donnée disponible pour cette année.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($budgetsDepenses->isNotEmpty())
                    <tfoot>
                        <tr class="table-light fw-bold">
                            <td colspan="2" class="ps-4 py-3">TOTAL</td>
                            <td class="text-end py-3">{{ number_format($totalAlloue, 0, ',', ' ') }}</td>
                            <td class="text-end py-3">{{ number_format($totalUtilise, 0, ',', ' ') }}</td>
                            <td class="text-end py-3">{{ number_format($totalRestant, 0, ',', ' ') }}</td>
                            <td class="pe-4 py-3">{{ $tauxDepenses }}%</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
{{-- Répartition des recettes par cycle --}}
<div class="row g-3 mb-4">
    @foreach ($cycles as $cycle)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-uppercase text-muted small mb-1 fw-semibold">{{ $cycle->nom }}</p>
                    <p class="h5 fw-bold mb-0 text-success">
                        {{ number_format($totauxDepensesParCycle[$cycle->nom] ?? 0, 0, ',', ' ') }} FCFA
                    </p>
                </div>
            </div>
        </div>
    @endforeach
</div>
    {{-- ============ SOLDE ============ --}}
    <div class="card border-0 shadow" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
        <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-calculator"></i> Solde
            </h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <p class="text-muted small mb-0">Solde prévisionnel</p>
                                <span class="badge {{ $soldePrevu >= 0 ? 'bg-success' : 'bg-danger' }} rounded-pill">
                                    {{ $soldePrevu >= 0 ? 'Excédentaire' : 'Déficitaire' }}
                                </span>
                            </div>
                            <p class="h2 fw-bold mb-1 {{ $soldePrevu >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $soldePrevu >= 0 ? '+' : '' }}{{ number_format($soldePrevu, 0, ',', ' ') }} <span class="fs-6 text-muted fw-normal">FCFA</span>
                            </p>
                            <p class="text-muted small mb-0">Prévu (recettes) − Alloué (dépenses)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <p class="text-muted small mb-0">Solde réalisé</p>
                                <span class="badge {{ $soldeRealise >= 0 ? 'bg-success' : 'bg-danger' }} rounded-pill">
                                    {{ $soldeRealise >= 0 ? 'Excédentaire' : 'Déficitaire' }}
                                </span>
                            </div>
                            <p class="h2 fw-bold mb-1 {{ $soldeRealise >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $soldeRealise >= 0 ? '+' : '' }}{{ number_format($soldeRealise, 0, ',', ' ') }} <span class="fs-6 text-muted fw-normal">FCFA</span>
                            </p>
                            <p class="text-muted small mb-0">Réalisé (recettes) − Utilisé (dépenses)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>