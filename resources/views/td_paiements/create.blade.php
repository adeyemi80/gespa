@extends('tableau.neutre')

@section('title', 'Nouveau paiement TD')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4" style="max-width: 680px;">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('td-paiements.index', ['annee_id' => $annee_id]) }}"
           class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="mb-0 fw-bold">Nouveau paiement TD</h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('td-paiements.store') }}" method="POST" novalidate>
                @csrf

                {{-- Année --}}
                <div class="mb-3">
                    <label for="annee_id" class="form-label fw-semibold">
                        Année scolaire <span class="text-danger">*</span>
                    </label>
                    <select name="annee_id" id="annee_id"
                            class="form-select @error('annee_id') is-invalid @enderror">
                        <option value="">Choisir…</option>
                        @foreach($annees as $annee)
                            <option value="{{ $annee->id }}"
                                {{ old('annee_id', $annee_id) == $annee->id ? 'selected' : '' }}>
                                {{ $annee->libelle ?? $annee->nom ?? $annee->id }}
                            </option>
                        @endforeach
                    </select>
                    @error('annee_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Élève --}}
                <div class="mb-3">
                    <label for="eleve_id" class="form-label fw-semibold">
                        Élève <span class="text-danger">*</span>
                    </label>

                    @if($eleve)
                        {{-- Pré-sélectionné depuis la fiche élève --}}
                        <input type="hidden" name="eleve_id" value="{{ $eleve->id }}">
                        <input type="text"
                               class="form-control bg-light"
                               value="{{ $eleve->nom }} {{ $eleve->prenom }}"
                               readonly>
                    @else
                        {{-- Recherche libre --}}
                        <input type="text"
                               id="eleve_search"
                               class="form-control @error('eleve_id') is-invalid @enderror mb-1"
                               placeholder="Taper le nom ou prénom pour filtrer…"
                               autocomplete="off">
                        <select name="eleve_id" id="eleve_id"
                                class="form-select @error('eleve_id') is-invalid @enderror"
                                size="5">
                            <option value="">— saisir le nom ci-dessus —</option>
                        </select>
                        @error('eleve_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Tapez au moins 2 caractères pour chercher.</small>
                    @endif
                </div>

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
                           value="{{ old('montant') }}"
                           class="form-control @error('montant') is-invalid @enderror"
                           placeholder="Ex : 5000">
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
                           value="{{ old('date_paiement', now()->toDateString()) }}"
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
                           value="{{ old('reference') }}"
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
                              class="form-control @error('observation') is-invalid @enderror"
                              placeholder="Remarque facultative…">{{ old('observation') }}</textarea>
                    @error('observation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Enregistrer
                    </button>
                    <a href="{{ route('td-paiements.index', ['annee_id' => $annee_id]) }}"
                       class="btn btn-outline-secondary">
                        Annuler
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Recherche élève en AJAX (requiert une route /api/eleves?search=...)
    const searchInput = document.getElementById('eleve_search');
    const selectEleve = document.getElementById('eleve_id');

    if (searchInput && selectEleve) {
        let timer;

        searchInput.addEventListener('input', function () {
            clearTimeout(timer);
            const q = this.value.trim();

            if (q.length < 2) {
                selectEleve.innerHTML = '<option value="">— saisir le nom ci-dessus —</option>';
                return;
            }

            timer = setTimeout(async () => {
                const res  = await fetch(`/api/eleves?search=${encodeURIComponent(q)}`);
                const data = await res.json();

                selectEleve.innerHTML = data.length
                    ? data.map(e =>
                        `<option value="${e.id}">${e.nom} ${e.prenom}</option>`
                      ).join('')
                    : '<option value="">Aucun résultat</option>';
            }, 300);
        });
    }
</script>
@endpush

@endsection