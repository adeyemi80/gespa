@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient text-white rounded-top-4" style="background: linear-gradient(90deg, #198754, #20c997);">
                    <h3 class="mb-0"><i class="bi bi-plus-circle-fill me-2"></i> Nouvelle Transaction</h3>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('transactions.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        {{-- Type --}}
                        <div class="mb-3">
                            <label for="type" class="form-label fw-bold">Type</label>
                            <select name="type" id="type" class="form-select shadow-sm" required>
                                <option value="recette">💰 Recette</option>
                                <option value="dépense">💸 Dépense</option>
                            </select>
                        </div>

                        {{-- Montant --}}
                        <div class="mb-3">
                            <label for="montant" class="form-label fw-bold">Montant</label>
                            <div class="input-group">
                                <span class="input-group-text">FCFA</span>
                                <input type="number" step="0.01" name="montant" id="montant" class="form-control shadow-sm" placeholder="Ex: 5000" required>
                            </div>
                        </div>

                        {{-- Catégorie --}}
                        <div class="mb-3">
                            <label for="categorie_id" class="form-label fw-bold">Catégorie</label>
                            <select name="categorie_id" id="categorie_id" class="form-select shadow-sm" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Compte --}}
                        <div class="mb-3">
                            <label for="compte_id" class="form-label fw-bold">Compte</label>
                            <select name="compte_id" id="compte_id" class="form-select shadow-sm" required>
                                @foreach($comptes as $cpt)
                                    <option value="{{ $cpt->id }}">{{ $cpt->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date --}}
                        <div class="mb-3">
                            <label for="date_transaction" class="form-label fw-bold">Date</label>
                            <input type="date" class="form-control @error('date_paiement') is-invalid @enderror"
                           id="date_transaction" name="date_transaction" value="{{ old('date_transaction') ?? now()->toDateString() }}" required>
                              @error('date_transaction')
                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                        </div>

                        {{-- Boutons --}}
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-left-circle me-1"></i> Annuler
                            </a>
                            <button class="btn btn-success px-4">
                                <i class="bi bi-check-circle me-1"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
