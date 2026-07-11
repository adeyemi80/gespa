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
                        Modifier l'investisseur
                    </h3>
                </div>

                <div class="card-body p-4">

                    <form method="POST" action="{{ route('investisseurs.update', $investisseur) }}">
                        @csrf
                        @method('PUT')

                        <!-- Nom / Prénom -->
                        <div class="row mb-4">

                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-5">
                                    Nom
                                </label>

                                <input type="text"
                                       name="nom"
                                       value="{{ old('nom', $investisseur->nom) }}"
                                       class="form-control form-control-lg @error('nom') is-invalid @enderror">

                                @error('nom')
                                    <div class="invalid-feedback fs-6">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-5">
                                    Prénom
                                </label>

                                <input type="text"
                                       name="prenom"
                                       value="{{ old('prenom', $investisseur->prenom) }}"
                                       class="form-control form-control-lg @error('prenom') is-invalid @enderror">

                                @error('prenom')
                                    <div class="invalid-feedback fs-6">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>

                        <!-- Téléphone / Email -->
                        <div class="row mb-4">

                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-5">
                                    Téléphone
                                </label>

                                <input type="text"
                                       name="telephone"
                                       value="{{ old('telephone', $investisseur->telephone) }}"
                                       class="form-control form-control-lg @error('telephone') is-invalid @enderror">

                                @error('telephone')
                                    <div class="invalid-feedback fs-6">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-5">
                                    Email
                                </label>

                                <input type="email"
                                       name="email"
                                       value="{{ old('email', $investisseur->email) }}"
                                       class="form-control form-control-lg @error('email') is-invalid @enderror">

                                @error('email')
                                    <div class="invalid-feedback fs-6">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>

                        <!-- Profession -->
                        <div class="mb-4">
                            <label class="form-label fw-bold fs-5">
                                Profession
                            </label>

                            <input type="text"
                                   name="profession"
                                   value="{{ old('profession', $investisseur->profession) }}"
                                   class="form-control form-control-lg @error('profession') is-invalid @enderror">

                            @error('profession')
                                <div class="invalid-feedback fs-6">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Adresse -->
                        <div class="mb-4">
                            <label class="form-label fw-bold fs-5">
                                Adresse
                            </label>

                            <textarea name="adresse"
                                      rows="4"
                                      class="form-control form-control-lg @error('adresse') is-invalid @enderror">{{ old('adresse', $investisseur->adresse) }}</textarea>

                            @error('adresse')
                                <div class="invalid-feedback fs-6">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <hr>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-end gap-3 mt-4">

                            <a href="{{ route('investisseurs.index') }}"
                               class="btn btn-outline-secondary btn-lg px-4">
                                <i class="bi bi-x-circle"></i>
                                Annuler
                            </a>

                            <button type="submit"
                                    class="btn btn-warning btn-lg px-4 text-dark shadow-sm">
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