@extends('tableau.neutre')

@section('title', 'Établissements')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 fw-bold text-dark">
                <i class="bi bi-building me-2"></i>Gestion des Établissements
            </h1>
            <small class="text-muted">Gestion centralisée de vos établissements scolaires</small>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('etablissements.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Nouvel Établissement
            </a>
        </div>
    </div>

    <!-- Messages de succès -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tableau des établissements -->
    @if ($etablissements->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-bold text-dark">Établissement</th>
                                <th class="fw-bold text-dark">Représentant</th>
                                <th class="fw-bold text-dark">Adresse</th>
                                <th class="fw-bold text-dark">Téléphone</th>
                                <th class="fw-bold text-dark">Email</th>
                                <th class="fw-bold text-dark text-center" style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($etablissements as $etablissement)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $etablissement->nom }}</div>
                                        <small class="text-muted">
                                            @if ($etablissement->ifu)
                                                IFU: {{ $etablissement->ifu }}
                                            @elseif ($etablissement->npi)
                                                NPI: {{ $etablissement->npi }}
                                            @else
                                                <span class="text-danger">Pas d'identifiant</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        {{ $etablissement->representant }}<br>
                                        <small class="text-muted">{{ $etablissement->fonction }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $etablissement->adresse ?? '-' }}</small>
                                    </td>
                                    <td>
                                        {{ $etablissement->telephone ?? '-' }}
                                    </td>
                                    <td>
                                        @if ($etablissement->email)
                                            <a href="mailto:{{ $etablissement->email }}" class="text-decoration-none">
                                                {{ $etablissement->email }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('etablissements.show', $etablissement) }}" 
                                               class="btn btn-outline-info" 
                                               title="Voir détails">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('etablissements.edit', $etablissement) }}" 
                                               class="btn btn-outline-warning" 
                                               title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('etablissements.destroy', $etablissement) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet établissement ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger" 
                                                        title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <!-- Aucun établissement -->
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                </div>
                <h5 class="text-muted mb-3">Aucun établissement trouvé</h5>
                <p class="text-muted mb-4">Créez votre premier établissement pour commencer.</p>
                <a href="{{ route('etablissements.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Créer un établissement
                </a>
            </div>
        </div>
    @endif
</div>

@endsection