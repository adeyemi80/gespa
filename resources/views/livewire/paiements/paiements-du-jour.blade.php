@php
    $dateCourante = \Carbon\Carbon::parse($date);
@endphp

<div class="container py-5">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h3 class="text-primary mb-0">
            📅 PAIEMENTS DU JOUR
        </h3>

        <input
            type="date"
            wire:model.live="date"
            class="form-control"
            style="max-width:220px;"
        >

    </div>


    {{-- =========================================================
         RÉSUMÉ DU JOUR
    ========================================================== --}}
    <div
        class="card mb-4 shadow-sm border-primary"
        wire:key="resume-{{ $dateCourante->format('Y-m-d') }}"
    >

        <div class="card-body text-center">

            <div class="text-muted small mb-3">

                <strong>
                    {{ $dateCourante->format('d/m/Y') }}
                </strong>

                — {{ $paiements->count() }} paiement(s)

            </div>


            {{-- =================================================
                 TOTAL DU JOUR
            ================================================== --}}
            <button
                class="btn btn-outline-primary btn-sm mb-3"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#totalDuJour"
                aria-expanded="false"
                aria-controls="totalDuJour"
            >

                <i class="bi bi-cash-stack"></i>

                Voir le total enregistré

            </button>


            <div
                class="collapse"
                id="totalDuJour"
            >

                <div class="text-muted small">

                    TOTAL ENREGISTRÉ LE
                    {{ $dateCourante->format('d/m/Y') }}

                </div>

                <div class="display-6 text-primary fw-bold mb-3">

                    {{ number_format($totalDuJour, 0, ',', ' ') }}

                    FCFA

                </div>

            </div>


            {{-- =================================================
                 DÉTAIL
            ================================================== --}}
            <button
                class="btn btn-outline-success btn-sm"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#detailPaiements"
                aria-expanded="false"
                aria-controls="detailPaiements"
            >

                <i class="bi bi-eye"></i>

                Voir le détail

            </button>

        </div>

    </div>


    {{-- =========================================================
         DÉTAIL DES PAIEMENTS
    ========================================================== --}}
    <div
        class="collapse"
        id="detailPaiements"
    >

        <div class="card shadow-sm">

            <div class="card-header bg-info text-white fw-bold">

                📋 Détail des paiements

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table
                        class="table table-striped table-hover align-middle mb-0"
                    >

                        {{-- =================================================
                             EN-TÊTE DU TABLEAU
                        ================================================== --}}
                        <thead class="table-light">

                            <tr>

                                <th style="width:120px;">
                                    Heure
                                </th>

                                <th>
                                    Référence / Élève
                                </th>

                                <th>
                                    Description du frais
                                </th>

                                <th class="text-end">
                                    Montant
                                </th>

                                <th class="text-end">
                                    Cumul
                                </th>

                            </tr>

                        </thead>


                        {{-- =================================================
                             CORPS DU TABLEAU
                        ================================================== --}}
                        <tbody>

                            @php
                                $cumul = 0;
                            @endphp


                            @forelse($paiements as $paiement)

                                @php
                                    $cumul += $paiement->montant_verse;
                                @endphp


                                <tr
                                    wire:key="paiement-{{ $paiement->id }}"
                                >

                                    {{-- =====================================
                                         HEURE
                                    ====================================== --}}
                                    <td>

                                        {{ optional($paiement->created_at)->format('H:i') }}

                                    </td>


                                    {{-- =====================================
                                         ÉLÈVE / RÉFÉRENCE
                                    ====================================== --}}
                                    <td>

                                        <strong>
                                            {{ $paiement->eleve ?: 'Élève non identifié' }}
                                        </strong>

                                        <br>

                                        <small class="text-muted">

                                            {{ $paiement->classe ?? '-' }}

                                        </small>


                                        @if($paiement->reference)

                                            <br>

                                            <small class="text-secondary">

                                                Réf. :
                                                {{ $paiement->reference }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- =====================================
                                         DESCRIPTION DU FRAIS
                                    ====================================== --}}
                                    <td>

                                        <span class="fw-semibold text-dark">

                                            {{ $paiement->description ?? 'Paiement' }}

                                        </span>

                                    </td>


                                    {{-- =====================================
                                         MONTANT
                                    ====================================== --}}
                                    <td class="text-end fw-semibold">

                                        {{ number_format(
                                            $paiement->montant_verse,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                    </td>


                                    {{-- =====================================
                                         CUMUL
                                    ====================================== --}}
                                    <td class="text-end fw-bold text-primary">

                                        {{ number_format(
                                            $cumul,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                    </td>

                                </tr>


                            @empty

                                {{-- =========================================
                                     AUCUN PAIEMENT
                                ========================================== --}}
                                <tr>

                                    <td
                                        colspan="5"
                                        class="text-center py-5 text-muted"
                                    >

                                        <i class="bi bi-inbox fs-3"></i>

                                        <br>

                                        Aucun paiement enregistré
                                        pour cette date.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>


                        {{-- =================================================
                             TOTAL GÉNÉRAL
                        ================================================== --}}
                        @if($paiements->isNotEmpty())

                            <tfoot class="table-primary">

                                <tr>

                                    <td
                                        colspan="3"
                                        class="fw-bold"
                                    >

                                        TOTAL DU JOUR

                                    </td>


                                    <td class="text-end fw-bold">

                                        {{ number_format(
                                            $totalDuJour,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                    </td>


                                    <td class="text-end fw-bold">

                                        {{ number_format(
                                            $totalDuJour,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                    </td>

                                </tr>

                            </tfoot>

                        @endif

                    </table>

                </div>

            </div>

        </div>


        {{-- =========================================================
             RÉCAPITULATIF PAR TYPE DE FRAIS
        ========================================================== --}}
        @if($paiements->isNotEmpty() && $totalParFrais->isNotEmpty())

            <div class="card shadow-sm mt-4">

                <div class="card-header bg-success text-white fw-bold">

                    <i class="bi bi-bar-chart-fill"></i>

                    Récapitulatif par type de frais

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        Type de frais
                                    </th>

                                    <th class="text-center">
                                        Nombre de paiements
                                    </th>

                                    <th class="text-end">
                                        Total
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($totalParFrais as $description => $montant)

                                    @php
                                        $nombrePaiements = $paiements
                                            ->where('description', $description)
                                            ->count();
                                    @endphp

                                    <tr>

                                        <td>

                                            <span class="fw-semibold">

                                                {{ $description }}

                                            </span>

                                        </td>


                                        <td class="text-center">

                                            <span class="badge bg-white
                                            ">

                                                {{ $nombrePaiements }}

                                            </span>

                                        </td>


                                        <td class="text-end fw-bold text-success">

                                            {{ number_format(
                                                $montant,
                                                0,
                                                ',',
                                                ' '
                                            ) }}

                                            FCFA

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>


                            {{-- =================================================
                                 TOTAL DE TOUS LES FRAIS
                            ================================================== --}}
                            <tfoot class="table-success">

                                <tr>

                                    <td
                                        colspan="2"
                                        class="fw-bold"
                                    >

                                        TOTAL GÉNÉRAL

                                    </td>

                                    <td class="text-end fw-bold">

                                        {{ number_format(
                                            $totalDuJour,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                    </td>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>
