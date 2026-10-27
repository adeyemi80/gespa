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
                <i class="fas fa-edit text-primary me-2"></i>
                Modifier une inscription TD
            </h3>
            <p class="text-muted mb-0">
                Inscription n° {{ $inscription->id }}
            </p>
        </div>

        <a href="{{ route('inscriptions-td-externes.show', $inscription->id) }}"
           class="btn btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-2"></i> Retour aux détails
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Veuillez corriger les erreurs :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 p-4">
            <h5 class="fw-bold mb-0">Informations de l'inscription</h5>
        </div>

        <div class="card-body p-4">

            <form method="POST"
                  action="{{ route('inscriptions-td-externes.update', $inscription->id) }}"
                  id="formInscriptionTD">
                @csrf
                @method('PUT')

                <div class="row g-4">

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
                                    @selected((string) old('eleve_externe_td_id', $inscription->eleve_externe_td_id) === (string) $eleve->id)>
                                    {{ $eleve->nom }} {{ $eleve->prenom }}
                                    — {{ $eleve->matricule }}
                                </option>
                            @endforeach
                        </select>

                        @error('eleve_externe_td_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

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
                                    @selected((string) old('annee_id', $inscription->annee_id) === (string) $annee->id)>
                                    {{ $annee->libelle ?? $annee->nom ?? $annee->id }}
                                </option>
                            @endforeach
                        </select>

                        @error('annee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="classe_id" class="form-label fw-semibold">
                            Classe <span class="text-danger">*</span>
                        </label>

                        <select name="classe_id"
                                id="classe_id"
                                class="form-select @error('classe_id') is-invalid @enderror"
                                required>
                            <option value="">-- Sélectionner une classe --</option>

                            @foreach(($classes ?? collect()) as $classe)
                                <option value="{{ $classe->id }}"
                                    @selected((string) old('classe_id', $inscription->classe_id) === (string) $classe->id)>
                                    {{ $classe->nom }}
                                </option>
                            @endforeach
                        </select>

                        @error('classe_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="frais_id" class="form-label fw-semibold">
                            Frais TD <span class="text-danger">*</span>
                        </label>

                        <select name="frais_id"
                                id="frais_id"
                                class="form-select @error('frais_id') is-invalid @enderror"
                                required>
                            <option value="{{ $inscription->frais_id }}">
                                {{ $inscription->frais->nom
                                    ?? $inscription->frais->libelle
                                    ?? 'Frais actuel' }}
                                — {{ number_format((float) $inscription->montant_total, 0, ',', ' ') }} FCFA
                            </option>
                        </select>

                        @error('frais_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="form-text">
                            Les frais proposés proviennent de la table des frais existants.
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="date_inscription" class="form-label fw-semibold">
                            Date d'inscription <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="date_inscription"
                               id="date_inscription"
                               class="form-control @error('date_inscription') is-invalid @enderror"
                               value="{{ old('date_inscription', $inscription->date_inscription?->format('Y-m-d')) }}"
                               required>

                        @error('date_inscription')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="statut" class="form-label fw-semibold">Statut</label>

                        <select name="statut" id="statut" class="form-select">
                            <option value="active"
                                @selected(old('statut', $inscription->statut) === 'active')>
                                Active
                            </option>

                            <option value="inactive"
                                @selected(old('statut', $inscription->statut) === 'inactive')>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label for="observations" class="form-label fw-semibold">
                            Observations
                        </label>

                        <textarea name="observations"
                                  id="observations"
                                  rows="3"
                                  class="form-control">{{ old('observations', $inscription->observations) }}</textarea>
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2 flex-wrap">
                    <a href="{{ route('inscriptions-td-externes.show', $inscription->id) }}"
                       class="btn btn-light border rounded-pill px-4">
                        Annuler
                    </a>

                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-save me-2"></i> Enregistrer les modifications
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

    const fraisInitial = @json((int) $inscription->frais_id);
    const anneeInitiale = @json((int) $inscription->annee_id);
    const classeInitiale = @json((int) $inscription->classe_id);
    const ancienFraisId = @json(old('frais_id'));

    async function chargerFrais() {
        if (!annee.value || !classe.value) {
            return;
        }

        const url = new URL(
            @json(route('inscriptions-td-externes.frais-disponibles')),
            window.location.origin
        );

        url.searchParams.set('annee_id', annee.value);
        url.searchParams.set('classe_id', classe.value);

        try {
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Chargement des frais impossible.');
            }

            const data = await response.json();
            const liste = Array.isArray(data) ? data : (data.frais ?? []);

            frais.innerHTML = '';
            frais.add(new Option('-- Sélectionner un frais TD --', ''));

            liste.forEach(function (item) {
                const prix = Number(item.montant ?? 0);
                const nom = item.nom ?? item.libelle ?? item.description ?? 'Frais TD';

                const option = new Option(
                    nom + ' — ' + prix.toLocaleString('fr-FR') + ' FCFA',
                    item.id
                );

                frais.add(option);
            });

            // L'inscription actuelle peut conserver son frais si celui-ci
            // appartient toujours à l'année et à la classe sélectionnées.
            const selection = ancienFraisId || fraisInitial;

            if (String(annee.value) === String(anneeInitiale)
                && String(classe.value) === String(classeInitiale)) {
                frais.value = String(selection);
            } else if (ancienFraisId) {
                frais.value = String(ancienFraisId);
            }

            // Si le frais actuel n'est pas dans la liste retournée,
            // on ne le sélectionne pas arbitrairement.
        } catch (error) {
            console.error(error);
        }
    }

    annee.addEventListener('change', chargerFrais);
    classe.addEventListener('change', chargerFrais);

    chargerFrais();
});
</script>
@endsection