@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>

<div class="container mt-4">

    <div class="card shadow-lg border-0">

        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">💸 Enregistrement d’un Paiement</h3>
        </div>

        <div class="card-body">

            {{-- Success --}}
            @if(session('success'))
                <div class="alert alert-success" id="success-msg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $selectedAnnee = old('annee_id') ?? $anneeEnCours->id ?? null;
            @endphp

            <form id="paiement-form" action="{{ route('paiements.store') }}" method="POST" class="row g-3">
                @csrf

                {{-- Année --}}
                <div class="col-md-6">
                    <label class="fw-bold small">📅 Année</label>
                    <select id="annee_id" name="annee_id"
                            class="form-select form-select-sm bg-light border-primary"
                            required>
          @foreach($annees as $annee)
    <option value="{{ $annee->id }}"
    {{ ($anneeEnCours?->id ?? '') == $annee->id ? 'selected' : '' }}>
    {{ $annee->nom }}
</option>
@endforeach

                    </select>
                </div>

                {{-- Cycle --}}
                <div class="col-md-6">
                    <label class="form-label">Cycle</label>
                    <select id="cycle" class="form-select">
                        <option value="">-- Choisir un cycle --</option>
                        @foreach($cycles as $cycle)
                            <option value="{{ $cycle->id }}">{{ $cycle->nom }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Classe --}}
                <div class="col-md-6">
                    <label class="form-label">Classe</label>
                    <select id="classe" name="classe_id" class="form-select" disabled>
                        <option value="">-- Sélectionnez une classe --</option>
                    </select>
                </div>

                {{-- Élève --}}
                <div class="col-md-6">
                    <label class="form-label">Élève</label>
                    <select id="inscription_id" name="inscription_id" class="form-select" disabled>
                        <option value="">-- Sélectionnez un élève --</option>
                    </select>
                </div>

                {{-- Frais --}}
                <div class="col-md-6">
                    <label class="form-label">📌 Frais</label>
                    <select id="frais_id" name="frais_id" class="form-select" disabled>
                        <option value="">-- Sélectionnez un frais --</option>
                    </select>
                </div>

                {{-- Montants --}}
                <div class="col-md-4">
                    <label class="form-label">Montant total</label>
                    <input type="text" id="montant_frais" class="form-control" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Déjà payé</label>
                    <input type="text" id="montant_paye" class="form-control" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Reste</label>
                    <input type="text" id="reste" class="form-control text-danger fw-bold" readonly>
                </div>

                {{-- Montant versé --}}
                <div class="col-md-6">
                    <label class="form-label">💰 Montant versé</label>
                    <input type="number" name="montant_verse" class="form-control" min="1" required>
                </div>

                {{-- Mode --}}
                <div class="col-md-6">
                    <label class="form-label">Mode de paiement</label>
                    <select name="mode_paiement" class="form-select" required>
                        <option value="">-- Choisir --</option>
                        <option value="Espèce">Espèce</option>
                        <option value="Mobile Money">Mobile Money</option>
                        <option value="Chèque">Chèque</option>
                        <option value="Virement">Virement</option>
                    </select>
                </div>

                {{-- Date --}}
                <div class="col-md-6">
                    <label class="form-label">Date</label>
                    <input type="date" name="date_paiement"
                           class="form-control"
                           value="{{ now()->toDateString() }}" required>
                </div>

                {{-- Actions --}}
                <div class="col-12 text-end">
                    <button id="btn-save" class="btn btn-success">
                        💾 Enregistrer
                    </button>
                    <a href="{{ route('paiements.index') }}" class="btn btn-secondary ms-2">
                        Annuler
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

const anneeEnCours = "{{ $anneeEnCours->id ?? '' }}";

// 🔹 Cycle → Classes
$('#cycle').change(function () {

    let cycleId = $(this).val();

    $('#classe').prop('disabled', true).html('<option>Chargement...</option>');
    $('#inscription_id').prop('disabled', true).html('<option>---</option>');
    $('#frais_id').prop('disabled', true).html('<option>---</option>');

    if (cycleId) {
        $.get(`/cycles/${cycleId}/classes`, function (data) {
            $('#classe').prop('disabled', false)
                .html('<option value="">-- Classe --</option>');

            data.forEach(c => {
                $('#classe').append(`<option value="${c.id}">${c.nom}</option>`);
            });
        });
    }
});

// 🔹 Classe → Inscriptions
$('#classe').change(function () {

    let classe = $(this).val();
    let annee = $('#annee_id').val();

    if (classe && annee) {
        $.get(`/classes/${classe}/inscriptions?annee_id=${annee}`, function (data) {

            $('#inscription_id').prop('disabled', false)
                .html('<option value="">-- Élève --</option>');

            data.forEach(i => {
                $('#inscription_id').append(
                    `<option value="${i.id}">${i.eleve.nom} ${i.eleve.prenom}</option>`
                );
            });
        });
    }
});

// 🔹 Inscription → Frais
$('#inscription_id').change(function () {

    let inscription = $(this).val();
    let annee = $('#annee_id').val();

    if (inscription && annee) {
        $.get(`/inscriptions/${inscription}/frais?annee_id=${annee}`, function (data) {

            $('#frais_id').prop('disabled', false)
                .html('<option value="">-- Frais --</option>');

            data.forEach(f => {

                let disabled = f.statut === 'soldé' ? 'disabled' : '';

                $('#frais_id').append(`
                    <option value="${f.frais_id}"
                        data-total="${f.montant_frais}"
                        data-paye="${f.montant_paye}"
                        data-reste="${f.reste}"
                        ${disabled}>
                        ${f.nom} - ${Number(f.reste).toLocaleString()} FCFA
                    </option>
                `);
            });
        });
    }
});

// 🔹 Frais → Montants
$('#frais_id').change(function () {

    let opt = $(this).find(':selected');

    $('#montant_frais').val(opt.data('total') || '');
    $('#montant_paye').val(opt.data('paye') || '');
    $('#reste').val(opt.data('reste') || '');

    $('#btn-save').prop('disabled', opt.prop('disabled'));
});

// 🔹 Submit AJAX
$('#paiement-form').submit(function (e) {

    e.preventDefault();

    let form = $(this);

    $.post(form.attr('action'), form.serialize())
        .done(function (data) {

            let w = window.open('', '_blank');
            w.document.write(data);
            w.document.close();

            form.trigger('reset');

            // 🔥 remettre l’année en cours après reset
            $('#annee_id').val(anneeEnCours);

            $('#classe, #inscription_id, #frais_id').prop('disabled', true);
        })
        .fail(function (xhr) {
            alert('Erreur: ' + xhr.responseText);
        });
});

</script>
@endsection
@endsection