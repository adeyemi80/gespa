@extends('tableau.neutre')

@section('title', 'Enregistrer un paiement')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-3"> {{-- réduit --}}
    <div class="card shadow border-0 rounded-3">

        <div class="card-header bg-primary text-white p-3">
            <h5 class="mb-0 fw-bold">💰 Enregistrer un paiement</h5>
        </div>

        <div class="card-body p-3">

            {{-- MESSAGE SUCCESS --}}
            <div id="success-message" class="alert alert-success d-none py-2"></div>

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

            <form id="paiement-form" action="{{ route('paiements.storeUP') }}" method="POST">
                @csrf

                {{-- ANNÉE / CYCLE / CLASSE --}}
                <div class="row g-2 mb-2">

                    <div class="col-md-4">
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

                    <div class="col-md-4">
                        <label class="fw-bold small">Cycle</label>
                        <select id="cycle"
                                class="form-select form-select-sm bg-light border-primary">
                            <option value="">-- Choisir --</option>
                            @foreach($cycles as $cycle)
                                <option value="{{ $cycle->id }}">{{ $cycle->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="fw-bold small">Classe</label>
                        <select id="classe" name="classe_id"
                                class="form-select form-select-sm bg-light border-primary"
                                required>
                            <option value="">-- Classe --</option>
                        </select>
                    </div>

                </div>

                {{-- ÉLÈVE --}}
                <div class="mb-2">
                    <label class="fw-bold small">Élève</label>
                    <select id="inscription_id" name="inscription_id"
                            class="form-select form-select-sm bg-light border-primary"
                            required>
                        <option value="">-- Élève --</option>
                    </select>
                </div>

                {{-- FRAIS --}}
                <div class="mb-3">
                    <label class="fw-bold small">
                        📚 Frais
                        <span id="fraisBadge" class="badge bg-info">0</span>
                    </label>

                    <select id="frais_ids" name="frais_ids[]"
                            class="form-select form-select-sm border-success"
                            multiple size="5"
                            required>
                    </select>
                </div>

                {{-- RÉSUMÉ --}}
                <div id="infosFrais"
                     class="row g-2 bg-light p-2 rounded border mb-2 d-none">

                    <div class="col-md-4">
                        <input id="total_frais"
                               class="form-control form-control-sm"
                               placeholder="Total frais" readonly>
                    </div>

                    <div class="col-md-4">
                        <input id="total_paye"
                               class="form-control form-control-sm"
                               placeholder="Total payé" readonly>
                    </div>

                    <div class="col-md-4">
                        <input id="total_reste"
                               class="form-control form-control-sm text-danger fw-bold"
                               placeholder="Reste" readonly>
                    </div>
                </div>

                {{-- MONTANTS --}}
               <div class="mb-2">
    <div id="montants-container"
         class="bg-light p-2 rounded border">
    </div>
</div>

                {{-- MODE --}}
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="fw-bold small">Mode paiement</label>
                        <select name="mode_paiement"
                                class="form-select form-select-sm bg-light border-primary"
                                required>
                            <option value="">-- Choisir --</option>
                            <option value="Espèces">Espèces</option>
                            <option value="Mobile Money">Mobile Money</option>
                            <option value="Chèque">Chèque</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-bold small">Date</label>
                        <input type="date" name="date_paiement"
                               value="{{ date('Y-m-d') }}"
                               class="form-control form-control-sm border-primary"
                               required>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-success btn-sm">
                        💾 Enregistrer
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>

// SUCCESS MESSAGE
function showSuccess(message) {
    let msg = document.getElementById('success-message');
    msg.textContent = message;
    msg.classList.remove('d-none');

    setTimeout(() => {
        msg.classList.add('d-none');
    }, 3000);
}

// DOM
const annee = document.getElementById('annee_id');
const cycle = document.getElementById('cycle');
const classe = document.getElementById('classe');
const eleve = document.getElementById('inscription_id');
const frais = document.getElementById('frais_ids');
const container = document.getElementById('montants-container');
const infos = document.getElementById('infosFrais');
const badge = document.getElementById('fraisBadge');

// RESET
function resetAll() {
    classe.innerHTML = '<option value="">-- Classe --</option>';
    eleve.innerHTML = '<option value="">-- Élève --</option>';
    frais.innerHTML = '';
    container.innerHTML = '';
    infos.classList.add('d-none');
    badge.textContent = 0;
}

// CYCLE → CLASSE
cycle.addEventListener('change', function () {

    resetAll();

    if (!this.value) return;

    fetch(`/cycles/${this.value}/classes`)
        .then(r => r.json())
        .then(data => {

            data.forEach(c => {
                classe.innerHTML += `<option value="${c.id}">${c.nom}</option>`;
            });
        });
});


// CLASSE → ÉLÈVES
classe.addEventListener('change', function () {

    if (!this.value || !annee.value) return;

    eleve.innerHTML = '<option>Chargement...</option>';

    fetch(`/classes/${this.value}/inscriptions?annee_id=${annee.value}`)
        .then(r => r.json())
        .then(data => {

            // 🔥 TRI ALPHABÉTIQUE GARANTI
            data.sort((a, b) => {
                let nomA = (a.eleve?.nom || '').toLowerCase();
                let nomB = (b.eleve?.nom || '').toLowerCase();

                if (nomA < nomB) return -1;
                if (nomA > nomB) return 1;

                // si noms identiques → prénom
                let preA = (a.eleve?.prenom || '').toLowerCase();
                let preB = (b.eleve?.prenom || '').toLowerCase();

                if (preA < preB) return -1;
                if (preA > preB) return 1;

                return 0;
            });

            eleve.innerHTML = '<option value="">-- Élève --</option>';

            data.forEach(i => {
                eleve.innerHTML += `
                    <option value="${i.id}">
                        ${i.eleve?.nom ?? ''} ${i.eleve?.prenom ?? ''}
                    </option>
                `;
            });
        });
});


// ÉLÈVE → FRAIS
eleve.addEventListener('change', function () {

    if (!this.value || !annee.value) return;

    frais.innerHTML = '';

    fetch(`/inscriptions/${this.value}/frais?annee_id=${annee.value}`)
        .then(r => r.json())
        .then(data => {

            let count = 0;

            data.forEach(f => {

                let reste = parseFloat(f.reste) || 0;

                if (reste > 0) {

                    let opt = new Option(
                        `${f.description} (${reste.toLocaleString()} FCFA)`,
                        f.frais_id
                    );

                    opt.dataset.montant = f.montant_frais;
                    opt.dataset.paye = f.montant_paye;
                    opt.dataset.reste = reste;

                    frais.appendChild(opt);
                    count++;
                }
            });

            badge.textContent = count;
        });
});


// FRAIS → CALCUL
frais.addEventListener('change', function () {

    container.innerHTML = '';

    let totalF = 0, totalP = 0, totalR = 0;

    Array.from(this.selectedOptions).forEach(opt => {

        let m = parseFloat(opt.dataset.montant);
        let p = parseFloat(opt.dataset.paye);
        let r = parseFloat(opt.dataset.reste);

        totalF += m;
        totalP += p;
        totalR += r;

        container.innerHTML += `
            <div class="mb-1">
                <small>${opt.text}</small>
                <input type="number" name="montants[]" value="${r}"
                       class="form-control form-control-sm">
            </div>
        `;
    });

    document.getElementById('total_frais').value = totalF;
    document.getElementById('total_paye').value = totalP;
    document.getElementById('total_reste').value = totalR;

    infos.classList.remove('d-none');
});


// SUBMIT AJAX
document.getElementById('paiement-form').addEventListener('submit', function(e) {

    e.preventDefault();

    let formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {

            window.open(data.redirect, '_blank');

            this.reset();
            resetAll();

            showSuccess(data.message);
        }
    });
});

</script>

@endsection