@php
    $dateCourante = \Carbon\Carbon::parse($date);
@endphp

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary mb-0">📅 PAIEMENTS DU JOUR</h3>

        <input
            type="date"
            wire:model.live="date"
            class="form-control"
            style="max-width:220px;">
    </div>

    {{-- Résumé du jour --}}
    <div class="card mb-4 shadow-sm border-primary"
         wire:key="resume-{{ $dateCourante->format('Y-m-d') }}">

        <div class="card-body text-center">

            <div class="text-muted small mb-3">
                <strong>{{ $dateCourante->format('d/m/Y') }}</strong>
                — {{ $paiements->count() }} paiement(s)
            </div>

            <button
                class="btn btn-outline-primary btn-sm mb-3"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#totalDuJour"
                aria-expanded="false"
                aria-controls="totalDuJour">

                <i class="bi bi-cash-stack"></i>
                Voir le total enregistré
            </button>

            <div class="collapse" id="totalDuJour">

                <div class="text-muted small">
                    TOTAL ENREGISTRÉ LE {{ $dateCourante->format('d/m/Y') }}
                </div>

                <div class="display-6 text-primary fw-bold mb-3">
                    {{ number_format($totalDuJour, 0, ',', ' ') }} FCFA
                </div>

            </div>

            <button
                class="btn btn-outline-success btn-sm"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#detailPaiements"
                aria-expanded="false"
                aria-controls="detailPaiements">

                <i class="bi bi-eye"></i>
                Voir le détail
            </button>

        </div>

    </div>

    {{-- Détail des paiements --}}
    <div class="collapse" id="detailPaiements">

        <div class="card shadow-sm">

            <div class="card-header bg-info text-white fw-bold">
                📋 Détail des paiements
            </div>

            <div class="card-body p-0">

                <table class="table table-striped table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th style="width:120px;">Heure</th>
                            <th>Référence / Élève</th>
                            <th class="text-end">Montant</th>
                            <th class="text-end">Cumul</th>
                        </tr>
                    </thead>

                    <tbody>

                        @php
                            $cumul = 0;
                        @endphp

                        @forelse($paiements as $paiement)

                            @php
                                $cumul += $paiement->montant_verse;
                            @endphp

                            <tr wire:key="paiement-{{ $paiement->id }}">

                                <td>
                                    {{ optional($paiement->created_at)->format('H:i') }}
                                </td>

                                <td>

                                    @if($paiement->reference)

                                        <strong>{{ $paiement->reference }}</strong>

                                    @else

                                        <strong>
                                            {{ $paiement->inscription->eleve->nom ?? '' }}
                                            {{ $paiement->inscription->eleve->prenom ?? '' }}
                                        </strong>

                                        <br>

                                        <small class="text-muted">
                                            {{ $paiement->inscription->classe->nom ?? '' }}
                                        </small>

                                    @endif

                                </td>

                                <td class="text-end fw-semibold">
                                    {{ number_format($paiement->montant_verse, 0, ',', ' ') }}
                                    FCFA
                                </td>

                                <td class="text-end fw-bold text-primary">
                                    {{ number_format($cumul, 0, ',', ' ') }}
                                    FCFA
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="text-center py-5 text-muted">

                                    <i class="bi bi-inbox fs-3"></i>

                                    <br>

                                    Aucun paiement enregistré pour cette date.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                    @if($paiements->isNotEmpty())

                        <tfoot class="table-primary">

                            <tr>

                                <td colspan="2" class="fw-bold">
                                    TOTAL DU JOUR
                                </td>

                                <td class="text-end fw-bold">
                                    {{ number_format($totalDuJour, 0, ',', ' ') }}
                                    FCFA
                                </td>

                                <td class="text-end fw-bold">
                                    {{ number_format($totalDuJour, 0, ',', ' ') }}
                                    FCFA
                                </td>

                            </tr>

                        </tfoot>

                    @endif

                </table>

            </div>

        </div>

    </div>

</div>