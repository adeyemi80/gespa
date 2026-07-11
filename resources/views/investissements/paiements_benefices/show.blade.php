@extends('tableau.neutre')

@section('content')

<div class="container py-4">

    <!-- Bouton Retour -->
    <div class="mb-4">
        <button
            onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
            class="btn btn-secondary btn-lg shadow-sm">
            <i class="bi bi-arrow-left-circle"></i>
            Retour
        </button>
    </div>


    <!-- En-tête -->
    <div class="card shadow border-0 mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <h2 class="fw-bold mb-0">
                    <i class="bi bi-receipt text-primary"></i>
                    Détail du paiement de bénéfice
                </h2>


                <div class="d-flex gap-2">

                    <a href="{{ route('paiements-benefices.edit', $paiementBenefice) }}"
                       class="btn btn-warning btn-lg">
                        <i class="bi bi-pencil-square"></i>
                        Modifier
                    </a>


                    <a href="{{ route('paiements-benefices.index') }}"
                       class="btn btn-dark btn-lg">
                        <i class="bi bi-list"></i>
                        Retour
                    </a>

                </div>

            </div>

        </div>

    </div>


    <!-- Informations -->
    <div class="card shadow border-0">


        <div class="card-header bg-primary text-white">

            <h4 class="mb-0 fw-bold">
                <i class="bi bi-info-circle"></i>
                Informations du paiement
            </h4>

        </div>


        <div class="card-body p-4">


            <div class="row g-4">


                <!-- Investisseur -->
                <div class="col-md-6">

                    <label class="fw-bold fs-5 text-secondary">
                        Investisseur
                    </label>

                    <p class="fs-5 fw-semibold">

                        {{ $paiementBenefice->repartition->investissement->investisseur->nom ?? '—' }}

                        {{ $paiementBenefice->repartition->investissement->investisseur->prenom ?? '' }}

                    </p>

                </div>


                <!-- Période bénéfice -->
                <div class="col-md-6">

                    <label class="fw-bold fs-5 text-secondary">
                        Bénéfice (période)
                    </label>

                    <p class="fs-5 fw-semibold">

                        @if($paiementBenefice->repartition->benefice)

                            {{ \Carbon\Carbon::parse($paiementBenefice->repartition->benefice->date_debut)->format('d/m/Y') }}

                            →

                            {{ \Carbon\Carbon::parse($paiementBenefice->repartition->benefice->date_fin)->format('d/m/Y') }}

                        @else

                            —

                        @endif

                    </p>

                </div>



                <!-- Date paiement -->
                <div class="col-md-6">

                    <label class="fw-bold fs-5 text-secondary">
                        Date du paiement
                    </label>

                    <p class="fs-5">

                        {{ \Carbon\Carbon::parse($paiementBenefice->date_paiement)->format('d/m/Y') }}

                    </p>

                </div>



                <!-- Montant -->
                <div class="col-md-6">

                    <label class="fw-bold fs-5 text-secondary">
                        Montant
                    </label>

                    <p class="fs-4 fw-bold text-success">

                        {{ number_format($paiementBenefice->montant,0,',',' ') }}
                        F CFA

                    </p>

                </div>



                <!-- Mode paiement -->
                <div class="col-md-6">

                    <label class="fw-bold fs-5 text-secondary">
                        Mode de paiement
                    </label>

                    <p class="fs-5">

                        {{ $paiementBenefice->mode_paiement }}

                    </p>

                </div>



                <!-- Référence -->
                <div class="col-md-6">

                    <label class="fw-bold fs-5 text-secondary">
                        Référence
                    </label>

                    <p class="fs-5">

                        {{ $paiementBenefice->reference ?: '—' }}

                    </p>

                </div>



                <!-- Part répartition -->
                <div class="col-12">

                    <label class="fw-bold fs-5 text-secondary">
                        Part totale de la répartition
                    </label>

                    <p class="fs-5">

                        {{ number_format($paiementBenefice->repartition->montant ?? 0,0,',',' ') }}

                        F CFA

                    </p>

                </div>


            </div>


            <hr class="my-4">


            <!-- Observation -->
            <div class="mb-4">

                <label class="fw-bold fs-5 text-secondary">
                    Observation
                </label>

                <div class="bg-light rounded p-3 fs-5">

                    {{ $paiementBenefice->observation ?: '—' }}

                </div>

            </div>



            <!-- Dates système -->
            <div class="border-top pt-3 text-muted">

                <small>

                    <i class="bi bi-clock"></i>

                    Enregistré le
                    {{ $paiementBenefice->created_at->format('d/m/Y à H:i') }}


                    @if($paiementBenefice->updated_at &&
                        $paiementBenefice->updated_at->ne($paiementBenefice->created_at))

                        <br>

                        Modifié le
                        {{ $paiementBenefice->updated_at->format('d/m/Y à H:i') }}

                    @endif

                </small>

            </div>


        </div>

    </div>


</div>

@endsection