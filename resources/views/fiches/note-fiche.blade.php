@extends('tableau.neutre')

@section('title', 'Génération fiche de notes')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="fw-bold mb-4">📄 Génération de la fiche de notes d'une matière d'une Classe</h4>

            <form method="POST" action="{{ route('fiches.generer') }}">
                @csrf

                <div class="row g-3">

                    {{-- ANNÉE --}}
                    <div class="col-md-3">
                        <label class="form-label">Année scolaire</label>
                        <select id="annee_id" name="annee_id" class="form-select" required>
                            <option value="">-- Sélectionner --</option>
                            @foreach($annees as $annee)
                                <option value="{{ $annee->id }}">
                                    {{ $annee->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TRIMESTRE --}}
                    <div class="col-md-3">
                        <label class="form-label">Trimestre</label>
                        <select id="trimestre_id" name="trimestre_id" class="form-select" required disabled>
                            <option value="">-- Sélectionner --</option>
                        </select>
                    </div>

                    {{-- CLASSE --}}
                    <div class="col-md-3">
                        <label class="form-label">Classe</label>
                        <select id="classe_id" name="classe_id" class="form-select" required disabled>
                            <option value="">-- Sélectionner --</option>
                        </select>
                    </div>

                    {{-- MATIÈRE --}}
                    <div class="col-md-3">
    <label class="form-label">Matière</label>
    <select id="matiere_id" name="matiere_id" class="form-control" required style="width:100%;">
        <option value="">-- Sélectionner --</option>
    </select>
</div>

                </div>

                <button id="btnGenerer" class="btn btn-primary mt-4" disabled>
                    Générer la fiche
                </button>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const annee     = document.getElementById('annee_id');
    const trimestre = document.getElementById('trimestre_id');
    const classe    = document.getElementById('classe_id');
    const matiere   = document.getElementById('matiere_id');
    const btn       = document.getElementById('btnGenerer');

    function resetSelect(select, placeholder = '-- Sélectionner --') {
        select.innerHTML = `<option value="">${placeholder}</option>`;
        select.disabled = true;
    }

    function enable(select) {
        select.disabled = false;
    }

    function loadOptions(select, url, logName) {
    // Chargement
    select.innerHTML = `<option value="">🔄 ${logName}...</option>`;
    
    fetch(url)
        .then(res => {
            console.log(`📡 ${logName} Status:`, res.status);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
        })
        .then(data => {
            console.log(`📚 ${logName} reçues:`, data);
            
            // CLEAR COMPLET (essentiel pour Bootstrap/Firefox)
            select.innerHTML = `<option value="">-- Sélectionner --</option>`;
            
            if (!Array.isArray(data) || data.length === 0) {
                const noOpt = document.createElement('option');
                noOpt.textContent = '❌ Aucune matière';
                noOpt.disabled = true;
                select.appendChild(noOpt);
                return;
            }
            
            // Ajout manuel (plus fiable que new Option)
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nom;
                select.appendChild(option);
            });
            
            console.log(`✅ ${logName}: ${data.length} options`);
            
            // FORCE RENDU (clé pour Bootstrap/Firefox)
            select.disabled = false;
            const event = new Event('change', { bubbles: true });
            select.dispatchEvent(event);
            select.focus();
        })
        .catch(err => {
            console.error(`💥 ${logName}:`, err);
            select.innerHTML = `<option value="">❌ Erreur réseau</option>`;
        });
}
    annee.addEventListener('change', () => {
        resetSelect(trimestre);
        resetSelect(classe);
        resetSelect(matiere);
        btn.disabled = true;
        if (!annee.value) return;

        loadOptions(trimestre, `/ajax/annees/${annee.value}/trimestres`, 'Trimestres');
        loadOptions(classe, `/ajax/annees/${annee.value}/classes`, 'Classes');
    });

    classe.addEventListener('change', () => {
        resetSelect(matiere);
        btn.disabled = true;
        if (!classe.value) return;
        loadOptions(matiere, `/ajax/classes/${classe.value}/matieres`, 'Matières');
    });

    matiere.addEventListener('change', () => {
        btn.disabled = !matiere.value;
    });
});
</script>
@endpush