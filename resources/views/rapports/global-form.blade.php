@extends('tableau.neutre')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4 text-center">
                    <h4 class="mb-0">
                        <i class="bi bi-bar-chart-line-fill"></i> Rapport Global (toutes catégories)
                    </h4>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('rapports.global.resultat') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Date début</label>
                            <input type="date" name="date_debut" class="form-control form-control-lg" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Date fin</label>
                            <input type="date" name="date_fin" class="form-control form-control-lg" required>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-eye-fill"></i> Afficher le rapport
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-footer text-center text-muted">
                    Système de gestion financière
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
