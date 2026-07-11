```blade
@extends('tableau.neutre')

@section('content')

<div class="container py-3">

    <!-- Retour -->
    <div class="mb-3">

        <button
            onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
            class="btn btn-secondary shadow-sm">

            <i class="bi bi-arrow-left-circle me-1"></i>
            Retour

        </button>

    </div>



    <div class="row justify-content-center">

        <div class="col-lg-7">


            <div class="card shadow-sm border-0">


                <!-- Header -->
                <div class="card-header bg-danger text-white py-2">


                    <div class="d-flex justify-content-between align-items-center">


                        <h5 class="mb-0 fw-bold">

                            <i class="bi bi-cash-coin me-1"></i>

                            Nouveau retrait de capital

                        </h5>



                        <a href="{{ route('retraits-capital.index') }}"
                           class="btn btn-light btn-sm">

                            <i class="bi bi-arrow-left"></i>

                            Retour

                        </a>


                    </div>


                </div>





                <div class="card-body p-3">


                    @if ($errors->any())

                        <div class="alert alert-danger py-2">

                            <strong>
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                Erreurs :
                            </strong>


                            <ul class="mb-0 small">

                                @foreach($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif





                    <form action="{{ route('retraits-capital.store') }}"
                          method="POST">

                        @csrf



                        <!-- Investissement -->

                        <div class="mb-3">


                            <label class="form-label fw-semibold">

                                Investissement
                                <span class="text-danger">*</span>

                            </label>


                            <select name="investissement_id"
                                    class="form-select @error('investissement_id') is-invalid @enderror">


                                <option value="">
                                    -- Sélectionner un investissement --
                                </option>


                                @foreach($investissements as $investissement)

                                    <option value="{{ $investissement->id }}"
                                        {{ old('investissement_id') == $investissement->id ? 'selected' : '' }}>


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



                            <div class="alert alert-warning mt-2 mb-0 small">

                                <i class="bi bi-info-circle me-1"></i>

                                Le retrait ne peut pas dépasser le capital disponible.

                            </div>


                        </div>





                        <div class="row">


                            <div class="col-md-6 mb-3">


                                <label class="form-label fw-semibold">

                                    Date du retrait
                                    <span class="text-danger">*</span>

                                </label>


                                <input type="date"
                                       name="date_retrait"
                                       value="{{ old('date_retrait', date('Y-m-d')) }}"
                                       class="form-control @error('date_retrait') is-invalid @enderror">


                            </div>





                            <div class="col-md-6 mb-3">


                                <label class="form-label fw-semibold">

                                    Montant (F CFA)
                                    <span class="text-danger">*</span>

                                </label>


                                <div class="input-group">


                                    <span class="input-group-text">

                                        <i class="bi bi-currency-exchange"></i>

                                    </span>


                                    <input type="number"
                                           name="montant"
                                           min="1"
                                           step="0.01"
                                           value="{{ old('montant') }}"
                                           class="form-control">


                                </div>


                            </div>


                        </div>





                        <div class="row">


                            <div class="col-md-6 mb-3">


                                <label class="form-label fw-semibold">

                                    Mode de retrait

                                </label>


                                <select name="mode_retrait"
                                        class="form-select">


                                    <option value="">
                                        -- Choisir --
                                    </option>


                                    @foreach(['Espèces','Mobile Money','Virement bancaire','Chèque'] as $mode)

                                        <option value="{{ $mode }}"
                                            {{ old('mode_retrait') == $mode ? 'selected' : '' }}>

                                            {{ $mode }}

                                        </option>

                                    @endforeach


                                </select>


                            </div>





                            <div class="col-md-6 mb-3">


                                <label class="form-label fw-semibold">

                                    Référence

                                </label>


                                <input type="text"
                                       name="reference"
                                       value="{{ old('reference') }}"
                                       class="form-control"
                                       placeholder="Numéro transaction">


                            </div>


                        </div>





                        <div class="mb-3">


                            <label class="form-label fw-semibold">

                                Motif

                            </label>


                            <input type="text"
                                   name="motif"
                                   value="{{ old('motif') }}"
                                   class="form-control">


                        </div>





                        <div class="mb-3">


                            <label class="form-label fw-semibold">

                                Observation

                            </label>


                            <textarea name="observation"
                                      rows="3"
                                      class="form-control">{{ old('observation') }}</textarea>


                        </div>





                        <hr>



                        <div class="d-flex justify-content-end gap-2">


                            <a href="{{ route('retraits-capital.index') }}"
                               class="btn btn-secondary">


                                <i class="bi bi-x-circle me-1"></i>

                                Annuler


                            </a>




                            <button type="submit"
                                    class="btn btn-danger">


                                <i class="bi bi-save me-1"></i>

                                Enregistrer


                            </button>


                        </div>



                    </form>


                </div>


            </div>


        </div>


    </div>


</div>


@endsection
```
