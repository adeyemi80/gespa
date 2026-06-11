<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <div class="row">
            <!-- Date -->
            <div class="col-md-6 mb-3">
                <label for="date" class="form-label">📅 Date</label>
                <div class="input-group">
                    <input type="date" class="form-control @error('date') is-invalid @enderror"
                           id="date" name="date" value="{{ old('date') ?? now()->toDateString() }}" required>
        @error('date') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                @error('date') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <!-- Libellé -->
            <div class="col-md-6 mb-3">
                <label for="libelle" class="form-label">📝 Libellé</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-pencil"></i></span>
                    <input type="text" name="libelle" id="libelle" class="form-control"
                           value="{{ old('libelle', $recette->libelle ?? '') }}" required>
                </div>
                @error('libelle') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <!-- Montant -->
            <div class="col-md-6 mb-3">
                <label for="montant" class="form-label">💰 Montant</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-cash-stack"></i></span>
                    <input type="number" step="0.01" name="montant" id="montant" class="form-control"
                           value="{{ old('montant', $recette->montant ?? '') }}" required>
                    <span class="input-group-text">FCFA</span>
                </div>
                @error('montant') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>
    </div>
</div>
