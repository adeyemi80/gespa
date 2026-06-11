<form method="POST" action="{{ $actionRoute }}">
    @csrf

    <div class="row g-3">

        {{-- ANNEE --}}
        <div class="col-md-3">
            <label class="form-label">Année</label>
            <select wire:model.live="annee_id" class="form-select">
                <option value="">-- Choisir --</option>
                @foreach($annees as $annee)
                    <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                @endforeach
            </select>
        </div>

        {{-- TRIMESTRE --}}
        <div class="col-md-3">
            <label class="form-label">Trimestre</label>
            <select wire:model.live="trimestre_id" class="form-select" @disabled(!$trimestres || !$annee_id)>
                <option value="">-- Choisir --</option>
                @foreach($trimestres as $trim)
                    <option value="{{ $trim->id }}">{{ $trim->nom }}</option>
                @endforeach
            </select>
        </div>

        {{-- CLASSE --}}
        <div class="col-md-3">
            <label class="form-label">Classe</label>
            <select wire:model.live="classe_id" class="form-select" @disabled(!$classes || !$annee_id)>
                <option value="">-- Choisir --</option>
                @foreach($classes as $classe)
                    <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                @endforeach
            </select>
        </div>

        {{-- MATIERE --}}
        <div class="col-md-3">
            <label class="form-label">Matière</label>
            <select wire:model.live="matiere_id" class="form-select" @disabled(!$matieres || !$classe_id)>
                <option value="">-- Choisir --</option>
                @foreach($matieres as $matiere)
                    <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                @endforeach
            </select>
        </div>

    </div>

    {{-- BOUTON CONFIGURABLE --}}
    <div class="mt-4">
        <button type="submit"
                class="btn btn-primary"
                @disabled(!$annee_id || !$trimestre_id || !$classe_id || !$matiere_id)>
            {{ $btnText }}
        </button>
    </div>

</form>