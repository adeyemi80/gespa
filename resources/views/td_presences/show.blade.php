@extends('tableau.neutre')

@section('content')
<button
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
    class="btn btn-secondary mb-3">
    ⬅️ Retour
</button>
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <span>
                <i class="bi bi-people"></i>
                Présences — {{ $seance->classe->niveau ?? '—' }}
                — {{ $seance->date->format('d/m/Y') }}
                @if($seance->libelle)
                    <span class="fw-normal fst-italic ms-1">({{ $seance->libelle }})</span>
                @endif
            </span>
            <span class="badge bg-light text-dark">
                {{ $seance->annee->libelle ?? $seance->annee->nom ?? '' }}
            </span>
        </div>

        {{-- Résumé --}}
        <div class="card-body border-bottom">
            <div class="row text-center g-2">
                <div class="col-4">
                    <div class="bg-light rounded p-2">
                        <div class="fs-4 fw-bold">{{ $presences->count() }}</div>
                        <div class="text-muted small">Total</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-success bg-opacity-10 rounded p-2">
                        <div class="fs-4 fw-bold text-success">{{ $nbPresents }}</div>
                        <div class="text-muted small">Présents</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-danger bg-opacity-10 rounded p-2">
                        <div class="fs-4 fw-bold text-danger">{{ $nbAbsents }}</div>
                        <div class="text-muted small">Absents</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tableau --}}
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Élève</th>
                        <th class="text-center">Présence</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($presences as $i => $p)
                        <tr class="{{ $p->present ? 'table-success' : 'table-danger' }}">
                            <td class="text-muted small">{{ $i + 1 }}</td>
                            <td>{{ $p->nom }} {{ $p->prenom }}</td>
                            <td class="text-center">
                                @if($p->present)
                                    <span class="badge bg-success">✓ Présent</span>
                                @else
                                    <span class="badge bg-danger">✗ Absent</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                                Aucune présence enregistrée pour cette séance.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer text-muted small">
            Taux de présence :
            <strong>
                {{ $presences->count() > 0
                    ? number_format($nbPresents / $presences->count() * 100, 1)
                    : 0 }} %
            </strong>
        </div>
    </div>
</div>
@endsection