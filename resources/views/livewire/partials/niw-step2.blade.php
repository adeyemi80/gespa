<div class="card shadow-sm">
    <div class="card-header fw-bold">
        <i class="bi bi-upload me-1"></i> Étape 2 — Upload du fichier
    </div>
    <div class="card-body">

        <div class="mb-3">
            <label class="form-label">Fichier Excel (.xlsx) ou CSV</label>
            <input type="file"
                   class="form-control @error('fichier') is-invalid @enderror"
                   wire:model="fichier"
                   accept=".xlsx,.csv">
            @error('fichier')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- PROGRESS UPLOAD --}}
        <div wire:loading wire:target="fichier" class="text-muted mb-2">
            <div class="spinner-border spinner-border-sm me-1"></div> Chargement...
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" wire:click="backToStep1">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </button>

            <button class="btn btn-primary"
                    wire:click="previewFichier"
                    @disabled(!$fichier)>
                <span wire:loading wire:target="previewFichier"
                      class="spinner-border spinner-border-sm me-1"></span>
                Aperçu <i class="bi bi-eye ms-1"></i>
            </button>
        </div>
    </div>
</div>