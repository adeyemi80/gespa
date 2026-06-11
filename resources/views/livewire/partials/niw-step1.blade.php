<div class="card border-0 shadow-sm rounded-3">
  <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center gap-2">
    <i class="bi bi-sliders text-primary fs-5"></i>
    <span class="fw-semibold fs-5">Étape 1 — Paramètres</span>
  </div>
  <div class="card-body p-4">
    <div class="row g-3">

      {{-- CYCLE --}}
      <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary">Cycle</label>
        <select class="form-select rounded-2" wire:model.live="cycle_id">
          <option value="">— Choisir —</option>
          @foreach($cycles as $c)
            <option value="{{ $c->id }}">{{ $c->nom }}</option>
          @endforeach
        </select>
      </div>

      {{-- CLASSE --}}
      <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary">Classe</label>
        <select class="form-select rounded-2" wire:model.live="classe_id" @disabled(!$cycle_id)>
          <option value="">— Choisir —</option>
          @foreach($classes as $cl)
            <option value="{{ $cl->id }}">{{ $cl->nom }}</option>
          @endforeach
        </select>
      </div>

      {{-- ANNEE --}}
      <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary">Année scolaire</label>
        <select class="form-select rounded-2" wire:model.live="annee_id">
          @foreach($annees as $a)
            <option value="{{ $a->id }}">{{ $a->nom }}</option>
          @endforeach
        </select>
      </div>

      {{-- TRIMESTRE --}}
      <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary">Trimestre</label>
        <select class="form-select rounded-2" wire:model.live="trimestre_id">
          <option value="">— Choisir —</option>
          @foreach($trimestres as $t)
            <option value="{{ $t->id }}">{{ $t->nom }}</option>
          @endforeach
        </select>
      </div>

      {{-- MATIERE --}}
      <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary">Matière</label>
        <select class="form-select rounded-2" wire:model.live="matiere_id" @disabled(!$classe_id)>
          <option value="">— Choisir —</option>
          @foreach($matieres as $m)
            <option value="{{ $m->id }}">{{ $m->nom }}</option>
          @endforeach
        </select>
      </div>

      {{-- TYPES DE NOTES --}}
 <div class="d-flex flex-wrap gap-3">
  @foreach(['interrogation1','interrogation2','interrogation3','devoir1','devoir2'] as $t)
    <label class="d-flex align-items-center gap-3 px-4 py-2 rounded-pill border"
           style="cursor:pointer; font-size:1rem; white-space:nowrap">
      <input class="form-check-input m-0"
             type="checkbox"
             wire:model.live="types"
             value="{{ $t }}">
      {{ ucfirst(str_replace(['1','2','3'], [' 1',' 2',' 3'], $t)) }}
    </label>
  @endforeach
</div>
        @error('types') <span class="text-danger mt-1 d-block">{{ $message }}</span> @enderror
      </div>

    </div>

    {{-- ACTIONS --}}
    <div class="mt-4 pt-3 border-top d-flex gap-2">
      <button class="btn btn-outline-secondary rounded-2"
              wire:click="downloadTemplate"
              @disabled(!$annee_id || !$classe_id || !$trimestre_id)>
        <i class="bi bi-download me-1"></i> Télécharger le modèle
      </button>
      <button class="btn btn-primary rounded-2"
              wire:click="goToUpload"
              @disabled(!$annee_id || !$classe_id || !$trimestre_id || !$matiere_id || empty($types))>
        Suivant <i class="bi bi-arrow-right ms-1"></i>
      </button>
    </div>
  </div>
</div>