@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                <i class="fas fa-user-plus text-primary me-2"></i>
                Nouvelle inscription TD externe
            </h3>
            <p class="text-muted mb-0">
                Enregistrer un élève externe aux travaux dirigés.
            </p>
        </div>

        <a href="{{ route('inscriptions-td-externes.index') }}"
           class="btn btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-2"></i> Retour
        </a>
    </div>

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

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white p-4 border-0">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-clipboard-list text-primary me-2"></i>
                Informations de l'inscription
            </h5>
        </div>

        <div class="card-body p-4">
            <form method="POST"
                  action="{{ route('inscriptions-td-externes.store') }}"
                  id="formInscriptionTD">
                @csrf

                <div class="row g-4">

                    {{-- Élève --}}
                    <div class="col-md-6">
                        <label for="eleve_externe_td_id" class="form-label fw-semibold">
                            Élève externe <span class="text-danger">*</span>
                        </label>

                        <select name="eleve_externe_td_id"
                                id="eleve_externe_td_id"
                                class="form-select @error('eleve_externe_td_id') is-invalid @enderror"
                                required>
                            <option value="">-- Sélectionner un élève --</option>

                            @foreach(($eleves ?? collect()) as $eleve)
                                <option value="{{ $eleve->id }}"
                                    @selected((string) old('eleve_externe_td_id') === (string) $eleve->id)>
                                    {{ $eleve->nom }} {{ $eleve->prenom }}
                                    — {{ $eleve->matricule }}
                                </option>
                            @endforeach
                        </select>

                        @error('eleve_externe_td_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="form-text">
                            L'élève doit déjà être enregistré dans le module des élèves externes.
                        </div>
                    </div>

                    {{-- Année --}}
                    <div class="col-md-6">
                        <label for="annee_id" class="form-label fw-semibold">
                            Année scolaire <span class="text-danger">*</span>
                        </label>

                        <select name="annee_id"
                                id="annee_id"
                                class="form-select @error('annee_id') is-invalid @enderror"
                                required>
                            <option value="">-- Sélectionner une année --</option>

                            @foreach(($annees ?? collect()) as $annee)
                                <option value="{{ $annee->id }}"
                                    @selected((string) old('annee_id') === (string) $annee->id)>
                                    {{ $annee->libelle ?? $annee->nom ?? $annee->id }}
                                </option>
                            @endforeach
                        </select>

                        @error('annee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Classe --}}
                    <div class="col-md-6">
                        <label for="classe_id" class="form-label fw-semibold">
                            Classe des TD <span class="text-danger">*</span>
                        </label>

                        <select name="classe_id"
                                id="classe_id"
                                class="form-select @error('classe_id') is-invalid @enderror"
                                required>
                            <option value="">-- Sélectionner une classe --</option>

                            @foreach(($classes ?? collect()) as $classe)
                                <option value="{{ $classe->id }}"
                                    @selected((string) old('classe_id') === (string) $classe->id)>
                                    {{ $classe->nom }}
                                </option>
                            @endforeach
                        </select>

                        @error('classe_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Frais TD --}}
                    <div class="col-md-6">
                        <label for="frais_id" class="form-label fw-semibold">
                            Frais TD applicable <span class="text-danger">*</span>
                        </label>

                        <select name="frais_id"
                                id="frais_id"
                                class="form-select @error('frais_id') is-invalid @enderror"
                                required
                                disabled>
                            <option value="">Sélectionnez d'abord l'année et la classe</option>
                        </select>

                        @error('frais_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="form-text">
                            Le montant est récupéré depuis le frais TD déjà enregistré.
                        </div>
                    </div>

                    {{-- Montant --}}
                    <div class="col-md-6">
                        <label for="montant_affiche" class="form-label fw-semibold">
                            Montant total du frais TD
                        </label>

                        <div class="input-group">
                            <input type="text"
                                   id="montant_affiche"
                                   class="form-control bg-light fw-bold"
                                   value=""
                                   placeholder="Sélectionnez un frais"
                                   readonly>
                            <span class="input-group-text">FCFA</span>
                        </div>

                        <div class="form-text">
                            Le montant total sera déterminé et vérifié par le contrôleur.
                        </div>
                    </div>

                    {{-- Date --}}
                    <div class="col-md-6">
                        <label for="date_inscription" class="form-label fw-semibold">
                            Date d'inscription <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="date_inscription"
                               id="date_inscription"
                               class="form-control @error('date_inscription') is-invalid @enderror"
                               value="{{ old('date_inscription', now()->format('Y-m-d')) }}"
                               required>

                        @error('date_inscription')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Statut --}}
                    <div class="col-md-6">
                        <label for="statut" class="form-label fw-semibold">
                            Statut
                        </label>

                        <select name="statut" id="statut" class="form-select">
                            <option value="active" @selected(old('statut', 'active') === 'active')>
                                Active
                            </option>
                            <option value="inactive" @selected(old('statut') === 'inactive')>
                                Inactive
                            </option>
                        </select>
                    </div>

                    {{-- Observations --}}
                    <div class="col-12">
                        <label for="observations" class="form-label fw-semibold">
                            Observations
                        </label>

                        <textarea name="observations"
                                  id="observations"
                                  rows="3"
                                  class="form-control"
                                  placeholder="Informations complémentaires éventuelles...">{{ old('observations') }}</textarea>
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2 flex-wrap">
                    <a href="{{ route('inscriptions-td-externes.index') }}"
                       class="btn btn-light border rounded-pill px-4">
                        Annuler
                    </a>

                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-save me-2"></i>
                        Enregistrer l'inscription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const annee = document.getElementById('annee_id');
    const classe = document.getElementById('classe_id');
    const frais = document.getElementById('frais_id');
    const montant = document.getElementById('montant_affiche');

    const ancienFraisId = @json(old('frais_id'));

    function reinitialiserFrais(message = '-- Sélectionner un frais TD --') {
        frais.innerHTML = '';
        frais.add(new Option(message, ''));
        frais.disabled = true;
        montant.value = '';
    }

    async function chargerFrais() {
        reinitialiserFrais('-- Chargement des frais TD --');

        if (!annee.value || !classe.value) {
            reinitialiserFrais('-- Sélectionnez l’année et la classe --');
            return;
        }

        try {
            const url = new URL(
                @json(route('inscriptions-td-externes.frais-disponibles')),
                window.location.origin
            );

            url.searchParams.set('annee_id', annee.value);
            url.searchParams.set('classe_id', classe.value);

            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Impossible de récupérer les frais TD.');
            }

            const data = await response.json();
            const liste = Array.isArray(data) ? data : (data.frais ?? []);

            reinitialiserFrais('-- Sélectionner un frais TD --');

            if (liste.length === 0) {
                reinitialiserFrais('-- Aucun frais TD disponible --');
                return;
            }

            liste.forEach(function (item) {
                const prix = Number(item.montant ?? 0);

                const libelle = item.nom
                    ?? item.libelle
                    ?? item.description
                    ?? ('Frais #' + item.id);

                const option = new Option(
                    libelle + ' — ' + prix.toLocaleString('fr-FR') + ' FCFA',
                    item.id
                );

                option.dataset.montant = prix;

                frais.add(option);
            });

            frais.disabled = false;

            if (ancienFraisId) {
                frais.value = String(ancienFraisId);
                afficherMontant();
            }
        } catch (error) {
            reinitialiserFrais('-- Erreur de chargement --');
            console.error(error);
        }
    }

    function afficherMontant() {
        const option = frais.options[frais.selectedIndex];

        if (!option || !option.value) {
            montant.value = '';
            return;
        }

        const prix = Number(option.dataset.montant ?? 0);

        montant.value = prix.toLocaleString('fr-FR', {
            maximumFractionDigits: 2
        });
    }

    annee.addEventListener('change', chargerFrais);
    classe.addEventListener('change', chargerFrais);
    frais.addEventListener('change', afficherMontant);

    if (annee.value && classe.value) {
        chargerFrais();
    }
});
</script>
@endsection