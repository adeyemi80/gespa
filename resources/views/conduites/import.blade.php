@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="bi bi-upload me-2"></i> 
                Importation des **Conduites**
            </h4>
        </div>

        <div class="card-body">
            {{-- Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('conduites.previsualiser') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-4">
                    {{-- Année --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">📅 **Année scolaire**</label>
                        <select id="annee_id" name="annee_id" class="form-select" required>
                            <option value="">-- Choisir une année --</option>
                            @foreach($annees as $annee)
                                <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Classe (pré-filtrée cycle 3) --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">🏫 **Classe** </label>
                        <select id="classe_id" name="classe_id" class="form-select" disabled required>
                            <option value="">-- Choisir d'abord l'année --</option>
                        </select>
                    </div>

                    {{-- Bouton modèle --}}
                    <div class="col-md-4 d-flex align-items-end">
                        <a id="btn-modele" href="#" class="btn btn-outline-success w-100 disabled">
                            <i class="bi bi-file-earmark-excel-fill me-2"></i>
                            📥 **Télécharger le modèle Excel**
                        </a>
                    </div>
                </div>

                <div class="row g-4 mt-3">
                    {{-- Trimestre --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">📘 **Trimestre**</label>
                        <select id="trimestre_id" name="trimestre_id" class="form-select" disabled required>
                            <option value="">-- Choisir l'année d'abord --</option>
                        </select>
                    </div>

                    {{-- Fichier Excel --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">📂 **Fichier Excel** (.xlsx )</label>
                        <input type="file" name="fichier" class="form-control" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text"></div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-upload me-2"></i> **Prévisualiser & Importer**
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const annee = document.getElementById('annee_id');
    const classe = document.getElementById('classe_id');
    const trimestre = document.getElementById('trimestre_id');
    const btnModele = document.getElementById('btn-modele');

    const CYCLE_ID = {{ $cycle_id ?? 3 }};  // ✅ Syntaxe Blade correcte

    // ===============================
    // 1. Année → Classes (cycle 3) + Trimestres
    // ===============================
    annee.addEventListener('change', () => {
        const anneeId = annee.value;

        // Reset
        classe.innerHTML = '<option value="">Chargement...</option>';
        trimestre.innerHTML = '<option value="">Chargement...</option>';
        classe.disabled = true;
        trimestre.disabled = true;
        btnModele.classList.add('disabled');

        if (!anneeId) return;

        // 🔹 Classes : API + filtre cycle_id=3 (double sécurité)
        fetch(`/annees/${anneeId}/classes/cycle3`)
            .then(r => r.json())
            .then(data => {
                // ✅ CORRIGÉ : Pas de Blade dans JS
                classe.innerHTML = '<option value="">-- Choisir une classe --</option>';

                const classesFiltrees = data.filter(c => c.cycle_id == CYCLE_ID);
                classesFiltrees
                    .sort((a, b) => a.nom.localeCompare(b.nom))
                    .forEach(c => {
                        classe.innerHTML += `<option value="${c.id}">${c.nom} </option>`;
                    });

                // Debug console (optionnel)
                console.log(`${classesFiltrees.length} classes cycle ${CYCLE_ID} pour année ${anneeId}`);

                classe.disabled = false;
            })
            .catch(err => {
                console.error('Erreur classes:', err);
                classe.innerHTML = '<option value="">Erreur chargement</option>';
            });

        // 🔹 Trimestres
        fetch(`/annees/${anneeId}/trimestres`)
            .then(r => r.json())
            .then(data => {
                trimestre.innerHTML = '<option value="">-- Choisir un trimestre --</option>';
                data.forEach(t => {
                    const periode = t.periode ? ` (${t.periode})` : '';
                    trimestre.innerHTML += `<option value="${t.id}">${t.nom}${periode}</option>`;
                });
                trimestre.disabled = false;
            })
            .catch(() => {
                trimestre.innerHTML = '<option value="">Erreur</option>';
            });
    });

    // ===============================
    // 2. Activation boutons
    // ===============================
    const toggleBtn = () => {
        const enabled = annee.value && classe.value && trimestre.value;
        btnModele.classList.toggle('disabled', !enabled);
    };

    classe.addEventListener('change', toggleBtn);
    trimestre.addEventListener('change', toggleBtn);

    // ===============================
    // 3. Télécharger modèle Excel
    // ===============================
    btnModele.addEventListener('click', e => {
        e.preventDefault();
        if (btnModele.classList.contains('disabled')) return;

        const params = new URLSearchParams({
            classe_id: classe.value,
            trimestre_id: trimestre.value,
            annee_id: annee.value,
            cycle_id: CYCLE_ID
        });
         window.location.href = `/conduites/template/${classe.value}/${trimestre.value}/${annee.value}`;
    });
});
</script>
@endsection