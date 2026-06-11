@extends('tableau.neutre')

@section('content')

<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>

<div class="container py-5" style="background-color: #f8f9fa;">

    <div class="card shadow-sm">

        <div class="card-header bg-warning text-dark">

            <h5 class="mb-0">
                ✏️ Modifier le Frais
            </h5>

        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ route('frais.update', $frais->id) }}">

                @csrf
                @method('PUT')

                {{-- DONNEES PRINCIPALES --}}
                <div class="row mb-3">

                    {{-- NOM --}}
                    <div class="mb-3">
    <label class="fw-bold small">Nom du frais</label>
    <input type="text"
           name="nom"
           value="{{ old('nom', $frais->nom ?? '') }}"
           class="form-control form-control-sm {{ $errors->has('nom') ? 'is-invalid' : '' }}">
    @error('nom')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                    {{-- MONTANT --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Montant total (FCFA)
                        </label>

                        @php
                            $montant = $frais->anneeClasseFrais
                                ->first()?->montant;
                        @endphp

                        <input type="number"
                               name="montant"
                               class="form-control"
                               value="{{ old('montant', $montant) }}"
                               required>

                    </div>

                </div>

                {{-- CLASSE + ANNEE --}}
                <div class="row mb-3">

                    {{-- CLASSE --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Classe
                        </label>

                        @php
                            $classeSelectionnee = $frais->anneeClasseFrais
                                ->first()?->classe_id;
                        @endphp

                        <select name="classe_id"
                                class="form-select"
                                required>

                            @foreach($classes as $classe)

                                <option value="{{ $classe->id }}"
                                    {{ $classeSelectionnee == $classe->id ? 'selected' : '' }}>

                                    {{ $classe->nom }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- ANNEE --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Année scolaire
                        </label>

                        @php
                            $anneeSelectionnee = $frais->anneeClasseFrais
                                ->first()?->annee_id;
                        @endphp

                        <select name="annee_id"
                                class="form-select"
                                required>

                            @foreach($annees as $annee)

                                <option value="{{ $annee->id }}"
                                    {{ $anneeSelectionnee == $annee->id ? 'selected' : '' }}>

                                    {{ $annee->nom }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                {{-- DESCRIPTION --}}
                <div class="mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <input type="text"
                           name="description"
                           class="form-control"
                           value="{{ old('description', $frais->description) }}">

                </div>

                <hr>

                {{-- ECHEANCES --}}
                <h6 class="text-primary">

                    📆 Échéances

                </h6>

                <div id="echeances-container">

                    @foreach($frais->echeances as $i => $echeance)

                        <div class="row mb-3 echeance-item">

                            {{-- LIBELLE --}}
                            <div class="col-md-4">

                                <input type="text"
                                       name="echeances[{{ $i }}][libelle]"
                                       class="form-control"
                                       value="{{ $echeance->nom }}"
                                       required>

                            </div>

                            {{-- MONTANT --}}
                            <div class="col-md-4">

                                <input type="number"
                                       name="echeances[{{ $i }}][montant]"
                                       class="form-control"
                                       value="{{ $echeance->montant }}"
                                       required>

                            </div>

                            {{-- DATE --}}
                            <div class="col-md-4">

                                <input type="date"
                                       name="echeances[{{ $i }}][date_limite]"
                                       class="form-control"
                                       value="{{ $echeance->date_limite }}"
                                       required>

                            </div>

                        </div>

                    @endforeach

                </div>

                {{-- AJOUT --}}
                <div class="mb-3">

                    <button type="button"
                            class="btn btn-outline-success"
                            id="add-echeance">

                        ➕ Ajouter une tranche

                    </button>

                </div>

                {{-- BOUTONS --}}
                <div class="text-end">

                    <button type="submit"
                            class="btn btn-primary">

                        💾 Mettre à jour

                    </button>

                    <a href="{{ route('frais.index') }}"
                       class="btn btn-secondary">

                        Annuler

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- SCRIPT --}}
<script>

let index = {{ $frais->echeances->count() }};

document.getElementById('add-echeance')
    .addEventListener('click', function () {

    const container =
        document.getElementById('echeances-container');

    const row = document.createElement('div');

    row.classList.add(
        'row',
        'mb-3',
        'echeance-item'
    );

    row.innerHTML = `

        <div class="col-md-4">

            <input type="text"
                   name="echeances[${index}][libelle]"
                   class="form-control"
                   required>

        </div>

        <div class="col-md-4">

            <input type="number"
                   name="echeances[${index}][montant]"
                   class="form-control"
                   required>

        </div>

        <div class="col-md-4">

            <input type="date"
                   name="echeances[${index}][date_limite]"
                   class="form-control"
                   required>

        </div>

    `;

    container.appendChild(row);

    index++;
});

</script>

@endsection