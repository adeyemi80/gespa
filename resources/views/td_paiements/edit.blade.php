@extends('tableau.neutre')

@section('title', 'Modifier un paiement TD')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4" style="max-width: 680px;">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('td-paiements.index', ['annee_id' => $tdPaiement->annee_id]) }}"
           class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="mb-0 fw-bold">Modifier le paiement</h4>
    </div>

    {{-- Résumé de l'élève --}}
    <div class="alert alert-light border mb-4 d-flex align-items-center gap-3">
        <i class="bi bi-person-circle fs-4 text-secondary"></i>
        <div>
            <div class="fw-bold">
                {{ $tdPaiement->eleve->nom }} {{ $tdPaiement->eleve->prenom }}
            </div>
            <small class="text-muted">
                Année : {{ $tdPaiement->annee->libelle ?? $tdPaiement->annee->nom ?? $tdPaiement->annee_id }}
                — Enregistré le {{ $tdPaiement->created_at->format('d/m/Y') }}
            </small>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('td-paiements.update', $tdPaiement) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                {{-- Champs cachés (non modifiables) --}}
                <input type="hidden" name="eleve_id" value="{{ $tdPaiement->eleve_id }}">
                <input type="hidden" name="annee_id" value="{{ $tdPaiement->annee_id }}">

                {{-- Montant --}}
                <div class="mb-3">
                    <label for="montant" class="form-label fw-semibold">
                        Montant (F) <span class="text-danger">*</span>
                    </label>
                    <input type="number"
                           name="montant"
                           id="montant"
                           min="1"
                           step="1"
                           value="{{ old('montant', $tdPaiement->montant) }}"
                           class="form-control @error('montant') is-invalid @enderror">
                    @error('montant')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Date de paiement --}}
                <div class="mb-3">
                    <label for="date_paiement" class="form-label fw-semibold">
                        Date du paiement <span class="text-danger">*</span>
                    </label>
                    <input type="date"
                           name="date_paiement"
                           id="date_paiement"
                           value="{{ old('date_paiement', \Carbon\Carbon::parse($tdPaiement->date_paiement)->toDateString()) }}"
                           class="form-control @error('date_paiement') is-invalid @enderror">
                    @error('date_paiement')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Référence --}}
                <div class="mb-3">
                    <label for="reference" class="form-label fw-semibold">Référence</label>
                    <input type="text"
                           name="reference"
                           id="reference"
                           value="{{ old('reference', $tdPaiement->reference) }}"
                           class="form-control @error('reference') is-invalid @enderror"
                           placeholder="N° reçu, mobile money…">
                    @error('reference')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Observation --}}
                <div class="mb-4">
                    <label for="observation" class="form-label fw-semibold">Observation</label>
                    <textarea name="observation"
                              id="observation"
                              rows="3"
                              class="form-control @error('observation') is-invalid @enderror">{{ old('observation', $tdPaiement->observation) }}</textarea>
                    @error('observation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Mettre à jour
                    </button>
                    <a href="{{ route('td-paiements.show', $tdPaiement) }}"
                       class="btn btn-outline-secondary">
                        Annuler
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection