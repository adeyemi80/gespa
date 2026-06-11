@extends('tableau.neutre')

@section('title', 'Import des matières')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-4">

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            Importer un fichier Excel de matières
        </div>

        <div class="card-body">

            <form action="{{ route('matieres.preview') }}" method="POST" enctype="multipart/form-data">
                @csrf
 <p class="small">
                        Télécharger le modèle Excel après sélectionné(année + cycle)
                    </p>

                {{-- DOWNLOAD MODELE --}}
                <div class="mb-3">
                    <a href="#" id="download-modele" class="btn btn-info d-none" target="_blank">
                        📥 Télécharger le modèle Excel
                    </a>
                </div>
                {{-- ANNÉE --}}
                <table>
                    <tr>
                        <td>
                <div class="mb-3">
                    <label class="form-label">Année scolaire</label>
                    <select name="annee_id" id="annee_id" class="form-select" required>
                        <option value="">-- Sélectionnez une année --</option>
                        @foreach($annees as $annee)
                            <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                        @endforeach
                    </select></td> <td>
                   <p class="small">
                       Importer les matires d'une collator_sort_with_sort_keys après sélectionné(année + cycle + classe)
                    </p></td> </tr></table>
</div>
               

                <div class="row">

                    {{-- CYCLE --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">🎓 Cycle</label>
                        <select id="cycle_id" name="cycle_id" class="form-select" required>
                            <option value="">-- Choisir un cycle --</option>
                            @foreach($cycles as $cycle)
                                <option value="{{ $cycle->id }}">{{ $cycle->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- CLASSE --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Classe</label>
                        <select id="classe_id" name="classe_id" class="form-select" required>
                            <option value="">-- Choisir un cycle d'abord --</option>
                        </select>
                    </div>

                </div>

                {{-- FICHIER --}}
                <div class="mb-3">
                    <label class="form-label">Fichier Excel</label>
                    <input type="file" name="fichier_excel" class="form-control" accept=".xlsx,.xls" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Prévisualiser
                </button>

            </form>

        </div>
    </div>
</div>

{{-- ================= SCRIPT ================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const anneeSelect  = document.getElementById('annee_id');
    const cycleSelect  = document.getElementById('cycle_id');
    const classeSelect = document.getElementById('classe_id');
    const linkModele   = document.getElementById('download-modele');

    // ===============================
    // ANNÉE → MODELE DOWNLOAD
    // ===============================
    anneeSelect.addEventListener('change', function () {

        const anneeId = this.value;
        const cycleId = cycleSelect.value;

        if (anneeId && cycleId) {
            linkModele.href =
                "{{ route('matieres.modele.download') }}"
                + "?annee_id=" + anneeId
                + "&cycle_id=" + cycleId;

            linkModele.classList.remove('d-none');
        } else {
            linkModele.classList.add('d-none');
        }
    });

    // ===============================
    // CYCLE → CLASSES (FILTRAGE)
    // ===============================
    cycleSelect.addEventListener('change', function () {

        const cycleId = this.value;

        classeSelect.innerHTML = '<option value="">Chargement...</option>';

        if (!cycleId) {
            classeSelect.innerHTML = '<option value="">-- Choisir un cycle --</option>';
            return;
        }

        fetch(`/cycles/${cycleId}/classes`)
            .then(res => res.json())
            .then(data => {

                classeSelect.innerHTML = '<option value="">-- Choisir une classe --</option>';

                data.forEach(c => {
                    classeSelect.innerHTML += `
                        <option value="${c.id}">${c.nom}</option>
                    `;
                });

            })
            .catch(() => {
                classeSelect.innerHTML =
                    '<option value="">Erreur de chargement</option>';
            });
    });

    // ===============================
    // ANNÉE OU CYCLE → ACTIVER DOWNLOAD
    // ===============================
    function updateDownloadLink() {
        const anneeId = anneeSelect.value;
        const cycleId = cycleSelect.value;

        if (anneeId && cycleId) {
            linkModele.href =
                "{{ route('matieres.modele.download') }}"
                + "?annee_id=" + anneeId
                + "&cycle_id=" + cycleId;

            linkModele.classList.remove('d-none');
        } else {
            linkModele.classList.add('d-none');
        }
    }

    anneeSelect.addEventListener('change', updateDownloadLink);
    cycleSelect.addEventListener('change', updateDownloadLink);

});
</script>

@endsection