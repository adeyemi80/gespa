@extends('tableau.neutre')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">

            <div class="card shadow-lg rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4 text-center">
                    <h4 class="mb-0">
                        <i class="bi bi-bar-chart-line-fill"></i> Rapport Global
                    </h4>
                    <small>Période : {{ $date_debut }} au {{ $date_fin }}</small>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Catégorie</th>
                                    <th>Total Recettes</th>
                                    <th>Total Dépenses</th>
                                    <th>Solde</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recap as $r)
                                    <tr>
                                        <td>{{ $r['categorie'] }}</td>
                                        <td>{{ number_format($r['recettes'], 0, ',', ' ') }} F</td>
                                        <td>{{ number_format($r['depenses'], 0, ',', ' ') }} F</td>
                                        <td>{{ number_format($r['solde'], 0, ',', ' ') }} F</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="alert alert-success text-center fw-bold">
                                Recettes Globales : {{ number_format($totalRecettes, 0, ',', ' ') }} F
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-danger text-center fw-bold">
                                Dépenses Globales : {{ number_format($totalDepenses, 0, ',', ' ') }} F
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-info text-center fw-bold">
                                Solde Global : {{ number_format($soldeGlobal, 0, ',', ' ') }} F
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('rapports.global.pdf') }}" class="btn btn-danger btn-lg">
                            <i class="bi bi-file-earmark-pdf"></i> Exporter en PDF
                        </a>
                    </div>
                </div>

                <div class="card-footer text-center text-muted">
                    Système de gestion financière
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
