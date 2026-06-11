@extends('classes.layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
                    <h5 class="mb-0">Informations sur l'épreuve : {{ $epreuve->nature }}</h5>
                    <a href="{{ route('epreuves.index') }}" class="btn btn-primary btn-sm">&larr; Retour</a>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-md-4 fw-bold text-end">Trimestre :</label>
                        <div class="col-md-8">{{ $epreuve->trimestre }}</div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 fw-bold text-end">Matière :</label>
                        <div class="col-md-8">{{ $epreuve->matiere }}</div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 fw-bold text-end">Nature :</label>
                        <div class="col-md-8">{{ $epreuve->nature }}</div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 fw-bold text-end">Fichier :</label>
                        <div class="col-md-8">
                            {{ $epreuve->file }}
                            <a href="{{ asset('epreuves/' . $epreuve->file) }}" target="_blank" class="btn btn-link btn-sm">
                                Télécharger
                            </a>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 fw-bold text-end">Classe :</label>
                        <div class="col-md-8">{{ $epreuve->classe->nom ?? 'N/A' }}</div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 fw-bold text-end">Année :</label>
                        <div class="col-md-8">{{ $epreuve->classe->annee->ann ?? 'N/A' }}</div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
