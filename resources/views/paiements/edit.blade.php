@extends('tableau.neutre')

@section('content')

<div class="container py-5">

```
{{-- Retour --}}
<div class="mb-3">
    <button
        type="button"
        onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
        class="btn btn-secondary">
        ⬅️ Retour
    </button>
</div>

<h3 class="mb-4 text-warning">
    ✏️ Modifier le Paiement
</h3>

{{-- Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Fermer"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Fermer"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <strong>Veuillez corriger les erreurs suivantes :</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card shadow border-0">

    <div class="card-header bg-warning text-dark">
        <strong>Informations du paiement</strong>
    </div>

    <div class="card-body">

        <form action="{{ route('paiements.update', $paiement->id) }}"
              method="POST"
              class="row g-3">

            @csrf
            @method('PUT')

            {{-- ============================= --}}
            {{-- INFORMATIONS NON MODIFIABLES --}}
            {{-- ============================= --}}

            {{-- Numéro de reçu --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">
                    🧾 Numéro de reçu
                </label>

                <input type="text"
                       class="form-control bg-light"
                       value="{{ $paiement->numero_recu ?? '—' }}"
                       readonly>
            </div>

            {{-- Année scolaire --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">
                    📅 Année scolaire
                </label>

                <input type="text"
                       class="form-control bg-light"
                       value="{{ optional(optional($paiement->inscription)->annee)->nom ?? '—' }}"
                       readonly>
            </div>

            {{-- Classe --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">
                    🏫 Classe
                </label>

                <input type="text"
                       class="form-control bg-light"
                       value="{{ optional(optional($paiement->inscription)->classe)->nom ?? '—' }}"
                       readonly>
            </div>

            {{-- Élève --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">
                    👨‍🎓 Élève
                </label>

                <input type="text"
                       class="form-control bg-light"
                       value="{{ optional(optional($paiement->inscription)->eleve)->nom ?? '' }}
                              {{ optional(optional($paiement->inscription)->eleve)->prenom ?? '' }}"
                       readonly>
            </div>

            {{-- Matricule --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">
                    🔖 Matricule
                </label>

                <input type="text"
                       class="form-control bg-light"
                       value="{{ optional(optional($paiement->inscription)->eleve)->matricule ?? '—' }}"
                       readonly>
            </div>

            {{-- Frais --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">
                    📌 Frais concerné
                </label>

                <input type="text"
                       class="form-control bg-light"
                       value="{{ optional($paiement->frais)->nom
                                ?? optional($paiement->frais)->description
                                ?? '—' }}"
                       readonly>
            </div>

            {{-- ============================= --}}
            {{-- DONNÉES MODIFIABLES --}}
            {{-- ============================= --}}

            {{-- Montant --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">
                    💰 Montant versé
                </label>

                <input type="number"
                       name="montant_verse"
                       class="form-control"
                       value="{{ old('montant_verse', $paiement->montant_verse) }}"
                       min="0"
                       step="0.01"
                       required>

                <small class="text-muted">
                    Montant actuellement enregistré :
                    <strong>
                        {{ number_format($paiement->montant_verse, 0, ',', ' ') }} F
                    </strong>
                </small>
            </div>

            {{-- Mode de paiement --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">
                    💳 Mode de paiement
                </label>

                <select name="mode_paiement"
                        class="form-select"
                        required>

                    @foreach([
                        'Espèce',
                        'Mobile Money',
                        'Chèque',
                        'Virement Bancaire'
                    ] as $mode)

                        <option value="{{ $mode }}"
                            {{ old('mode_paiement', $paiement->mode_paiement) == $mode ? 'selected' : '' }}>
                            {{ $mode }}
                        </option>

                    @endforeach

                </select>
            </div>

            {{-- Date --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">
                    📅 Date de paiement
                </label>

                <input type="date"
                       name="date_paiement"
                       class="form-control"
                       value="{{ old(
                           'date_paiement',
                           $paiement->date_paiement
                               ? \Carbon\Carbon::parse($paiement->date_paiement)->format('Y-m-d')
                               : ''
                       ) }}"
                       required>
            </div>

            {{-- ============================= --}}
            {{-- APERÇU DU PAIEMENT --}}
            {{-- ============================= --}}

            <div class="col-12 mt-4">

                <div class="alert alert-light border">

                    <h6 class="fw-bold mb-3">
                        📋 Données enregistrées actuellement
                    </h6>

                    <div class="row">

                        <div class="col-md-4 mb-2">
                            <span class="text-muted">Montant :</span><br>
                            <strong>
                                {{ number_format($paiement->montant_verse, 0, ',', ' ') }} F
                            </strong>
                        </div>

                        <div class="col-md-4 mb-2">
                            <span class="text-muted">Mode :</span><br>
                            <strong>
                                {{ $paiement->mode_paiement ?? '—' }}
                            </strong>
                        </div>

                        <div class="col-md-4 mb-2">
                            <span class="text-muted">Date :</span><br>
                            <strong>
                                {{ $paiement->date_paiement
                                    ? \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y')
                                    : '—' }}
                            </strong>
                        </div>

                    </div>

                </div>

            </div>

            {{-- ============================= --}}
            {{-- ACTIONS --}}
            {{-- ============================= --}}

            <div class="col-12 d-flex justify-content-between mt-3">

                <button
                    type="button"
                    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
                    class="btn btn-secondary">

                    ❌ Annuler
                </button>

                <button type="submit"
                        class="btn btn-warning btn-lg px-5">

                    💾 Mettre à jour
                </button>

            </div>

        </form>

    </div>
</div>
```

</div>

@endsection
