@extends('tableau.neutre')

@section('content')
<button
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
    class="btn btn-secondary mb-3">
    ⬅️ Retour
</button>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-money-check-alt me-2"></i>
                            Nouveau versement
                        </h4>

                        <a href="{{ route('versements.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Retour
                        </a>
                    </div>
                </div>
@if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}

            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ERREURS --}}
            @if ($errors->any())
                <div class="alert alert-danger py-2">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>❌ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <h6 class="fw-bold mb-2">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Veuillez corriger les erreurs suivantes :
                            </h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('versements.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Investisseur <span class="text-danger">*</span>
                            </label>

                            <select name="investissement_id" class="form-select @error('investissement_id') is-invalid @enderror">
                                <option value="">-- Sélectionner un investisseur --</option>
                                @foreach ($investissements as $investissement)
                                    <option value="{{ $investissement->id }}" {{ old('investissement_id') == $investissement->id ? 'selected' : '' }}>
                                        {{ $investissement->investisseur->nom ?? '' }}
                                        {{ $investissement->investisseur->prenom ?? '' }}
                                        | Investissement #{{ $investissement->id }}
                                    </option>
                                @endforeach
                            </select>

                            @error('investissement_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Date du versement <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="date_versement"
                                    value="{{ old('date_versement', date('Y-m-d')) }}"
                                    class="form-control @error('date_versement') is-invalid @enderror"
                                >

                                @error('date_versement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Montant (F CFA) <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-coins"></i>
                                    </span>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="1"
                                        name="montant"
                                        value="{{ old('montant') }}"
                                        placeholder="0"
                                        class="form-control @error('montant') is-invalid @enderror"
                                    >
                                </div>

                                @error('montant')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Mode de VERSEMENT
                                </label>

                                <select name="mode_paiement" class="form-select @error('mode_paiement') is-invalid @enderror">
                                    <option value="">-- Choisir --</option>
                                    <option value="Espèces" {{ old('mode_paiement') == 'Espèces' ? 'selected' : '' }}>Espèces</option>
                                    <option value="Mobile Money" {{ old('mode_paiement') == 'Mobile Money' ? 'selected' : '' }}>Mobile Money</option>
                                    <option value="Virement bancaire" {{ old('mode_paiement') == 'Virement bancaire' ? 'selected' : '' }}>Virement bancaire</option>
                                    <option value="Chèque" {{ old('mode_paiement') == 'Chèque' ? 'selected' : '' }}>Chèque</option>
                                </select>

                                @error('mode_paiement')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Référence
                                </label>

                                <input
                                    type="text"
                                    name="reference"
                                    value="{{ old('reference') }}"
                                    placeholder="Numéro de transaction"
                                    class="form-control @error('reference') is-invalid @enderror"
                                >

                                @error('reference')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 mt-3">
                            <label class="form-label fw-semibold">
                                Observation
                            </label>

                            <textarea
                                name="observation"
                                rows="4"
                                class="form-control @error('observation') is-invalid @enderror"
                                placeholder="Ajouter une remarque éventuelle..."
                            >{{ old('observation') }}</textarea>

                            @error('observation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('versements.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>
                                Annuler
                            </a>

                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i>
                                Enregistrer le versement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection