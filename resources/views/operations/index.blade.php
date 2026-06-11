@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-5">

    <h2 class="mb-4">📊 Liste des opérations</h2>

    <!-- Résumé des finances -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow border-0 rounded-4 bg-success text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">✅ Total Recettes</h5>
                    <p class="fs-4 fw-bold">{{ number_format($recettes, 2, ',', ' ') }} FCFA</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow border-0 rounded-4 bg-danger text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">❌ Total Dépenses</h5>
                    <p class="fs-4 fw-bold">{{ number_format($depenses, 2, ',', ' ') }} FCFA</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow border-0 rounded-4 {{ $solde >= 0 ? 'bg-primary' : 'bg-warning text-dark' }}">
                <div class="card-body text-center">
                    <h5 class="card-title">💼 Solde</h5>
                    <p class="fs-4 fw-bold">{{ number_format($solde, 2, ',', ' ') }} FCFA</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bouton ajouter -->
    <div class="mb-3">
        <a href="{{ route('operations.create') }}" class="btn btn-success btn-lg">➕ Ajouter une opération</a>
    </div>

    <!-- Tableau des opérations -->
    <div class="card shadow border-0 rounded-4">
        <div class="card-body">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>📅 Date</th>
                        <th>🏷️ Libellé</th>
                        <th>💰 Montant</th>
                        <th>📂 Catégorie</th>
                        <th>📝 Description</th>
                        <th>⚙️ Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($operations as $operation)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($operation->date)->format('d/m/Y') }}</td>
                        <td>{{ $operation->libelle }}</td>
                        <td class="fw-bold">{{ number_format($operation->montant, 2, ',', ' ') }} FCFA</td>
                        <td class="text-center">
                            @if($operation->categorie === 'recette')
                                <span class="badge bg-success fs-6">Recette</span>
                            @else
                                <span class="badge bg-danger fs-6">Dépense</span>
                            @endif
                        </td>
                        <td>{{ $operation->description ?? '---' }}</td>
                        <td class="text-center">
                            <a href="{{ route('operations.show', $operation->id) }}" class="btn btn-info btn-sm">👁️</a>
                            <a href="{{ route('operations.edit', $operation->id) }}" class="btn btn-warning btn-sm">✏️ </a>
                            <form action="{{ route('operations.destroy', $operation->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression ?')">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $operations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
