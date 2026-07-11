@extends('tableau.neutre')

@section('content')

<div class="container py-4">

    <!-- Retour -->
    <div class="mb-4">
        <button
            onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
            class="btn btn-secondary btn-lg shadow-sm">

            <i class="bi bi-arrow-left-circle me-2"></i>
            Retour

        </button>
    </div>


    <div class="row justify-content-center">

        <div class="col-lg-9">


            <div class="card shadow border-0">


                <!-- En-tête -->
                <div class="card-header bg-warning text-dark py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <h3 class="mb-0 fw-bold">

                            <i class="bi bi-pencil-square me-2"></i>

                            Modifier le retrait de capital

                        </h3>


                        <a href="{{ route('retraits-capital.index') }}"
                           class="btn btn-light btn-lg">

                            <i class="bi bi-arrow-left"></i>
                            Retour

                        </a>

                    </div>

                </div>



                <div class="card-body p-4">


                    @if ($errors->any())

                        <div class="alert alert-danger fs-5">

                            <h5 class="fw-bold">

                                <i class="bi bi-exclamation-triangle-fill me-2"></i>

                                Veuillez corriger les erreurs suivantes :

                            </h5>


                            <ul class="mb-0">

                                @foreach($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>


                        </div>

                    @endif





                    <form method="POST"
                          action="{{ route('retraits-capital.update', $retraitCapital) }}">

                        @csrf
                        @method('PUT')



                        <!-- Investissement -->

                        <div class="mb-4">


                            <label class="form-label fw-bold fs-5">

                                Investissement

                                <span class="text-danger">*</span>

                            </label>



                            <select name="investissement_id"
                                    class="form-select form-select-lg @error('investissement_id') is-invalid @enderror">


                                <option value="">
                                    -- Sélectionner un investissement --
                                </option>


                                @foreach($investissements as $investissement)


                                    <option value="{{ $investissement->id }}"

                                        {{ old('investissement_id', $retraitCapital->investissement_id) == $investissement->id ? 'selected' : '' }}>


                                        {{ $investissement->investisseur->nom ?? '' }}

                                        {{ $investissement->investisseur->prenom ?? '' }}

                                        |

                                        Investissement #{{ $investissement->id }}


                                    </option>


                                @endforeach


                            </select>



                            @error('investissement_id')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror




                            <div class="alert alert-warning mt-3 fs-6">

                                <i class="bi bi-info-circle me-2"></i>

                                Le montant du retrait ne peut pas dépasser le
                                <strong>capital disponible</strong>
                                sur l'investissement sélectionné
                                (ce retrait est exclu du calcul).

                            </div>


                        </div>





                        <div class="row">


                            <!-- Date -->

                            <div class="col-md-6 mb-4">


                                <label class="form-label fw-bold fs-5">

                                    Date du retrait

                                    <span class="text-danger">*</span>

                                </label>



                                <input type="date"
                                       name="date_retrait"
                                       value="{{ old('date_retrait', optional($retraitCapital->date_retrait)->format('Y-m-d') ?? $retraitCapital->date_retrait) }}"
                                       class="form-control form-control-lg @error('date_retrait') is-invalid @enderror">



                                @error('date_retrait')

                                    <div class="invalid-feedback">

                                        {{ $message }}

                                    </div>

                                @enderror


                            </div>





                            <!-- Montant -->

                            <div class="col-md-6 mb-4">


                                <label class="form-label fw-bold fs-5">

                                    Montant (F CFA)

                                    <span class="text-danger">*</span>

                                </label>



                                <div class="input-group input-group-lg">


                                    <span class="input-group-text">

                                        <i class="bi bi-cash-coin"></i>

                                    </span>



                                    <input type="number"
                                           name="montant"
                                           step="0.01"
                                           min="1"
                                           value="{{ old('montant', $retraitCapital->montant) }}"
                                           class="form-control @error('montant') is-invalid @enderror">


                                </div>


                                @error('montant')

                                    <div class="text-danger mt-1">

                                        {{ $message }}

                                    </div>

                                @enderror


                            </div>


                        </div>





                        <div class="row">


                            <!-- Mode -->

                            <div class="col-md-6 mb-4">


                                <label class="form-label fw-bold fs-5">

                                    Mode de retrait

                                </label>



                                <select name="mode_retrait"
                                        class="form-select form-select-lg">


                                    <option value="">
                                        -- Choisir --
                                    </option>


                                    @foreach(['Espèces','Mobile Money','Virement bancaire','Chèque'] as $mode)

                                        <option value="{{ $mode }}"

                                            {{ old('mode_retrait',$retraitCapital->mode_retrait)==$mode ? 'selected':'' }}>

                                            {{ $mode }}

                                        </option>


                                    @endforeach


                                </select>


                            </div>





                            <!-- Référence -->

                            <div class="col-md-6 mb-4">


                                <label class="form-label fw-bold fs-5">

                                    Référence

                                </label>



                                <input type="text"
                                       name="reference"
                                       value="{{ old('reference',$retraitCapital->reference) }}"
                                       placeholder="Numéro de transaction"
                                       class="form-control form-control-lg">


                            </div>


                        </div>





                        <!-- Motif -->

                        <div class="mb-4">


                            <label class="form-label fw-bold fs-5">

                                Motif

                            </label>



                            <input type="text"
                                   name="motif"
                                   value="{{ old('motif',$retraitCapital->motif) }}"
                                   placeholder="Motif du retrait"
                                   class="form-control form-control-lg">


                        </div>





                        <!-- Observation -->

                        <div class="mb-4">


                            <label class="form-label fw-bold fs-5">

                                Observation

                            </label>



                            <textarea name="observation"
                                      rows="4"
                                      class="form-control form-control-lg"
                                      placeholder="Ajouter une remarque éventuelle...">{{ old('observation',$retraitCapital->observation) }}</textarea>


                        </div>




                        <hr>




                        <!-- Boutons -->

                        <div class="d-flex justify-content-end gap-3">


                            <a href="{{ route('retraits-capital.index') }}"
                               class="btn btn-secondary btn-lg">


                                <i class="bi bi-x-circle me-2"></i>

                                Annuler


                            </a>




                            <button type="submit"
                                    class="btn btn-warning btn-lg">


                                <i class="bi bi-save me-2"></i>

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