@extends('tableau.neutre')

@section('content')

<div class="container py-4">

    {{-- Bouton Retour --}}
    <div class="mb-3">
        <button
            type="button"
            onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
            class="btn btn-secondary">
            ⬅️ Retour
        </button>
    </div>

    {{-- Carte principale --}}
    <div class="card shadow-sm border-0 rounded-3">

        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0">💰 Frais par élève</h5>
        </div>

        <div class="card-body">

            {{-- Messages de succès --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Fermer">
                    </button>
                </div>
            @endif

            {{-- Messages d'erreur --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Fermer">
                    </button>
                </div>
            @endif

            {{-- Erreurs de validation --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Une erreur est survenue :</strong>

                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Filtres de recherche --}}
            <div class="card border-0 bg-light mb-4">
                <div class="card-body">

                    <h6 class="mb-3">🔍 Filtrer les frais</h6>

                    <form method="GET"
                          action="{{ route('inscription-frais.index') }}">

                        <div class="row g-3">

                            {{-- Année --}}
                            <div class="col-md-3">
                                <label for="annee_id" class="form-label">
                                    Année scolaire
                                </label>

                                <select name="annee_id"
                                        id="annee_id"
                                        class="form-select">

                                    <option value="">
                                        Toutes les années
                                    </option>

                                    @foreach($annees as $annee)
                                        <option value="{{ $annee->id }}"
                                            {{ (string) request('annee_id') === (string) $annee->id ? 'selected' : '' }}>
                                            {{ $annee->nom }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            {{-- Cycle --}}
                            <div class="col-md-3">
                                <label for="cycle" class="form-label">
                                    Cycle
                                </label>

                                <select id="cycle"
                                        name="cycle_id"
                                        class="form-select">

                                    <option value="">
                                        Tous les cycles
                                    </option>

                                    @foreach($cycles as $cycle)
                                        <option value="{{ $cycle->id }}"
                                            {{ (string) request('cycle_id') === (string) $cycle->id ? 'selected' : '' }}>
                                            {{ $cycle->nom }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            {{-- Classe --}}
                            <div class="col-md-3">
                                <label for="classe" class="form-label">
                                    Classe
                                </label>

                                <select id="classe"
                                        name="classe_id"
                                        class="form-select">

                                    <option value="">
                                        Toutes les classes
                                    </option>

                                    {{-- Les classes seront chargées par JavaScript --}}

                                </select>
                            </div>

                            {{-- Élève --}}
                            <div class="col-md-3">
                                <label for="eleve_id" class="form-label">
                                    Élève
                                </label>

                                <select name="eleve_id"
                                        id="eleve_id"
                                        class="form-select">

                                    <option value="">
                                        Tous les élèves
                                    </option>

                                </select>
                            </div>

                        </div>

                        {{-- Boutons --}}
                        <div class="mt-4 d-flex justify-content-end gap-2">

                            <a href="{{ route('inscription-frais.index') }}"
                               class="btn btn-outline-secondary">
                                🔄 Réinitialiser
                            </a>

                            <button type="submit" class="btn btn-primary">
                                🔍 Rechercher
                            </button>

                        </div>

                    </form>

                </div>
            </div>

            {{-- Informations sur les résultats --}}
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">

                <div>
                    <span class="text-muted">
                        Nombre de résultats :
                    </span>

                    <strong>
                        {{ $inscriptionFrais->total() }}
                    </strong>
                </div>

            </div>

            {{-- Tableau des frais --}}
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Élève</th>
                            <th>Classe</th>
                            <th>Frais</th>
                            <th class="text-end">Montant</th>
                            <th class="text-end">Payé</th>
                            <th class="text-end">Reste</th>
                            <th class="text-center">Statut</th>
                            <th class="text-center" style="min-width: 150px;">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($inscriptionFrais as $item)

                            <tr>

                                {{-- Identifiant --}}
                                <td>
                                    {{ $item->id }}
                                </td>

                                {{-- Élève --}}
                                <td>
                                    <strong>
                                        {{ $item->inscription->eleve->nom ?? '' }}
                                        {{ $item->inscription->eleve->prenom ?? '' }}
                                    </strong>
                                </td>

                                {{-- Classe --}}
                                <td>
                                    {{ $item->inscription->classe->nom ?? '—' }}
                                </td>

                                {{-- Frais --}}
                                <td>
                                    {{ $item->frais->nom ?? '—' }}
                                </td>

                                {{-- Montant --}}
                                <td class="text-end text-nowrap">
                                    {{ number_format($item->montant_frais, 0, ',', ' ') }} F
                                </td>

                                {{-- Montant payé --}}
                                <td class="text-end text-nowrap text-success">
                                    {{ number_format($item->montant_paye, 0, ',', ' ') }} F
                                </td>

                                {{-- Reste --}}
                                <td class="text-end text-nowrap
                                    {{ $item->reste > 0 ? 'text-danger' : 'text-success' }}">

                                    {{ number_format($item->reste, 0, ',', ' ') }} F

                                </td>

                                {{-- Statut --}}
                                <td class="text-center">

                                    @if($item->statut === 'soldé')

                                        <span class="badge bg-success">
                                            Soldé
                                        </span>

                                    @elseif($item->statut === 'partiellement_payé')

                                        <span class="badge bg-warning text-dark">
                                            Partiellement payé
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Non payé
                                        </span>

                                    @endif

                                </td>

                                {{-- Actions --}}
                                <td class="text-center text-nowrap">

                                    {{-- Consulter --}}
                                    <a href="{{ route('inscription-frais.show', $item->id) }}"
                                       class="btn btn-sm btn-info text-white"
                                       title="Consulter">

                                        👁️

                                    </a>

                                    {{-- Modifier --}}
                                    <a href="{{ route('inscription-frais.edit', $item->id) }}"
                                       class="btn btn-sm btn-warning"
                                       title="Modifier">

                                        ✏️

                                    </a>

                                    {{-- Supprimer --}}
                                    <form action="{{ route('inscription-frais.destroy', $item->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Attention ! Voulez-vous vraiment supprimer ce frais associé à cette inscription ? Cette action est irréversible.');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Supprimer">

                                            🗑️

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="9"
                                    class="text-center text-muted py-4">

                                    <div class="fs-3 mb-2">
                                        📂
                                    </div>

                                    Aucun frais trouvé pour les critères sélectionnés.

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">

                <div class="text-muted small">
                    Affichage de
                    {{ $inscriptionFrais->firstItem() ?? 0 }}
                    à
                    {{ $inscriptionFrais->lastItem() ?? 0 }}
                    sur
                    {{ $inscriptionFrais->total() }}
                    résultat(s).
                </div>

                <div>
                    {{ $inscriptionFrais->withQueryString()->links() }}
                </div>

            </div>

        </div>
    </div>

</div>

{{-- Script de gestion des filtres --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const cycle = document.getElementById('cycle');
    const classe = document.getElementById('classe');
    const eleve = document.getElementById('eleve_id');

    const classeSelectionnee = @json(request('classe_id'));
    const eleveSelectionne = @json(request('eleve_id'));

    /**
     * Charger les classes d'un cycle.
     */
    async function chargerClasses(cycleId, classeId = '') {

        classe.innerHTML = '<option value="">Chargement...</option>';
        eleve.innerHTML = '<option value="">Tous les élèves</option>';

        if (!cycleId) {
            classe.innerHTML = '<option value="">Toutes les classes</option>';

            // Sans cycle sélectionné, conserver la possibilité de
            // sélectionner une classe parmi toutes les classes.
            return;
        }

        try {

            const response = await fetch(
                `/cycles/${encodeURIComponent(cycleId)}/classes`
            );

            if (!response.ok) {
                throw new Error('Erreur lors du chargement des classes.');
            }

            const data = await response.json();

            classe.innerHTML = '<option value="">Toutes les classes</option>';

            data.forEach(function (c) {

                const option = document.createElement('option');

                option.value = c.id;
                option.textContent = c.nom;

                if (String(c.id) === String(classeId)) {
                    option.selected = true;
                }

                classe.appendChild(option);

            });

            // Si une classe était sélectionnée, charger ses élèves.
            if (classeId) {
                await chargerEleves(classeId, eleveSelectionne);
            }

        } catch (error) {

            console.error(error);

            classe.innerHTML = '<option value="">Erreur de chargement</option>';

        }
    }

    /**
     * Charger les élèves d'une classe.
     */
    async function chargerEleves(classeId, eleveId = '') {

        eleve.innerHTML = '<option value="">Chargement...</option>';

        if (!classeId) {
            eleve.innerHTML = '<option value="">Tous les élèves</option>';
            return;
        }

        try {

            const response = await fetch(
                `/ajax/eleves-par-classe/${encodeURIComponent(classeId)}`
            );

            if (!response.ok) {
                throw new Error('Erreur lors du chargement des élèves.');
            }

            const data = await response.json();

            eleve.innerHTML = '<option value="">Tous les élèves</option>';

            data.forEach(function (e) {

                const option = document.createElement('option');

                option.value = e.id;
                option.textContent = `${e.nom ?? ''} ${e.prenom ?? ''}`.trim();

                if (String(e.id) === String(eleveId)) {
                    option.selected = true;
                }

                eleve.appendChild(option);

            });

        } catch (error) {

            console.error(error);

            eleve.innerHTML = '<option value="">Erreur de chargement</option>';

        }
    }

    /**
     * Changement de cycle.
     */
    cycle.addEventListener('change', function () {

        chargerClasses(this.value);

    });

    /**
     * Changement de classe.
     */
    classe.addEventListener('change', function () {

        chargerEleves(this.value);

    });

    /**
     * Restaurer les filtres après une recherche.
     */
    if (cycle.value) {

        chargerClasses(cycle.value, classeSelectionnee);

    } else {

        classe.innerHTML = '<option value="">Toutes les classes</option>';
        eleve.innerHTML = '<option value="">Tous les élèves</option>';

    }

});
</script>

@endsection