@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-warning">
            <h5 class="mb-0">✏️ Modifier le frais de l’élève</h5>
        </div>

        <div class="card-body">

            <p>
                <strong>Élève :</strong>
                {{ $inscription_frai->inscription->eleve->nom }}
                {{ $inscription_frai->inscription->eleve->prenom }}
                <br>
                <strong>Classe :</strong>
                {{ $inscription_frai->inscription->classe->nom }}
                <br>
                <strong>Frais :</strong>
                {{ $inscription_frai->frais->nom }}
            </p>

            <hr>

            <form method="POST"
                  action="{{ route('inscription-frais.update', $inscription_frai->id) }}">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Montant total</label>
                        <input type="number"
                               name="montant_frais"
                               class="form-control"
                               value="{{ old('montant_frais', $inscription_frai->montant_frais) }}"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Montant payé</label>
                        <input type="number"
                               name="montant_paye"
                               class="form-control"
                               value="{{ old('montant_paye', $inscription_frai->montant_paye) }}"
                               required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Statut</label>
                        <select name="statut" class="form-select">
                            <option value="non_payé" {{ $inscription_frai->statut == 'non_payé' ? 'selected' : '' }}>
                                Non payé
                            </option>
                            <option value="partiellement_payé" {{ $inscription_frai->statut == 'partiellement_payé' ? 'selected' : '' }}>
                                Partiellement payé
                            </option>
                            <option value="soldé" {{ $inscription_frai->statut == 'soldé' ? 'selected' : '' }}>
                                Soldé
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">En arriéré ?</label>
                        <select name="est_arriere" class="form-select">
                            <option value="0" {{ !$inscription_frai->est_arriere ? 'selected' : '' }}>Non</option>
                            <option value="1" {{ $inscription_frai->est_arriere ? 'selected' : '' }}>Oui</option>
                        </select>
                    </div>
                </div>

                <div class="text-end">
                    <a href="{{ route('inscription-frais.show', $inscription_frai->id) }}"
                       class="btn btn-secondary">
                        Annuler
                    </a>

                    <button class="btn btn-primary">
                        💾 Enregistrer
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
