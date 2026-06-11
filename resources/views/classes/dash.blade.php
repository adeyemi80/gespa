@extends('classes.layout')

@section('content')
<div class="container-fluid py-4">
    <div class="row">

        <!-- Dashboard Cards -->
        <div class="col-12 mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h6 class="card-title text-uppercase">Élèves</h6>
                            <p class="card-text fs-4 fw-bold">{{ $eleves->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h6 class="card-title text-uppercase">Classes</h6>
                            <p class="card-text fs-4 fw-bold">{{ $classes->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h6 class="card-title text-uppercase">Paiements</h6>
                            <p class="card-text fs-4 fw-bold">{{ $paiementsEffectues }}%</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h6 class="card-title text-uppercase">Budgets</h6>
                            <p class="card-text fs-4 fw-bold">{{ number_format($budgets->sum('montant_alloue'), 2, ',', ' ') }} FCFA</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des Élèves -->
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient text-white rounded-top-4" style="background: linear-gradient(90deg, #0d6efd, #20c997);">
                    <h4 class="mb-0"><i class="bi bi-people me-2"></i> Liste des Élèves</h4>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Matricule</th>
                                    <th>Nom & Prénom</th>
                                    <th>Classe</th>
                                    <th>Sexe</th>
                                    <th>Date de naissance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($eleves as $eleve)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $eleve->matricule }}</td>
                                        <td>{{ $eleve->nom }} {{ $eleve->prenom }}</td>
                                        <td>{{ $eleve->classe->nom ?? 'N/A' }}</td>
                                        <td>{{ $eleve->sexe }}</td>
                                        <td>{{ $eleve->date_naissance ? $eleve->date_naissance->format('d/m/Y') : '-' }}</td>
                                        <td class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('eleves.show', $eleve->id) }}" class="btn btn-sm btn-info" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('eleves.edit', $eleve->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('eleves.destroy', $eleve->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet élève ?')" title="Supprimer">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                            Aucun élève trouvé
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
