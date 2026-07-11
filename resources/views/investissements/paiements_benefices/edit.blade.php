@extends('tableau.neutre')

@section('content')

<div class="container py-4">

    <!-- Bouton Retour -->
    <div class="mb-4">
        <button
            onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
            class="btn btn-secondary btn-lg shadow-sm">
            <i class="bi bi-arrow-left-circle"></i> Retour
        </button>
    </div>

    <div class="row justify-content-center">

        <div class="col-lg-9">

            <div class="card shadow border-0">

                <!-- En-tête -->
                <div class="card-header bg-warning text-dark py-3">
                    <h3 class="mb-0 fw-bold">
                        <i class="bi bi-pencil-square me-2"></i>
                        Modifier le paiement de bénéfice
                    </h3>
                </div>

                <div class="card-body p-4">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('paiements-benefices.update', $paiementBenefice) }}">
                        @csrf
                        @method('PUT')

                        <!-- Répartition -->
                        <div class="mb-4">

                            <label class="form-label fw-bold fs-5">
                                Répartition
                            </label>

                            <select name="repartition_id"
                                    class="form-select form-select-lg @error('repartition_id') is-invalid @enderror">

                                <option value="">Sélectionner une répartition</option>

                                @foreach ($repartitions as $repartition)

                                    <option value="{{ $repartition->id }}"
                                        {{ (string) old('repartition_id', $paiementBenefice->repartition_id) === (string) $repartition->id ? 'selected' : '' }}>

                                        {{ $repartition->investissement->investisseur->nom ?? '' }}
                                        {{ $repartition->investissement->investisseur->prenom ?? '' }}

                                        —

                                        @if ($repartition->benefice)
                                            Bénéfice
                                            {{ \Carbon\Carbon::parse($repartition->benefice->date_debut)->format('d/m/Y') }}
                                            →
                                            {{ \Carbon\Carbon::parse($repartition->benefice->date_fin)->format('d/m/Y') }}
                                        @endif

                                        ({{ number_format($repartition->montant,0,',',' ') }} F CFA de part)

                                    </option>

                                @endforeach

                            </select>

                            @error('repartition_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Le montant du paiement ne peut pas dépasser le bénéfice restant à payer
                                (ce paiement est exclu du calcul).
                            </div>

                        </div>

                        <!-- Date / Montant -->
                        <div class="row mb-4">

                            <div class="col-md-6">

                                <label class="form-label fw-bold fs-5">
                                    Date du paiement
                                </label>

                                <input type="date"
                                       name="date_paiement"
                                       value="{{ old('date_paiement', optional($paiementBenefice->date_paiement)->format('Y-m-d') ?? $paiementBenefice->date_paiement) }}"
                                       class="form-control form-control-lg @error('date_paiement') is-invalid @enderror">

                                @error('date_paiement')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-6">

                                <label class="form-label fw-bold fs-5">
                                    Montant (F CFA)
                                </label>

                                <input type="number"
                                       step="0.01"
                                       min="1"
                                       name="montant"
                                       value="{{ old('montant', $paiementBenefice->montant) }}"
                                       class="form-control form-control-lg @error('montant') is-invalid @enderror">

                                @error('montant')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                        <!-- Mode / Référence -->
                        <div class="row mb-4">

                            <div class="col-md-6">

                                <label class="form-label fw-bold fs-5">
                                    Mode de paiement
                                </label>

                                <input type="text"
                                       name="mode_paiement"
                                       value="{{ old('mode_paiement', $paiementBenefice->mode_paiement) }}"
                                       placeholder="Espèces, Mobile Money, Virement..."
                                       class="form-control form-control-lg @error('mode_paiement') is-invalid @enderror">

                                @error('mode_paiement')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-6">

                                <label class="form-label fw-bold fs-5">
                                    Référence
                                </label>

                                <input type="text"
                                       name="reference"
                                       value="{{ old('reference', $paiementBenefice->reference) }}"
                                       class="form-control form-control-lg @error('reference') is-invalid @enderror">

                                @error('reference')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                        <!-- Observation -->
                        <div class="mb-4">

                            <label class="form-label fw-bold fs-5">
                                Observation
                            </label>

                            <textarea name="observation"
                                      rows="4"
                                      class="form-control form-control-lg @error('observation') is-invalid @enderror">{{ old('observation', $paiementBenefice->observation) }}</textarea>

                            @error('observation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <hr>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-end gap-3">

                            <a href="{{ route('paiements-benefices.index') }}"
                               class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-x-circle"></i>
                                Annuler
                            </a>

                            <button type="submit"
                                    class="btn btn-warning btn-lg text-dark">
                                <i class="bi bi-check-circle-fill"></i>
                                Mettre à jour
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection