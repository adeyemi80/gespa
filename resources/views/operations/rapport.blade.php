@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-11">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-bar-chart-line"></i> Rapport des opérations
                    </h4>
                </div>

                <div class="card-body p-4">
                    {{-- Formulaire de filtre --}}
                    <form action="{{ route('operations.rapport.generer') }}" method="POST" class="row g-3 mb-4">
                        @csrf

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Libellé</label>
                            <select name="libelle" class="form-select shadow-sm">
                                <option value="">-- Tous --</option>
                                @foreach($libelles as $libelle)
                                    <option value="{{ $libelle }}" {{ request('libelle') == $libelle ? 'selected' : '' }}>
                                        {{ $libelle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Catégorie</label>
                            <select name="categorie" class="form-select shadow-sm">
                                <option value="">-- Toutes --</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie }}" {{ request('categorie') == $categorie ? 'selected' : '' }}>
                                        {{ ucfirst($categorie) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-bold">Date début</label>
                            <input type="date" name="date_debut" class="form-control shadow-sm" value="{{ request('date_debut') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-bold">Date fin</label>
                            <input type="date" name="date_fin" class="form-control shadow-sm" value="{{ request('date_fin') }}">
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-search"></i> Filtrer
                            </button>
                        </div>
                    </form>

                    @isset($operations)
                        {{-- Totaux et PDF --}}
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Total recettes :</strong> {{ number_format($recettes, 2, ',', ' ') }} FCFA<br>
                                <strong>Total dépenses :</strong> {{ number_format($depenses, 2, ',', ' ') }} FCFA<br>
                                <strong>Solde :</strong> {{ number_format($solde, 2, ',', ' ') }} FCFA
                            </div>
                            <a href="{{ route('operations.rapport.pdf', request()->all()) }}" class="btn btn-danger px-4">
                                <i class="bi bi-file-earmark-pdf"></i> Export PDF
                            </a>
                        </div>

                        {{-- Tableau des opérations --}}
                        <div class="table-responsive shadow-sm rounded-3 overflow-hidden">
                            <table class="table table-striped table-hover table-bordered align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Libellé</th>
                                        <th>Catégorie</th>
                                        <th>Description</th>
                                          <th>Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($operations as $operation)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($operation->date)->format('d/m/Y') }}</td>
                                            <td>{{ $operation->libelle }}</td>
                                            <td>
                                                @if($operation->categorie === 'recette')
                                                    <span class="badge bg-success">Recette</span>
                                                @else
                                                    <span class="badge bg-danger">Dépense</span>
                                                @endif
                                            </td>
                                            <td>{{ $operation->description }}</td>
                                              <td  class="auto-size">{{ number_format($operation->montant, 2, ',', ' ') }} FCFA</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                <i class="bi bi-exclamation-circle"></i> Aucune opération trouvée
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endisset
                </div>

                <div class="card-footer text-muted small text-center">
                    <i class="bi bi-info-circle"></i> Sélectionnez un libellé, une catégorie et une période pour filtrer les opérations et générer le PDF.
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
