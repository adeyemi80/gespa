@extends('tableau.neutre')

@section('title', 'Nouveau contrat de prestation')

@section('content')

<div class="container-fluid">

    <div class="row mb-4">
        <div class="col-md-12">

            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white">

                    <h4 class="mb-0">
                        <i class="fas fa-file-signature"></i>
                        Nouveau contrat de prestation de services
                    </h4>

                </div>

                <div class="card-body">

                    @if ($errors->any())

                        <div class="alert alert-danger">

                            <strong>
                                Veuillez corriger les erreurs suivantes :
                            </strong>

                            <ul class="mb-0 mt-2">

                                @foreach($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif

                    <form
                        action="{{ route('contrats.store') }}"
                        method="POST">

                        @csrf

                        <!-- ===================================== -->
                        <!-- ETABLISSEMENT -->
                        <!-- ===================================== -->

                        <div class="card mb-4 border-primary">

                            <div class="card-header bg-light">

                                <h5 class="mb-0">

                                    <i class="fas fa-school text-primary"></i>

                                    Informations de l'établissement

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label fw-bold">

                                            Nom de l'établissement

                                        </label>

                                        <input
                                            type="text"
                                            name="etablissement"
                                            class="form-control @error('etablissement') is-invalid @enderror"
                                            value="{{ old('etablissement') }}"
                                            required>

                                        @error('etablissement')

                                            <div class="invalid-feedback">

                                                {{ $message }}

                                            </div>

                                        @enderror

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label fw-bold">

                                            Représenté par

                                        </label>

                                        <input
                                            type="text"
                                            name="representant"
                                            class="form-control @error('representant') is-invalid @enderror"
                                            value="{{ old('representant') }}"
                                            required>

                                        @error('representant')

                                            <div class="invalid-feedback">

                                                {{ $message }}

                                            </div>

                                        @enderror

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-8 mb-3">

                                        <label class="form-label fw-bold">

                                            Adresse

                                        </label>

                                        <textarea
                                            name="adresse_etablissement"
                                            rows="3"
                                            class="form-control @error('adresse_etablissement') is-invalid @enderror">{{ old('adresse_etablissement') }}</textarea>

                                        @error('adresse_etablissement')

                                            <div class="invalid-feedback">

                                                {{ $message }}

                                            </div>

                                        @enderror

                                    </div>

                                    <div class="col-md-4 mb-3">

                                        <label class="form-label fw-bold">

                                            Fonction

                                        </label>

                                        <input
                                            type="text"
                                            name="fonction"
                                            class="form-control @error('fonction') is-invalid @enderror"
                                            value="{{ old('fonction') }}"
                                            placeholder="Directeur"
                                            required>

                                        @error('fonction')

                                            <div class="invalid-feedback">

                                                {{ $message }}

                                            </div>

                                        @enderror

                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- ===================================== -->
                        <!-- PRESTATAIRE -->
                        <!-- ===================================== -->

                        <div class="card mb-4 border-success">

                            <div class="card-header bg-light">

                                <h5 class="mb-0">

                                    <i class="fas fa-user-tie text-success"></i>

                                    Informations du prestataire

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label fw-bold">

                                            Nom et prénom

                                        </label>

                                        <input
                                            type="text"
                                            name="prestataire_nom"
                                            class="form-control @error('prestataire_nom') is-invalid @enderror"
                                            value="{{ old('prestataire_nom') }}"
                                            required>

                                        @error('prestataire_nom')

                                            <div class="invalid-feedback">

                                                {{ $message }}

                                            </div>

                                        @enderror

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label fw-bold">

                                            Téléphone

                                        </label>

                                        <input
                                            type="text"
                                            name="telephone"
                                            class="form-control @error('telephone') is-invalid @enderror"
                                            value="{{ old('telephone') }}"
                                            required>

                                        @error('telephone')

                                            <div class="invalid-feedback">

                                                {{ $message }}

                                            </div>

                                        @enderror

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-8 mb-3">

                                        <label class="form-label fw-bold">

                                            Adresse

                                        </label>

                                        <textarea
                                            name="prestataire_adresse"
                                            rows="3"
                                            class="form-control @error('prestataire_adresse') is-invalid @enderror">{{ old('prestataire_adresse') }}</textarea>

                                        @error('prestataire_adresse')

                                            <div class="invalid-feedback">

                                                {{ $message }}

                                            </div>

                                        @enderror

                                    </div>

                                    <div class="col-md-4 mb-3">

                                        <label class="form-label fw-bold">

                                            NPI / IFU

                                        </label>

                                        <input
                                            type="text"
                                            name="ifu"
                                            class="form-control @error('ifu') is-invalid @enderror"
                                            value="{{ old('ifu') }}">

                                        @error('ifu')

                                            <div class="invalid-feedback">

                                                {{ $message }}

                                            </div>

                                        @enderror

                                    </div>

                                </div>

                            </div>

                        </div>
                                                <!-- ===================================== -->
                        <!-- OBJET DU CONTRAT -->
                        <!-- ===================================== -->

                        <div class="card mb-4 border-info">

                            <div class="card-header bg-light">

                                <h5 class="mb-0">

                                    <i class="fas fa-file-contract text-info"></i>

                                    Objet du contrat

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="mb-3">

                                    <label class="form-label fw-bold">

                                        Objet de la prestation

                                    </label>

                                    <textarea
                                        name="objet_contrat"
                                        rows="5"
                                        class="form-control @error('objet_contrat') is-invalid @enderror"
                                        placeholder="Décrivez précisément la prestation attendue..."
                                        required>{{ old('objet_contrat') }}</textarea>

                                    @error('objet_contrat')

                                        <div class="invalid-feedback">

                                            {{ $message }}

                                        </div>

                                    @enderror

                                </div>

                            </div>

                        </div>

                        <!-- ===================================== -->
                        <!-- MONTANTS -->
                        <!-- ===================================== -->

                        <div class="card mb-4 border-warning">

                            <div class="card-header bg-light">

                                <h5 class="mb-0">

                                    <i class="fas fa-money-bill-wave text-warning"></i>

                                    Informations financières

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label fw-bold">

                                            Montant total (FCFA)

                                        </label>

                                        <input
                                            type="number"
                                            min="0"
                                            step="0.01"
                                            id="montant_total"
                                            name="montant_total"
                                            value="{{ old('montant_total') }}"
                                            class="form-control @error('montant_total') is-invalid @enderror"
                                            required>

                                        @error('montant_total')

                                            <div class="invalid-feedback">

                                                {{ $message }}

                                            </div>

                                        @enderror

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label fw-bold">

                                            Montant total (en lettres)

                                        </label>

                                        <textarea
                                            rows="2"
                                            name="montant_total_lettre"
                                            class="form-control @error('montant_total_lettre') is-invalid @enderror"
                                            required>{{ old('montant_total_lettre') }}</textarea>

                                        @error('montant_total_lettre')

                                            <div class="invalid-feedback">

                                                {{ $message }}

                                            </div>

                                        @enderror

                                    </div>

                                </div>

                                <hr>

                                <div class="row">

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label fw-bold">

                                            Acompte versé (FCFA)

                                        </label>

                                        <input
                                            type="number"
                                            min="0"
                                            step="0.01"
                                            id="acompte"
                                            name="acompte"
                                            value="{{ old('acompte') }}"
                                            class="form-control @error('acompte') is-invalid @enderror"
                                            required>

                                        @error('acompte')

                                            <div class="invalid-feedback">

                                                {{ $message }}

                                            </div>

                                        @enderror

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label fw-bold">

                                            Acompte (en lettres)

                                        </label>

                                        <textarea
                                            rows="2"
                                            name="acompte_lettre"
                                            class="form-control @error('acompte_lettre') is-invalid @enderror"
                                            required>{{ old('acompte_lettre') }}</textarea>

                                        @error('acompte_lettre')

                                            <div class="invalid-feedback">

                                                {{ $message }}

                                            </div>

                                        @enderror

                                    </div>

                                </div>

                                <hr>

                                <div class="row">

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label fw-bold text-danger">

                                            Reliquat à payer (FCFA)

                                        </label>

                                        <input
                                            type="number"
                                            id="reliquat"
                                            name="reliquat"
                                            value="{{ old('reliquat') }}"
                                            class="form-control bg-light fw-bold"
                                            readonly>

                                        <small class="text-muted">

                                            Calcul automatique :
                                            Montant total − Acompte

                                        </small>

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label fw-bold">

                                            Reliquat (en lettres)

                                        </label>

                                        <textarea
                                            rows="2"
                                            name="reliquat_lettre"
                                            class="form-control @error('reliquat_lettre') is-invalid @enderror">{{ old('reliquat_lettre') }}</textarea>

                                        @error('reliquat_lettre')

                                            <div class="invalid-feedback">

                                                {{ $message }}

                                            </div>

                                        @enderror

                                    </div>

                                </div>

                            </div>

                        </div>
                                                <!-- ===================================== -->
                        <!-- LIVRAISON -->
                        <!-- ===================================== -->

                        <div class="card mb-4 border-secondary">

                            <div class="card-header bg-light">

                                <h5 class="mb-0">

                                    <i class="fas fa-truck text-secondary"></i>

                                    Livraison

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label fw-bold">

                                            Date limite de livraison

                                        </label>

                                        <input
                                            type="date"
                                            name="date_limite_livraison"
                                            value="{{ old('date_limite_livraison') }}"
                                            class="form-control @error('date_limite_livraison') is-invalid @enderror"
                                            required>

                                        @error('date_limite_livraison')

                                            <div class="invalid-feedback">

                                                {{ $message }}

                                            </div>

                                        @enderror

                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- ===================================== -->
                        <!-- SIGNATURE -->
                        <!-- ===================================== -->

                        <div class="card mb-4 border-dark">

                            <div class="card-header bg-light">

                                <h5 class="mb-0">

                                    <i class="fas fa-signature"></i>

                                    Signature

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label fw-bold">

                                            Lieu de signature

                                        </label>

                                        <input
                                            type="text"
                                            name="lieu_signature"
                                            value="{{ old('lieu_signature') }}"
                                            class="form-control @error('lieu_signature') is-invalid @enderror"
                                            placeholder="Ex : Cotonou"
                                            required>

                                        @error('lieu_signature')

                                            <div class="invalid-feedback">

                                                {{ $message }}

                                            </div>

                                        @enderror

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label fw-bold">

                                            Date de signature

                                        </label>

                                        <input
                                            type="date"
                                            name="date_signature"
                                            value="{{ old('date_signature', date('Y-m-d')) }}"
                                            class="form-control @error('date_signature') is-invalid @enderror"
                                            required>

                                        @error('date_signature')

                                            <div class="invalid-feedback">

                                                {{ $message }}

                                            </div>

                                        @enderror

                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- ===================================== -->
                        <!-- MENTION MANUSCRITE -->
                        <!-- ===================================== -->

                        <div class="card mb-4 border-success">

                            <div class="card-header bg-light">

                                <h5 class="mb-0">

                                    <i class="fas fa-pen-fancy text-success"></i>

                                    Mention manuscrite

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="mb-3">

                                    <label class="form-label fw-bold">

                                        Mention manuscrite figurant sur le contrat

                                    </label>

                                    <textarea
                                        name="mention_manuelle"
                                        rows="4"
                                        class="form-control @error('mention_manuelle') is-invalid @enderror"
                                        placeholder="Exemple : Lu et approuvé – Bon pour réception de l'acompte et engagement de prestation.">{{ old('mention_manuelle', "Lu et approuvé – Bon pour réception de l'acompte et engagement de prestation.") }}</textarea>

                                    @error('mention_manuelle')

                                        <div class="invalid-feedback">

                                            {{ $message }}

                                        </div>

                                    @enderror

                                </div>

                            </div>

                        </div>

                        <!-- ===================================== -->
                        <!-- OBSERVATIONS -->
                        <!-- ===================================== -->

                        <div class="card mb-4 border-info">

                            <div class="card-header bg-light">

                                <h5 class="mb-0">

                                    <i class="fas fa-comment-alt text-info"></i>

                                    Observations (facultatif)

                                </h5>

                            </div>

                            <div class="card-body">

                                <textarea
                                    name="observations"
                                    rows="4"
                                    class="form-control"
                                    placeholder="Observations internes...">{{ old('observations') }}</textarea>

                            </div>

                        </div>
                                                <!-- ===================================== -->
                        <!-- BOUTONS -->
                        <!-- ===================================== -->

                        <div class="card shadow-sm">

                            <div class="card-body text-center">

                                <button
                                    type="submit"
                                    class="btn btn-success btn-lg">

                                    <i class="fas fa-save"></i>

                                    Enregistrer le contrat

                                </button>

                                <a
                                    href="{{ route('contrats.index') }}"
                                    class="btn btn-secondary btn-lg">

                                    <i class="fas fa-arrow-left"></i>

                                    Retour

                                </a>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    let montant = document.getElementById('montant_total');

    let acompte = document.getElementById('acompte');

    let reliquat = document.getElementById('reliquat');

    function calculerReliquat()
    {
        let total = parseFloat(montant.value);

        let avance = parseFloat(acompte.value);

        if (isNaN(total))
            total = 0;

        if (isNaN(avance))
            avance = 0;

        let reste = total - avance;

        if (reste < 0)
            reste = 0;

        reliquat.value = reste.toFixed(2);
    }

    montant.addEventListener('keyup', calculerReliquat);
    acompte.addEventListener('keyup', calculerReliquat);

    montant.addEventListener('change', calculerReliquat);
    acompte.addEventListener('change', calculerReliquat);

    calculerReliquat();

});

</script>

@endpush