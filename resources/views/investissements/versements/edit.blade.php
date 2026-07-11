@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container-fluid py-4">

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-warning text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-edit me-2"></i>
                            Modifier le versement
                        </h4>

                        <a href="{{ route('versements.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6 class="fw-bold">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Veuillez corriger les erreurs suivantes :
                            </h6>

                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('versements.update', $versement) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                Investissement
                                <span class="text-danger">*</span>
                            </label>

                            <select name="investissement_id"
                                    class="form-select @error('investissement_id') is-invalid @enderror">

                                <option value="">-- Sélectionner un investissement --</option>

                                @foreach ($investissements as $investissement)
                                    <option value="{{ $investissement->id }}"
                                        {{ old('investissement_id', $versement->investissement_id) == $investissement->id ? 'selected' : '' }}>

                                        {{ $investissement->investisseur->nom ?? '' }}
                                        {{ $investissement->investisseur->prenom ?? '' }}
                                        | Investissement #{{ $investissement->id }}

                                    </option>
                                @endforeach

                            </select>

                            @error('investissement_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    Date du versement
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="date"
                                       name="date_versement"
                                       value="{{ old('date_versement', optional($versement->date_versement)->format('Y-m-d') ?? $versement->date_versement) }}"
                                       class="form-control @error('date_versement') is-invalid @enderror">

                                @error('date_versement')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    Montant (F CFA)
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-coins"></i>
                                    </span>

                                    <input type="number"
                                           step="0.01"
                                           min="1"
                                           name="montant"
                                           value="{{ old('montant', $versement->montant) }}"
                                           class="form-control @error('montant') is-invalid @enderror">

                                </div>

                                @error('montant')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    Mode de paiement
                                </label>

                                <select name="mode_paiement"
                                        class="form-select @error('mode_paiement') is-invalid @enderror">

                                    <option value="">-- Choisir --</option>

                                    <option value="Espèces"
                                        {{ old('mode_paiement', $versement->mode_paiement) == 'Espèces' ? 'selected' : '' }}>
                                        Espèces
                                    </option>

                                    <option value="Mobile Money"
                                        {{ old('mode_paiement', $versement->mode_paiement) == 'Mobile Money' ? 'selected' : '' }}>
                                        Mobile Money
                                    </option>

                                    <option value="Virement bancaire"
                                        {{ old('mode_paiement', $versement->mode_paiement) == 'Virement bancaire' ? 'selected' : '' }}>
                                        Virement bancaire
                                    </option>

                                    <option value="Chèque"
                                        {{ old('mode_paiement', $versement->mode_paiement) == 'Chèque' ? 'selected' : '' }}>
                                        Chèque
                                    </option>

                                </select>

                                @error('mode_paiement')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    Référence
                                </label>

                                <input type="text"
                                       name="reference"
                                       value="{{ old('reference', $versement->reference) }}"
                                       placeholder="Numéro de transaction"
                                       class="form-control @error('reference') is-invalid @enderror">

                                @error('reference')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                Observation
                            </label>

                            <textarea name="observation"
                                      rows="4"
                                      class="form-control @error('observation') is-invalid @enderror"
                                      placeholder="Ajouter une remarque éventuelle...">{{ old('observation', $versement->observation) }}</textarea>

                            @error('observation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end">

                            <a href="{{ route('versements.index') }}"
                               class="btn btn-secondary me-2">
                                <i class="fas fa-times"></i>
                                Annuler
                            </a>

                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i>
                                Mettre à jour
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>

</div>
@endsection