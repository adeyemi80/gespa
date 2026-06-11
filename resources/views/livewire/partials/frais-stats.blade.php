{{-- resources/views/livewire/partials/frais-stats.blade.php --}}
{{-- Variables : $frais, $totaux, $titre, $badge, $show_nb_eleves --}}

{{-- Cartes totaux --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-4">
        <div class="card border-0 bg-light rounded-3 h-100">
            <div class="card-body p-4">
                <p class="text-muted small fw-semibold text-uppercase mb-1">Total dû</p>
                <p class="fs-4 fw-bold text-dark mb-0">
                    {{ number_format($totaux['montant_total'], 0, ',', ' ') }}
                    <span class="fs-6 fw-normal text-muted">FCFA</span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="card border-0 bg-success bg-opacity-10 rounded-3 h-100">
            <div class="card-body p-4">
                <p class="text-success small fw-semibold text-uppercase mb-1">Déjà payé</p>
                <p class="fs-4 fw-bold text-success mb-1">
                    {{ number_format($totaux['montant_paye'], 0, ',', ' ') }}
                    <span class="fs-6 fw-normal">FCFA</span>
                </p>
                @if($totaux['montant_total'] > 0)
                    @php $pctG = min(100, round($totaux['montant_paye'] / $totaux['montant_total'] * 100)); @endphp
                    <div class="progress" style="height:6px;">
                        <div class="progress-bar bg-success" style="width:{{ $pctG }}%"></div>
                    </div>
                    <p class="text-success small mt-1 mb-0">{{ $pctG }}% réglé</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="card border-0 bg-danger bg-opacity-10 rounded-3 h-100">
            <div class="card-body p-4">
                <p class="text-danger small fw-semibold text-uppercase mb-1">Reste à payer</p>
                <p class="fs-4 fw-bold text-danger mb-0">
                    {{ number_format($totaux['reste'], 0, ',', ' ') }}
                    <span class="fs-6 fw-normal">FCFA</span>
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Tableau --}}
<div class="card border-0 border-top rounded-3 overflow-hidden">
    <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3 px-4">
        <h6 class="fw-semibold text-dark mb-0">{{ $titre }}</h6>
        <div class="d-flex align-items-center gap-2">
            @if($badge)
                <span class="badge bg-primary bg-opacity-10 text-primary fw-normal">{{ $badge }}</span>
            @endif
            <span class="badge bg-secondary bg-opacity-10 text-secondary fw-normal">
                {{ count($frais) }} ligne{{ count($frais) > 1 ? 's' : '' }}
            </span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.05em;">
                <tr>
                    <th class="px-4 py-3 fw-semibold">Frais</th>
                    @if($show_nb_eleves)
                        <th class="px-4 py-3 fw-semibold text-center">Élèves</th>
                    @endif
                    <th class="px-4 py-3 fw-semibold text-end">Montant dû</th>
                    <th class="px-4 py-3 fw-semibold text-end">Payé</th>
                    <th class="px-4 py-3 fw-semibold text-end">Reste</th>
                    <th class="px-4 py-3 fw-semibold text-center">Statut</th>
                    <th class="px-4 py-3 fw-semibold" style="min-width:130px;">Progression</th>
                </tr>
            </thead>
            <tbody>
                @foreach($frais as $item)
                @php
                    $item = (object) $item;
                    $pct = $item->montant_frais > 0
                        ? min(100, round($item->montant_paye / $item->montant_frais * 100))
                        : 100;

                    [$badgeClass, $badgeLabel] = match($item->statut) {
                        'soldé'              => ['bg-success text-success',   'Soldé'],
                        'partiellement_payé' => ['bg-warning text-warning',   'Partiel'],
                        'non_payé'           => ['bg-danger text-danger',     'Non payé'],
                        default              => ['bg-secondary text-secondary', $item->statut],
                    };
                    $barClass = match($item->statut) {
                        'soldé'              => 'bg-success',
                        'partiellement_payé' => 'bg-warning',
                        default              => 'bg-danger',
                    };
                @endphp
                <tr>
                    <td class="px-4 py-3">
                        <span class="fw-medium text-dark text-capitalize">{{ $item->nom_frais }}</span>
                        @if(isset($item->est_arriere) && $item->est_arriere)
                            <span class="d-block text-warning small mt-1">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>Arriéré
                            </span>
                        @endif
                    </td>
                    @if($show_nb_eleves)
                        <td class="px-4 py-3 text-center text-muted">
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                {{ $item->nb_eleves ?? '—' }}
                            </span>
                        </td>
                    @endif
                    <td class="px-4 py-3 text-end font-monospace text-dark">
                        {{ number_format($item->montant_frais, 0, ',', ' ') }}
                    </td>
                    <td class="px-4 py-3 text-end font-monospace text-success">
                        {{ number_format($item->montant_paye, 0, ',', ' ') }}
                    </td>
                    <td class="px-4 py-3 text-end font-monospace text-danger">
                        {{ number_format($item->reste, 0, ',', ' ') }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="badge {{ $badgeClass }} bg-opacity-10 fw-medium px-2 py-1 rounded-pill" style="font-size:.72rem;">
                            {{ $badgeLabel }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height:6px;">
                                <div class="progress-bar {{ $barClass }}" style="width:{{ $pct }}%;"></div>
                            </div>
                            <span class="text-muted" style="font-size:.72rem;min-width:30px;text-align:right;">{{ $pct }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light border-top fw-semibold">
                <tr>
                    <td class="px-4 py-3 text-uppercase text-muted small" @if($show_nb_eleves) colspan="2" @endif>Total</td>
                    <td class="px-4 py-3 text-end font-monospace text-dark">
                        {{ number_format($totaux['montant_total'], 0, ',', ' ') }}
                    </td>
                    <td class="px-4 py-3 text-end font-monospace text-success">
                        {{ number_format($totaux['montant_paye'], 0, ',', ' ') }}
                    </td>
                    <td class="px-4 py-3 text-end font-monospace text-danger">
                        {{ number_format($totaux['reste'], 0, ',', ' ') }}
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>