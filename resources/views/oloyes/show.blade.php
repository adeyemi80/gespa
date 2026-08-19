@extends('tableau.neutre')

@section('content')

<div class="container py-4">

    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold text-info">
                👁️ Détails de la dépense
            </h2>

            <p class="text-muted mb-0">
                Consultation des informations de la dépense
            </p>
        </div>

        <a href="{{ route('oloyes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

    </div>

    <div class="card shadow">

        <div class="card-header bg-info text-white">

            <h5 class="mb-0">

                Informations de la dépense

            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="fw-bold text-secondary">
                        Date
                    </label>

                    <div class="form-control bg-light">

                        {{ $oloye->date->format('d/m/Y') }}

                    </div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold text-secondary">
                        Catégorie
                    </label>

                    <div class="form-control bg-light">

                        {{ $oloye->categorie ?: 'Non renseignée' }}

                    </div>

                </div>

                <div class="col-md-12 mb-3">

                    <label class="fw-bold text-secondary">
                        Libellé
                    </label>

                    <div class="form-control bg-light">

                        {{ $oloye->libelle }}

                    </div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold text-secondary">
                        Bénéficiaire
                    </label>

                    <div class="form-control bg-light">

                        {{ $oloye->beneficiaire ?: 'Non renseigné' }}

                    </div>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="fw-bold text-secondary">
                        Montant
                    </label>

                    <div class="form-control bg-light text-danger fw-bold fs-5">

                        {{ number_format($oloye->montant,0,',',' ') }}

                        FCFA

                    </div>

                </div>

                <div class="col-md-12 mb-3">

                    <label class="fw-bold text-secondary">
                        Observation
                    </label>

                    <div
                        class="form-control bg-light"
                        style="min-height:120px;">

                        {!! nl2br(e($oloye->observation ?: 'Aucune observation')) !!}

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="fw-bold text-secondary">
                        Créé le
                    </label>

                    <div class="form-control bg-light">

                        {{ $oloye->created_at->format('d/m/Y à H:i') }}

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="fw-bold text-secondary">
                        Dernière modification
                    </label>

                    <div class="form-control bg-light">

                        {{ $oloye->updated_at->format('d/m/Y à H:i') }}

                    </div>

                </div>

            </div>

        </div>

        <div class="card-footer text-end">

            <a
                href="{{ route('oloyes.edit',$oloye) }}"
                class="btn btn-warning">

                <i class="bi bi-pencil-square"></i>

                Modifier

            </a>

            <form
                action="{{ route('oloyes.destroy',$oloye) }}"
                method="POST"
                class="d-inline">

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="btn btn-danger"
                    onclick="return confirm('Voulez-vous vraiment supprimer cette dépense ?')">

                    <i class="bi bi-trash"></i>

                    Supprimer

                </button>

            </form>

        </div>

    </div>

</div>

@endsection