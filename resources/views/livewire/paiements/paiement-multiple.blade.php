<div class="paiement-view">

    {{-- ========================================================= --}}

    {{-- TICKET                                                   --}}

    {{-- ========================================================= --}}

    <a id="ticket-link"

       href="#"

       target="_blank"

       style="display:none">

    </a>

    <script>

        document.addEventListener('livewire:initialized', () => {

            Livewire.on('ouvrirTicket', ({ numeroLot }) => {

                const link = document.getElementById('ticket-link');

                link.href = `/paiements/ticket/${numeroLot}`;

                link.click();

            });

            Livewire.on('masquerSucces', () => {

                setTimeout(() => {

                    @this.set('successMessage', '');

                }, 10000);

            });

        });

    </script>



    {{-- ========================================================= --}}

    {{-- MESSAGE DE SUCCÈS                                        --}}

    {{-- ========================================================= --}}

    @if ($successMessage)

        <div class="alert alert-success alert-dismissible py-2">

            ✅ {{ $successMessage }}

            <button

                type="button"

                class="btn-close"

                wire:click="$set('successMessage', '')">

            </button>

        </div>

    @endif



    {{-- ========================================================= --}}

    {{-- ========================================================= --}}
    {{-- RÉIMPRESSION DU DERNIER REÇU                              --}}
    {{-- ========================================================= --}}
    @if ($numeroLotGenere)
        <div class="alert alert-info border-info d-flex flex-wrap align-items-center justify-content-between gap-2 py-2 mb-3">
            <div>
                <strong>🧾 Dernier reçu :</strong>
                <span class="font-monospace">{{ $numeroLotGenere }}</span>
            </div>

            <button
                type="button"
                class="btn btn-primary btn-sm"
                wire:click="imprimerRecu"
                wire:loading.attr="disabled"
                wire:target="imprimerRecu"
            >
                <span wire:loading.remove wire:target="imprimerRecu">
                    🖨️ Réimprimer le reçu
                </span>
                <span wire:loading wire:target="imprimerRecu">
                    <span class="spinner-border spinner-border-sm me-1"></span>
                    Impression...
                </span>
            </button>
        </div>
    @endif

    {{-- ERREURS                                                  --}}

    {{-- ========================================================= --}}

    @if ($errors->has('frais'))

        <div class="alert alert-danger py-2">

            ❌ {{ $errors->first('frais') }}

        </div>

    @endif



    {{-- ========================================================= --}}

    {{-- CARD PRINCIPALE                                           --}}

    {{-- ========================================================= --}}

    <div class="card shadow border-0 rounded-3">

        {{-- ===================================================== --}}

        {{-- EN-TÊTE                                                --}}

        {{-- ===================================================== --}}

        <div class="card-header bg-primary text-white p-3">

            <h5 class="mb-0 fw-bold">

                💰 Enregistrer un paiement multiple

            </h5>

        </div>



        {{-- ===================================================== --}}

        {{-- CORPS                                                  --}}

        {{-- ===================================================== --}}

        <div class="card-body p-3">



            {{-- ================================================= --}}

            {{-- ANNÉE / CYCLE / CLASSE                             --}}

            {{-- ================================================= --}}

            <div class="row g-2 mb-2">

                {{-- ANNÉE --}}

                <div class="col-md-4">

                    <label class="fw-bold small">

                        📅 Année

                    </label>

                    <select

                        wire:model.live="anneeId"

                        class="form-select form-select-sm bg-light border-primary"

                    >

                        @foreach ($annees as $annee)

                            <option value="{{ $annee->id }}">

                                {{ $annee->nom }}

                            </option>

                        @endforeach

                    </select>

                </div>



                {{-- CYCLE --}}

                <div class="col-md-4">

                    <label class="fw-bold small">

                        Cycle

                    </label>

                    <select

                        wire:model.live="cycleId"

                        class="form-select form-select-sm bg-light border-primary"

                    >

                        <option value="">

                            \-- Choisir --

                        </option>

                        @foreach ($cycles as $cycle)

                            <option value="{{ $cycle->id }}">

                                {{ $cycle->nom }}

                            </option>

                        @endforeach

                    </select>

                </div>



                {{-- CLASSE --}}

                <div class="col-md-4">

                    <label class="fw-bold small">

                        Classe

                    </label>

                    <select

                        wire:model.live="classeId"

                        class="form-select form-select-sm bg-light border-primary"

                    >

                        <option value="">

                            \-- Classe --

                        </option>

                        @foreach ($classes as $c)

                            <option value="{{ $c['id'] }}">

                                {{ $c['nom'] }}

                            </option>

                        @endforeach

                    </select>

                </div>

            </div>



            {{-- ================================================= --}}

            {{-- ÉLÈVE                                               --}}

            {{-- ================================================= --}}

            <div class="mb-3">

                <label class="fw-bold small">

                    Élève

                </label>

                <select

                    wire:model.live="inscriptionId"

                    class="form-select form-select-sm bg-light border-primary"

                >

                    <option value="">

                        \-- Élève --

                    </option>

                    @foreach ($inscriptions as $i)

                        <option value="{{ $i['id'] }}">

                            {{ $i['nom'] }}

                        </option>

                    @endforeach

                </select>

            </div>
{{-- ========================================================= --}}
{{-- ANCIENS REÇUS DE L'ÉLÈVE : OUVRIR / FERMER AU CLIC --}}
{{-- ========================================================= --}}

<div class="card border-0 shadow-sm rounded-4 mt-4 overflow-hidden">

    {{-- EN-TÊTE CLIQUABLE --}}
    <div
        class="card-header bg-white border-0 p-3"
        role="button"
        data-bs-toggle="collapse"
        data-bs-target="#anciensRecusCollapse"
        aria-expanded="false"
        aria-controls="anciensRecusCollapse"
        style="cursor: pointer;"
    >

        <div class="d-flex justify-content-between align-items-center gap-3">

            <div>
                <h5 class="mb-1">
                    <i class="bi bi-receipt me-2 text-primary"></i>
                    Anciens reçus de paiement
                </h5>

                <p class="text-muted small mb-0">
                    Consultez et réimprimez les reçus des années scolaires
                    précédentes et de l'année sélectionnée.
                </p>
            </div>

            {{-- Icône indiquant l'ouverture ou la fermeture --}}
            <i
                class="bi bi-chevron-down text-primary fs-5"
                id="iconeAnciensRecus"
            ></i>

        </div>

    </div>

    {{-- CONTENU REPLIABLE --}}
    <div class="collapse" id="anciensRecusCollapse">

        <div class="card-body">

            @error('recus')
                <div class="alert alert-danger">
                    {{ $message }}
                </div>
            @enderror

            @if (!$inscriptionId)

                <div class="alert alert-info mb-0">
                    Sélectionnez une année, un cycle, une classe
                    et un élève.
                </div>

            @elseif (empty($anciensRecus))

                <div class="alert alert-secondary mb-0">
                    Aucun reçu trouvé pour cet élève.
                </div>

            @else

                <div class="table-responsive">

                    <table class="table table-hover table-striped align-middle">

                        <thead class="table-light">
                            <tr>
                                <th>N° reçu</th>
                                <th>Date</th>
                                <th>Année scolaire</th>
                                <th>Classe</th>
                                <th>Frais</th>
                                <th>Mode de paiement</th>
                                <th class="text-end">Montant</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($anciensRecus as $recu)

                                <tr wire:key="ancien-recu-{{ md5($recu['numero_recu']) }}">

                                    <td>
                                        <span class="fw-bold">
                                            {{ $recu['numero_recu'] }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $recu['date'] }}
                                    </td>

                                    <td>
                                        {{ $recu['annee'] }}
                                    </td>

                                    <td>
                                        {{ $recu['classe'] }}
                                    </td>

                                    <td>
                                        {{ $recu['frais'] }}
                                    </td>

                                    <td>
                                        {{ $recu['mode_paiement'] }}
                                    </td>

                                    <td class="text-end fw-bold">
                                        {{ number_format($recu['total'], 0, ',', ' ') }}
                                        FCFA
                                    </td>

                                    <td class="text-center">

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            wire:click="reimprimerRecu(@js($recu['numero_recu']))"
                                            wire:loading.attr="disabled"
                                            wire:target="reimprimerRecu"
                                        >

                                            <span
                                                wire:loading.remove
                                                wire:target="reimprimerRecu"
                                            >
                                                <i class="bi bi-printer me-1"></i>
                                                Imprimer
                                            </span>

                                            <span
                                                wire:loading
                                                wire:target="reimprimerRecu"
                                            >
                                                <span class="spinner-border spinner-border-sm"></span>
                                                Impression...
                                            </span>

                                        </button>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                        <tfoot class="table-light">

                            <tr>
                                <th colspan="6">
                                    Nombre de reçus : {{ count($anciensRecus) }}
                                </th>

                                <th colspan="2" class="text-end">
                                    Total des reçus affichés :
                                    {{ number_format(
                                        collect($anciensRecus)->sum('total'),
                                        0,
                                        ',',
                                        ' '
                                    ) }}
                                    FCFA
                                </th>
                            </tr>

                        </tfoot>

                    </table>

                </div>

            @endif

        </div>

    </div>

</div>

{{-- ========================================================= --}}
{{-- GESTION DE L'ICÔNE OUVRIR / FERMER --}}
{{-- ========================================================= --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const contenu = document.getElementById('anciensRecusCollapse');
        const icone = document.getElementById('iconeAnciensRecus');

        if (contenu && icone) {
            contenu.addEventListener('show.bs.collapse', function () {
                icone.classList.remove('bi-chevron-down');
                icone.classList.add('bi-chevron-up');
            });

            contenu.addEventListener('hide.bs.collapse', function () {
                icone.classList.remove('bi-chevron-up');
                icone.classList.add('bi-chevron-down');
            });
        }
    });
</script>


            {{-- ================================================= --}}

            {{-- PAIEMENT DES ARRIÉRÉS                               --}}

            {{-- ================================================= --}}

            @if ($paiementArriere)

                <div class="card border-warning shadow-sm mb-3">

                    <div class="card-header bg-warning text-dark">

                        <strong>

                            💰 Règlement des arriérés

                        </strong>

                    </div>



                    <div class="card-body">



                        {{-- ANNÉE CONCERNÉE --}}

                        <div class="alert alert-warning py-2">

                            <strong>

                                Année concernée :

                            </strong>

                            {{ $annees->firstWhere(

                                'id',

                                $anneePrecedenteId

                            )?->nom ?? 'Année précédente' }}

                        </div>



                        {{-- ================================================= --}}

                        {{-- LISTE DES FRAIS EN ARRIÉRÉ                          --}}

                        {{-- ================================================= --}}

                        @if (count($fraisArrieres) > 0)

                            <div class="table-responsive">

                                <table class="table table-bordered table-sm align-middle">

                                    <thead class="table-light">

                                        <tr>

                                            <th>

                                                Frais

                                            </th>

                                            <th class="text-end">

                                                Montant dû

                                            </th>

                                            <th class="text-end">

                                                Déjà payé

                                            </th>

                                            <th class="text-end">

                                                Reste

                                            </th>

                                            <th style="width:180px">

                                                À payer

                                            </th>

                                        </tr>

                                    </thead>



                                    <tbody>

                                        @foreach ($fraisArrieres as $frais)

                                            <tr>

                                                <td>

                                                    <strong>

                                                        {{ $frais['nom'] }}

                                                    </strong>

                                                </td>



                                                <td class="text-end">

                                                    {{ number_format(

                                                        $frais['montant_frais'],

                                                        0,

                                                        ',',

                                                        ' '

                                                    ) }}

                                                    FCFA

                                                </td>



                                                <td class="text-end">

                                                    {{ number_format(

                                                        $frais['montant_paye'],

                                                        0,

                                                        ',',

                                                        ' '

                                                    ) }}

                                                    FCFA

                                                </td>



                                                <td class="text-end">

                                                    <strong class="text-danger">

                                                        {{ number_format(

                                                            $frais['reste'],

                                                            0,

                                                            ',',

                                                            ' '

                                                        ) }}

                                                        FCFA

                                                    </strong>

                                                </td>



                                                <td>

                                                    <input

                                                        type="number"

                                                        min="0"

                                                        max="{{ $frais['reste'] }}"

                                                        wire:model.live="montantsArrieres.{{ $frais['id'] }}"

                                                        class="form-control form-control-sm"

                                                    >

                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        @else

                            <div class="alert alert-success">

                                ✅ Aucun arriéré à payer.

                            </div>

                        @endif



                        {{-- ================================================= --}}

                        {{-- TOTAL ARRIÉRÉ                                      --}}

                        {{-- ================================================= --}}

                        <div class="alert alert-danger d-flex justify-content-between align-items-center">

                            <span>

                                <strong>

                                    Total restant :

                                </strong>

                            </span>

                            <strong>

                                {{ number_format(

                                    $totalArriere,

                                    0,

                                    ',',

                                    ' '

                                ) }}

                                FCFA

                            </strong>

                        </div>



                        {{-- ================================================= --}}

                        {{-- MODE DE PAIEMENT                                   --}}

                        {{-- ================================================= --}}

                        <div class="row g-2">

                            <div class="col-md-6">

                                <label class="fw-bold small">

                                    Mode de paiement

                                </label>

                                <select

                                    wire:model="modePaiement"

                                    class="form-select form-select-sm"

                                >

                                    <option value="">

                                        \-- Choisir --

                                    </option>

                                    <option value="Espèces">

                                        Espèces

                                    </option>

                                    <option value="Mobile Money">

                                        Mobile Money

                                    </option>

                                    <option value="Chèque">

                                        Chèque

                                    </option>

                                </select>

                                @error('modePaiement')

                                    <div class="text-danger small">

                                        {{ $message }}

                                    </div>

                                @enderror

                            </div>



                            <div class="col-md-6">

                                <label class="fw-bold small">

                                    Date de paiement

                                </label>

                                <input

                                    type="date"

                                    wire:model="datePaiement"

                                    class="form-control form-control-sm"

                                >

                                @error('datePaiement')

                                    <div class="text-danger small">

                                        {{ $message }}

                                    </div>

                                @enderror

                            </div>

                        </div>



                        {{-- ================================================= --}}

                        {{-- BOUTONS ARRIÉRÉS                                  --}}

                        {{-- ================================================= --}}

                        <div class="d-flex justify-content-end gap-2 mt-3">

                            <button

                                type="button"

                                class="btn btn-secondary btn-sm"

                                wire:click="$set('paiementArriere', false)"

                            >

                                Annuler

                            </button>



                            <button

                                type="button"

                                class="btn btn-success btn-sm"

                                wire:click="enregistrerPaiementArriere"

                                wire:loading.attr="disabled"

                                wire:target="enregistrerPaiementArriere"

                            >

                                <span

                                    wire:loading.remove

                                    wire:target="enregistrerPaiementArriere"

                                >

                                    💾 Enregistrer le paiement

                                </span>



                                <span

                                    wire:loading

                                    wire:target="enregistrerPaiementArriere"

                                >

                                    <span class="spinner-border spinner-border-sm">

                                    </span>

                                    Enregistrement...

                                </span>

                            </button>

                        </div>

                    </div>

                </div>

            @endif



            {{-- ================================================= --}}

            {{-- CONTRÔLE DES ARRIÉRÉS                              --}}

            {{-- ================================================= --}}

            @if (

                $inscriptionId

                && !$paiementArriere

                && $this->arriereSelectionne > 0

            )

                @php

                    $inscriptionSelectionnee = collect(

                        $inscriptions

                    )->firstWhere(

                        'id',

                        (int) $inscriptionId

                    );

                    $eleveIdSelectionne =

                        $inscriptionSelectionnee['eleve_id']

                        ?? null;

                @endphp



                <div class="alert alert-danger border-danger py-3 mb-3">

                    <div class="fw-bold text-danger mb-2">

                        ⚠️ Paiement

                        {{ $annees->firstWhere(

                            'id',

                            $anneeId

                        )?->nom ?? '' }}

                        bloqué

                    </div>



                    {{-- MESSAGE --}}

                    @if ($eleveIdSelectionne)

                        @php

                            $messageArrieres =

                                $this->messageArrieres(

                                    $eleveIdSelectionne

                                );

                        @endphp



                        @if ($messageArrieres)

                            <div class="small mb-2">

                                {{ $messageArrieres }}

                            </div>

                        @endif

                    @endif



                    {{-- MONTANT --}}

                    <div class="mb-3">

                        Montant restant :

                        <strong class="text-danger">

                            {{ number_format(

                                $this->arriereSelectionne,

                                0,

                                ',',

                                ' '

                            ) }}

                            FCFA

                        </strong>

                    </div>



                    {{-- ================================================= --}}

                    {{-- BOUTON DIRECT PAIEMENT ARRIÉRÉ                    --}}

                    {{-- ================================================= --}}

                    @if ($eleveIdSelectionne)

                        <button

                            type="button"

                            class="btn btn-warning btn-sm"

                            wire:click="ouvrirPaiementArriere({{ $eleveIdSelectionne }})"

                            wire:loading.attr="disabled"

                            wire:target="ouvrirPaiementArriere"

                        >

                            💰 Payer les arriérés

                        </button>

                    @endif

                </div>

            @endif

{{-- ================================================= --}}

{{-- FRAIS 2026-2027                                  --}}

{{-- ================================================= --}}

@if (

    !$paiementArriere

    && $inscriptionId

    && $this->arriereSelectionne <= 0

    && count($fraisDisponibles) > 0

)

    <div class="mb-4">

        {{-- TITRE --}}

        <div class="d-flex align-items-center justify-content-between mb-3">

            <label class="fw-bold fs-4 mb-0 text-dark">

                📚 Frais à payer

                <span class="badge bg-info fs-6 align-middle ms-2">

                    {{ count($fraisDisponibles) }}

                </span>

            </label>

            <span class="text-muted fw-semibold">

                Cliquez sur un frais pour le sélectionner

            </span>

        </div>



        {{-- LISTE DES FRAIS --}}

        <div class="frais-box border rounded-4 p-3 bg-light shadow-sm">

            @foreach ($fraisDisponibles as $i => $frais)

                <div

                    class="frais-item {{ !empty($frais['selectionne']) ? 'frais-selectionne' : '' }}"

                    wire:click="toggleFrais({{ $i }})"

                >

                    <div class="frais-check-zone">

                        <input

                            type="checkbox"

                            class="form-check-input frais-checkbox"

                            id="frais_{{ $i }}"

                            wire:click.stop="toggleFrais({{ $i }})"

                            @checked(!empty($frais['selectionne']))

                        >

                    </div>

                    <label

                        class="frais-label"

                        for="frais_{{ $i }}"

                    >

                        <div class="frais-nom">

                            {{ $frais['nom'] }}

                        </div>

                        <div class="frais-reste">

                            <span class="text-muted fw-semibold">

                                Reste à payer :

                            </span>

                            <strong class="text-danger">

                                {{ number_format($frais['reste'], 0, ',', ' ') }} FCFA

                            </strong>

                        </div>

                    </label>

                </div>

            @endforeach

        </div>

    </div>



    {{-- STYLE DU BLOC FRAIS --}}

    <style>

        .paiement-view .frais-box {

            padding: .45rem !important;

            border-radius: .75rem !important;

        }

        .paiement-view .frais-item {

            position: relative;

            display: flex;

            align-items: center;

            width: 92%;

            max-width: 850px;

            background: #fff;

            border: 2px solid #dee2e6;

            border-radius: .7rem;

            padding: .55rem .7rem;

            min-height: 58px;

            margin-bottom: .45rem !important;

            transition: .15s ease;

            cursor: pointer;

        }

        .paiement-view .frais-item:last-child {

            margin-bottom: 0 !important;

        }

        .paiement-view .frais-item:hover {

            border-color: #0d6efd;

            background: #f7fbff;

        }

        .paiement-view .frais-selectionne {

            border-color: #0d6efd;

            background: #eaf3ff;

            box-shadow: inset 4px 0 0 #0d6efd;

        }

        .paiement-view .frais-check-zone {

            width: 46px;

            min-width: 46px;

            align-self: stretch;

            display: flex;

            align-items: center;

            justify-content: center;

            border-right: 1px solid #dee2e6;

            margin-right: .75rem;

            padding-right: .55rem;

        }

        .paiement-view .frais-checkbox {

            width: 25px;

            height: 25px;

            min-width: 25px;

            margin: 0;

            cursor: pointer;

            border: 2px solid #6c757d;

        }

        .paiement-view .frais-checkbox:checked {

            background-color: #0d6efd;

            border-color: #0d6efd;

        }

        .paiement-view .frais-label {

            flex: 1;

            min-width: 0;

            cursor: pointer;

            margin: 0;

        }

        .paiement-view .frais-nom {

            font-size: 1.05rem;

            font-weight: 800;

            line-height: 1.2;

        }

        .paiement-view .frais-reste {

            margin-top: .18rem;

            font-size: .9rem;

        }

        @media (max-width: 768px) {

            .paiement-view .frais-item {

                width: 100%;

                max-width: none;

            }

            .paiement-view .frais-check-zone {

                width: 42px;

                min-width: 42px;

                margin-right: .6rem;

                padding-right: .45rem;

            }

        }

    </style>



@elseif (

    !$paiementArriere

    && $inscriptionId

    && $this->arriereSelectionne > 0

)

    {{-- Les frais 2026-2027 restent masqués tant que l'arriéré existe --}}



@elseif (

    !$paiementArriere

    && $inscriptionId

    && $aucunFraisAffecte

)

    <div class="alert alert-warning py-3 rounded-3 fs-5 fw-semibold">

        ⚠️ Aucun frais n'a été affecté à cette inscription.

    </div>



@elseif (

    !$paiementArriere

    && $inscriptionId

)

    <div class="alert alert-success py-3 rounded-3 fs-5 fw-semibold">

        ✅ Tous les frais de cet élève sont soldés.

    </div>

@endif



            {{-- ================================================= --}}

            {{-- RÉSUMÉ DES FRAIS 2026-2027                         --}}

            {{-- ================================================= --}}

            @if (

                !$paiementArriere

                && $this->arriereSelectionne <= 0

                && collect($fraisDisponibles)

                    ->where('selectionne', true)

                    ->isNotEmpty()

            )

                <div class="row g-2 bg-light p-2 rounded border mb-2">



                    {{-- TOTAL FRAIS --}}

                    <div class="col-md-4">

                        <input

                            class="form-control form-control-sm"

                            value="{{ number_format(

                                $totalFrais,

                                0,

                                ',',

                                ' '

                            ) }} FCFA"

                            placeholder="Total frais"

                            readonly

                        >

                    </div>



                    {{-- TOTAL PAYÉ --}}

                    <div class="col-md-4">

                        <input

                            class="form-control form-control-sm"

                            value="{{ number_format(

                                $totalPaye,

                                0,

                                ',',

                                ' '

                            ) }} FCFA"

                            placeholder="Total payé"

                            readonly

                        >

                    </div>



                    {{-- TOTAL RESTE --}}

                    <div class="col-md-4">

                        <input

                            class="form-control form-control-sm text-danger fw-bold"

                            value="{{ number_format(

                                $totalReste,

                                0,

                                ',',

                                ' '

                            ) }} FCFA"

                            placeholder="Reste"

                            readonly

                        >

                    </div>

                </div>



                {{-- ================================================= --}}

                {{-- MONTANTS PAR FRAIS                                 --}}

                {{-- ================================================= --}}

                <div class="mb-2">

                    <div class="bg-light p-2 rounded border">

                        @foreach ($fraisDisponibles as $i => $frais)

                            @if (!empty($frais['selectionne']))

                                <div class="mb-1 row g-1 align-items-center">



                                    {{-- NOM --}}

                                    <div class="col-md-7">

                                        <small class="fw-bold">

                                            {{ $frais['nom'] }}

                                        </small>

                                        <small class="text-muted">

                                            — Reste :

                                            {{ number_format(

                                                $frais['reste'],

                                                0,

                                                ',',

                                                ' '

                                            ) }}

                                            FCFA

                                        </small>

                                    </div>



                                    {{-- MONTANT --}}

                                    <div class="col-md-5">

                                        <input

                                            type="number"

                                            wire:model.live="montants.{{ $frais['frais_id'] }}"

                                            min="1"

                                            max="{{ $frais['reste'] }}"

                                            class="form-control form-control-sm

                                                {{ isset($montants[$frais['frais_id']])

                                                    && $montants[$frais['frais_id']] > $frais['reste']

                                                    ? 'is-invalid'

                                                    : '' }}"

                                            placeholder="Montant à payer"

                                        >



                                        @if (

                                            isset($montants[$frais['frais_id']])

                                            && $montants[$frais['frais_id']] > $frais['reste']

                                        )

                                            <div

                                                class="invalid-feedback"

                                                style="font-size:11px"

                                            >

                                                Maximum :

                                                {{ number_format(

                                                    $frais['reste'],

                                                    0,

                                                    ',',

                                                    ' '

                                                ) }}

                                                FCFA

                                            </div>

                                        @endif

                                    </div>

                                </div>

                            @endif

                        @endforeach



                        {{-- TOTAL À ENCAISSER --}}

                        <div

                            class="border-top mt-2 pt-2 d-flex justify-content-between"

                        >

                            <span class="fw-bold small">

                                Total à encaisser :

                            </span>

                            <span class="fw-bold text-success">

                                {{ number_format(

                                    $this->totalASaisir,

                                    0,

                                    ',',

                                    ' '

                                ) }}

                                FCFA

                            </span>

                        </div>

                    </div>

                </div>

            @endif



            {{-- ================================================= --}}

            {{-- MODE DE PAIEMENT 2026-2027                         --}}

            {{-- ================================================= --}}

            @if (

                !$paiementArriere

                && $inscriptionId

                && $this->arriereSelectionne <= 0

            )

                <div class="row g-2 mt-1">



                    {{-- MODE --}}

                    <div class="col-md-6">

                        <label class="fw-bold small">

                            Mode paiement

                        </label>

                        <select

                            wire:model="modePaiement"

                            class="form-select form-select-sm bg-light border-primary"

                        >

                            <option value="">

                                \-- Choisir --

                            </option>

                            <option value="Espèces">

                                Espèces

                            </option>

                            <option value="Mobile Money">

                                Mobile Money

                            </option>

                            <option value="Chèque">

                                Chèque

                            </option>

                        </select>



                        @error('modePaiement')

                            <div class="text-danger small">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>



                    {{-- DATE --}}

                    <div class="col-md-6">

                        <label class="fw-bold small">

                            Date

                        </label>

                        <input

                            type="date"

                            wire:model="datePaiement"

                            class="form-control form-control-sm border-primary"

                        >



                        @error('datePaiement')

                            <div class="text-danger small">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>

            @endif



            {{-- ================================================= --}}

            {{-- BOUTON D'ENREGISTREMENT 2026-2027                  --}}

            {{-- ================================================= --}}

            @if (

                !$paiementArriere

                && $inscriptionId

            )

                <div class="text-end mt-3">



                    {{-- ARRIÉRÉ --}}

                    @if ($this->arriereSelectionne > 0)

                        <button

                            type="button"

                            class="btn btn-secondary btn-sm"

                            disabled

                        >

                            🔒 Paiement bloqué — régler les arriérés

                        </button>



                    {{-- PAIEMENT NORMAL --}}

                    @else

                        <button

                            type="button"

                            wire:click="enregistrer"

                            wire:loading.attr="disabled"

                            wire:target="enregistrer"

                            class="btn btn-success btn-sm"

                        >

                            <span

                                wire:loading.remove

                                wire:target="enregistrer"

                            >

                                💾 Enregistrer et imprimer le reçu

                            </span>



                            <span

                                wire:loading

                                wire:target="enregistrer"

                            >

                                <span

                                    class="spinner-border spinner-border-sm me-1"

                                ></span>

                                Enregistrement...

                            </span>

                        </button>

                    @endif

                </div>

            @endif

        </div>

    </div>

</div>