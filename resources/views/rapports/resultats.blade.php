@extends('tableau.neutre')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-success text-white rounded-top-4 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-bar-chart-line"></i> Rapport - {{ $categorie->nom }}
                    </h4>
                    <span class="badge bg-light text-dark">
                        Période : {{ $date_debut ?? session('rapport_date_debut') }} au {{ $date_fin ?? session('rapport_date_fin') }}
                    </span>
                </div>

                <div class="card-body p-4">

                    {{-- Tableau --}}
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th class="text-end">Montant (F CFA)</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($transactions as $t)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($t->date_transaction)->format('d/m/Y') }}</td>
                                    <td>{{ $t->categorie->description }}</td>
                                    <td class="text-end fw-bold text-{{ $t->montant > 0 ? 'success' : 'danger' }}">
                                        {{ number_format($t->montant, 0, ',', ' ') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        <i class="bi bi-info-circle"></i> Aucune transaction trouvée
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Total --}}
                    <div class="alert alert-primary d-flex justify-content-between align-items-center mt-4">
                        <h5 class="mb-0">
                            <i class="bi bi-cash-coin"></i> Total :
                        </h5>
                        <span class="fw-bold fs-5">{{ number_format($somme, 0, ',', ' ') }} F</span>
                    </div>

                    {{-- Bouton PDF --}}
                    <div class="text-end mt-3">
                        <a href="{{ route('rapports.pdf') }}" class="btn btn-danger px-4">
                            <i class="bi bi-file-earmark-pdf"></i> Exporter en PDF
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
