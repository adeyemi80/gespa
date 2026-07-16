@extends('tableau.neutre')

@section('content')

<div class="container py-3">
    <!-- Bouton Retour -->
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

                <div class="card-header bg-primary text-white py-2">

                    <h5 class="mb-0 fw-bold">

                        <i class="bi bi-person-plus-fill me-2"></i>

                        Nouvel investisseur

                    </h5>

                </div>





                <div class="card-body p-3">


                    <form method="POST" action="{{ route('investisseurs.store') }}">

                        @csrf



                        <div class="row mb-3">


                            <div class="col-md-6">


                                <label class="form-label fw-semibold">

                                    Nom

                                </label>


                                <input type="text"
                                       name="nom"
                                       value="{{ old('nom') }}"
                                       class="form-control @error('nom') is-invalid @enderror">


                                @error('nom')

                                    <div class="invalid-feedback">

                                        {{ $message }}

                                    </div>

                                @enderror


                            </div>





                            <div class="col-md-6">


                                <label class="form-label fw-semibold">

                                    Prénom

                                </label>


                                <input type="text"
                                       name="prenom"
                                       value="{{ old('prenom') }}"
                                       class="form-control @error('prenom') is-invalid @enderror">


                                @error('prenom')

                                    <div class="invalid-feedback">

                                        {{ $message }}

                                    </div>

                                @enderror


                            </div>


                        </div>





                        <div class="row mb-3">


                            <div class="col-md-6">


                                <label class="form-label fw-semibold">

                                    Téléphone

                                </label>


                                <input type="text"
                                       name="telephone"
                                       value="{{ old('telephone') }}"
                                       class="form-control @error('telephone') is-invalid @enderror">


                                @error('telephone')

                                    <div class="invalid-feedback">

                                        {{ $message }}

                                    </div>

                                @enderror


                            </div>





                            <div class="col-md-6">


                                <label class="form-label fw-semibold">

                                    Email

                                </label>


                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       class="form-control @error('email') is-invalid @enderror">


                                @error('email')

                                    <div class="invalid-feedback">

                                        {{ $message }}

                                    </div>

                                @enderror


                            </div>


                        </div>





                        <div class="mb-3">


                            <label class="form-label fw-semibold">

                                Profession

                            </label>


                            <input type="text"
                                   name="profession"
                                   value="{{ old('profession') }}"
                                   class="form-control @error('profession') is-invalid @enderror">


                            @error('profession')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror


                        </div>





                        <div class="mb-3">


                            <label class="form-label fw-semibold">

                                Adresse

                            </label>


                            <textarea name="adresse"
                                      rows="3"
                                      class="form-control @error('adresse') is-invalid @enderror">{{ old('adresse') }}</textarea>


                            @error('adresse')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror


                        </div>





                        <hr>



                        <div class="d-flex justify-content-end gap-2">


                            <a href="{{ route('investisseurs.index') }}"
                               class="btn btn-outline-secondary">


                                <i class="bi bi-x-circle me-1"></i>

                                Annuler


                            </a>





                            <button type="submit"
                                    class="btn btn-primary shadow-sm">


                                <i class="bi bi-save-fill me-1"></i>

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
