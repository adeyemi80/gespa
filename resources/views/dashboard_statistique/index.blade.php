@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">

    <h3>📊 Les Résultats Statistiques</h3>

    {{-- 📄 EXPORT PDF --}}
    @if($annee_id)
    <a href="{{ route('dashboard.statistique.pdf', [
        'annee_id' => $annee_id,
        'trimestre_id' => $trimestre_id,
        'classe_id' => $classe_id
    ]) }}" class="btn btn-danger mb-3">
        📄 Export PDF
    </a>
    @endif

    {{-- 🔎 FILTRES DYNAMIQUES --}}
    <form method="GET" class="row mb-4" id="filtresForm">
        {{-- ANNÉE --}}
        <div class="col-md-3">
            <label class="form-label">Année *</label>
            <select name="annee_id" id="annee_id" class="form-select" required>
                <option value="">-- Choisir année --</option>
                @foreach($annees as $annee)
                    <option value="{{ $annee->id }}"
                        {{ ($annee_id ?? '') == $annee->id ? 'selected' : '' }}>
                        {{ $annee->nom ?? 'Année '.$annee->id }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- TRIMESTRE --}}
        <div class="col-md-3">
            <label class="form-label">Trimestre</label>
            <select name="trimestre_id" id="trimestre_id" class="form-select">
                <option value="">-- Tous trimestres --</option>
                @foreach($trimestres as $trimestre)
                    <option value="{{ $trimestre->id }}"
                        {{ ($trimestre_id ?? '') == $trimestre->id ? 'selected' : '' }}>
                        {{ $trimestre->nom ?? 'T'.$trimestre->id }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- CLASSE CYCLE 3 (DYNAMIQUE) --}}
        <div class="col-md-3">
            <label class="form-label">Classe </label>
            <select name="classe_id" id="classe_id" class="form-select">
                <option value="">-- Attendre année --</option>
                @foreach($classes as $classe)
                    <option value="{{ $classe->id }}"
                        {{ ($classe_id ?? '') == $classe->id ? 'selected' : '' }}>
                        {{ $classe->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- BOUTON --}}
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100" id="btnFiltrer">
                🔍 Filtrer / Actualiser
            </button>
        </div>
    </form>

    {{-- ❌ CAS VIDE --}}
    @if(!$stats)
        <div class="alert alert-info">
            ⚠️ Sélectionnez une **année** pour voir les statistiques cycle 3
        </div>
    @else
        {{-- 📊 STATS --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card p-3 text-center bg-light">
                    <h6>👥 Effectif</h6>
                    <h3 class="text-primary">{{ $stats['effectif'] ?? 0 }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center bg-light">
                    <h6>📈 Moyenne générale</h6>
                    <h3 class="text-info">{{ number_format($stats['moyenne_generale'] ?? 0, 2) }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center bg-success text-white">
                    <h6>✅ Admis</h6>
                    <h3>{{ $stats['admis'] ?? 0 }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center bg-danger text-white">
                    <h6>❌ Échoués</h6>
                    <h3>{{ $stats['echoues'] ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <hr>

        {{-- 🏆 CLASSEMENT --}}
        <h4>🏆 Classement des élèves (Top)</h4>
        @if($topEleves->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Rang</th>
                        <th>Élève</th>
                        <th>Classe</th>
                        <th>Moyenne</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topEleves as $i => $ins)
                    <tr>
                        <td>
                            {{ $i + 1 }}
                            @if($i == 0) 🥇
                            @elseif($i == 1) 🥈
                            @elseif($i == 2) 🥉
                            @endif
                        </td>
                        <td>
                            {{ $ins->eleve->nom ?? '' }} {{ $ins->eleve->prenom ?? '' }}
                        </td>
                        <td>{{ $ins->classe->nom ?? '' }}</td>
                        <td class="{{ ($ins->moyenne ?? 0) >= 10 ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                            {{ number_format($ins->moyenne ?? 0, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="alert alert-warning">
                Aucun résultat pour ces filtres
            </div>
        @endif
    @endif
</div>

{{-- 🚀 JS DYNAMIQUE CYCLE 3 --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    // 📍 Trouve selects (ID ou name)
    const anneeSelect = document.getElementById('annee_id') || document.querySelector('select[name="annee_id"]');
    const classeSelect = document.getElementById('classe_id') || document.querySelector('select[name="classe_id"]');
    
    console.log('🎯 Selects trouvés:', !!anneeSelect, !!classeSelect);
    
    if (!anneeSelect || !classeSelect) {
        console.error('❌ Selects manquants ! Ajoutez id="annee_id" id="classe_id"');
        return;
    }

    // 🔄 Cycle3 loader
    anneeSelect.addEventListener('change', (e) => {
        const anneeId = e.target.value;
        console.log('📊 Année sélectionnée:', anneeId);
        
        if (!anneeId) {
            classeSelect.innerHTML = '<option value="">-- Année requise --</option>';
            return;
        }
        
        classeSelect.innerHTML = '<option value="">🔄 Chargement ...</option>';
        
        fetch(`/annees/${anneeId}/classes/cycle3`)
            .then(res => {
                console.log('📡 Status:', res.status);
                if (!res.ok) throw new Error(res.status);
                return res.json();
            })
            .then(classes => {
                console.table(classes);  // Debug table
                classeSelect.innerHTML = '<option value="">-- Choisir Une Classe  --</option>';
                classes.forEach(classe => {
                    const option = new Option(` ${classe.nom}`, classe.id);
                    classeSelect.appendChild(option);
                });
            })
            .catch(err => {
                console.error('💥 Erreur Cycle3:', err);
                classeSelect.innerHTML = '<option value="">❌ Erreur chargement</option>';
            });
    });
    
    // Trigger initial si année pré-sélectionnée
    if (anneeSelect.value) {
        anneeSelect.dispatchEvent(new Event('change'));
    }
    
    console.log('🚀 Cycle3 dynamique ACTIVÉ');
});
</script>
@endsection