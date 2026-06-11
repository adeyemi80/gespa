@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-gradient text-white d-flex justify-content-between align-items-center rounded-top-4"
             style="background: linear-gradient(90deg, #0d6efd, #20c997);">
            <h3 class="mb-0"><i class="bi bi-list-ul me-2"></i> Liste des Transactions</h3>
            <a href="{{ route('transactions.create') }}" class="btn btn-light btn-sm fw-bold shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Nouvelle Transaction
            </a>
        </div>

        <div class="card-body p-4">
            {{-- Message succès --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            {{-- Tableau --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Catégorie</th>
                            <th>Compte</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td class="fw-bold">{{ $transaction->id }}</td>
                            <td>
                                @if($transaction->type == 'recette')
                                    <span class="badge bg-success"><i class="bi bi-arrow-up-circle me-1"></i> Recette</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-arrow-down-circle me-1"></i> Dépense</span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ number_format($transaction->montant, 2, ',', ' ') }} FCFA</td>
                            <td>{{ $transaction->categorie->nom ?? '—' }}</td>
                            <td>{{ $transaction->compte->nom ?? '—' }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaction->date_transaction)->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('transactions.show', $transaction->id) }}" 
                                   class="btn btn-sm btn-info me-1">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('transactions.edit', $transaction->id) }}" 
                                   class="btn btn-sm btn-warning me-1">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" 
                                      class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Supprimer cette transaction ?')">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Aucune transaction trouvée
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
