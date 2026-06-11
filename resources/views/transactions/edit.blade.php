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
                <div class="card-header bg-gradient text-white rounded-top-4" style="background: linear-gradient(90deg, #0d6efd, #20c997);">
                    <h3 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Modifier Transaction</h3>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('transactions.update', $transaction->id) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- Type --}}
                        <div class="mb-3">
                            <label for="type" class="form-label fw-bold">Type</label>
                            <select name="type" id="type" class="form-select shadow-sm" required>
                                <option value="recette" {{ $transaction->type=='recette'?'selected':'' }}>💰 Recette</option>
                                <option value="depense" {{ $transaction->type=='depense'?'selected':'' }}>💸 Dépense</option>
                            </select>
                        </div>

                        {{-- Montant --}}
                        <div class="mb-3">
                            <label for="montant" class="form-label fw-bold">Montant</label>
                            <div class="input-group">
                                <span class="input-group-text">FCFA</span>
                                <input type="number" step="0.01" name="montant" id="montant"
                                       value="{{ $transaction->montant }}"
                                       class="form-control shadow-sm" required>
                            </div>
                        </div>

                        {{-- Catégorie --}}
                        <div class="mb-3">
                            <label for="categorie_id" class="form-label fw-bold">Catégorie</label>
                            <select name="categorie_id" id="categorie_id" class="form-select shadow-sm">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $transaction->categorie_id==$cat->id?'selected':'' }}>
                                        {{ $cat->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Compte --}}
                        <div class="mb-3">
                            <label for="compte_id" class="form-label fw-bold">Compte</label>
                            <select name="compte_id" id="compte_id" class="form-select shadow-sm">
                                @foreach($comptes as $cpt)
                                    <option value="{{ $cpt->id }}" {{ $transaction->compte_id==$cpt->id?'selected':'' }}>
                                        {{ $cpt->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date --}}
                        <div class="mb-3">
                            <label for="date" class="form-label fw-bold">Date</label>
                            <input type="date" name="date" id="date"
                                   value="{{ $transaction->date->format('Y-m-d') }}"
                                   class="form-control shadow-sm" required>
                        </div>

                        {{-- Boutons --}}
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-left-circle me-1"></i> Annuler
                            </a>
                            <button class="btn btn-primary px-4">
                                <i class="bi bi-save2 me-1"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
