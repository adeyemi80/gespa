@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <h3 class="mb-4 text-warning">✏️ Modifier le Paiement</h3>

    <div class="card shadow border-0">
        <div class="card-body">
            <form action="{{ route('paiements.update', $paiement->id) }}"
                  method="POST"
                  class="row g-3">
                @csrf
                @method('PUT')
 {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif
                {{-- Année (verrouillée) --}}
                <div class="col-md-6">
                    <label class="form-label">Année scolaire</label>
                    <select class="form-select" disabled>
    <option selected>
        {{ $paiement->inscription->annee->nom }}
    </option>
</select>
<input type="hidden" name="annee_id"
       value="{{ $paiement->inscription->annee_id }}">

                </div>

                {{-- Classe (verrouillée) --}}
                <div class="col-md-6">
                    <label class="form-label">Classe</label>
                    <select class="form-select" disabled>
                        @foreach($classes as $classe)
                            <option {{ $paiement->classe_id == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="classe_id" value="{{ $paiement->inscription->classe_id }}">
                </div>

                {{-- Élève (verrouillé) --}}
                <div class="col-md-6">
                    <label class="form-label">👨‍🎓 Élève</label>
                    <select class="form-select" disabled>
                        @foreach($inscriptions as $insc)
                            <option {{ $paiement->inscription_id == $insc->id ? 'selected' : '' }}>
                                {{ $insc->eleve->nom }} {{ $insc->eleve->prenom }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="inscription_id" value="{{ $paiement->inscription_id }}">
                </div>

                {{-- Frais (verrouillé) --}}
                <div class="col-md-6">
                    <label class="form-label">📌 Frais</label>
                    <select class="form-select" disabled>
                        @foreach($frais as $item)
                            <option {{ $paiement->frais_id == $item->id ? 'selected' : '' }}>
                                {{ $item->description }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="frais_id" value="{{ $paiement->frais_id }}">
                </div>

                {{-- Montant --}}
                <div class="col-md-6">
                    <label class="form-label">💰 Montant versé</label>
                    <input type="number"
                           name="montant_verse"
                           class="form-control"
                           value="{{ $paiement->montant_verse }}"
                           min="0"
                           required>
                </div>

                {{-- Mode --}}
                <div class="col-md-6">
                    <label class="form-label">Mode de paiement</label>
                    <select name="mode_paiement" class="form-select" required>
                        @foreach(['Espèce','Mobile Money','Chèque','Virement Bancaire'] as $mode)
                            <option value="{{ $mode }}"
                                {{ $paiement->mode_paiement == $mode ? 'selected' : '' }}>
                                {{ $mode }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Date --}}
                <div class="col-md-6">
                    <label class="form-label">Date de paiement</label>
                    <input type="date"
                           name="date_paiement"
                           class="form-control"
                           value="{{ $paiement->date_paiement->toDateString() }}"
                           required>
                </div>

                {{-- Actions --}}
                <div class="col-12 d-grid mt-3">
                    <button class="btn btn-warning btn-lg">
                        💾 Mettre à jour
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
