@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5" style="background-color:#f8f9fa; min-height:100vh;">

    {{-- TITRE --}}
    <h4 class="text-primary fw-bold mb-4">📑 Bulletins par Classe</h4>

    {{-- ALERTES --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- FORMULAIRE DE SÉLECTION --}}
   <div class="card mb-4 shadow-sm 
    @if(request()->has('annee_id') && request()->has('classe_id') && request()->has('trimestre_id')) 
        d-none 
    @endif">
        <div class="card-body">
            <form id="formBulletins" method="GET" class="row g-3">
                {{-- Année --}}
                <div class="col-md-4">
                    <label for="annee_id" class="form-label fw-bold">Année scolaire</label>
                    <select name="annee_id" id="annee_id" class="form-select" required>
                        <option value="">-- Sélectionner une année --</option>
                        @foreach($annees as $annee)
                            <option value="{{ $annee->id }}"
                                @if(request('annee_id') == $annee->id) selected @endif>
                                {{ $annee->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Classe --}}
                <div class="col-md-4">
                    <label for="classe_id" class="form-label fw-bold">Classe</label>
                    <select name="classe_id" id="classe_id" class="form-select" required>
                        <option value="">-- Sélectionner une classe --</option>
                        @if(isset($classes))
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}"
                                    @if(request('classe_id') == $classe->id) selected @endif>
                                    {{ $classe->nom }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Trimestre --}}
                <div class="col-md-4">
                    <label for="trimestre_id" class="form-label fw-bold">Trimestre</label>
                    <select name="trimestre_id" id="trimestre_id" class="form-select" required>
                        <option value="">-- Sélectionner un trimestre --</option>
                        @if(isset($trimestres))
                            @foreach($trimestres as $trimestre)
                                <option value="{{ $trimestre->id }}"
                                    @if(request('trimestre_id') == $trimestre->id) selected @endif>
                                    {{ $trimestre->nom }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Bouton --}}
                <div class="col-md-2 mt-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Afficher
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- BOUTON PDF --}}
    @if(isset($bulletins) && count($bulletins) > 0)
        <div class="mb-3 text-end">
            <a href="{{ route('bulletins.classe.pdf', [
                'classe_id' => $bulletins[0]['classe']->id,
                'trimestre_id' => $bulletins[0]['trimestre']->id,
                'annee_id' => $bulletins[0]['annee']->id
            ]) }}" class="btn btn-success">
                <i class="bi bi-file-earmark-pdf-fill"></i> Télécharger les bulletins PDF
            </a>
        </div>

        {{-- BULLETINS --}}
        @foreach($bulletins as $index => $data)
            @include('bulletins.templateClasse', $data)
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @endif
        @endforeach
    @elseif(request()->hasAny(['annee_id','classe_id','trimestre_id']))
        <div class="alert alert-warning mt-4">
            ⚠️ Aucun bulletin trouvé pour les critères sélectionnés.
        </div>
    @endif
</div>

{{-- SCRIPT AJAX pour classes et trimestres --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const anneeSelect = document.getElementById('annee_id');
    const classeSelect = document.getElementById('classe_id');
    const trimestreSelect = document.getElementById('trimestre_id');

    anneeSelect.addEventListener('change', function () {
        const anneeId = this.value;
        if (!anneeId) return;

        // ✅ CLASSES CYCLE 3 (votre méthode existante)
        classeSelect.innerHTML = '<option value="">Chargement...</option>';
        fetch(`/annees/${anneeId}/classes/cycle3`)
            .then(res => {
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                return res.json();
            })
            .then(data => {
                classeSelect.innerHTML = '<option value="">-- Choisir classe --</option>';
                if (data.length === 0) {
                    classeSelect.innerHTML += '<option disabled>Aucune classe </option>';
                    return;
                }
                data.forEach(c => {
                    const option = document.createElement('option');
                    option.value = c.id;
                    option.text = `${c.nom}`;
                    classeSelect.appendChild(option);
                });
            })
            .catch(err => {
                classeSelect.innerHTML = '<option value="">❌ Erreur cycle 3</option>';
                console.error('Classes cycle3 error:', err);
            });

        // Trimestres (inchangé - adaptez si besoin cycle3 aussi)
        trimestreSelect.innerHTML = '<option value="">Chargement...</option>';
        fetch(`/annees/${anneeId}/trimestres`)
            .then(res => res.json())
            .then(data => {
                trimestreSelect.innerHTML = '<option value="">-- Choisir trimestre --</option>';
                data.forEach(t => {
                    const option = document.createElement('option');
                    option.value = t.id;
                    option.text = t.nom;
                    trimestreSelect.appendChild(option);
                });
            }).catch(err => {
                console.error('Trimestres error:', err);
            });
    });
});
</script>
@endsection
