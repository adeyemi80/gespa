<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
        <span class="fw-semibold fs-5">
            <i class="bi bi-table me-2 text-primary"></i>
            Étape 3 — Aperçu ({{ collect($preview)->pluck('inscription_id')->unique()->count() }} élèves)
        </span>
        @if($invalid_count > 0)
            <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                ⚠️ {{ $invalid_count }} notes hors 0-20
            </span>
        @endif
    </div>

    <div class="card-body p-0">
        <div class="table-responsive" style="max-height:450px; overflow-y:auto">
            <table class="table table-bordered table-hover table-sm mb-0 align-middle">
                <thead class="table-primary sticky-top">
                    <tr>
                        <th class="px-3 py-2">Matricule</th>
                        <th class="px-3 py-2">Nom</th>
                        <th class="px-3 py-2">Prénom</th>
                        @foreach($types as $type)
                            <th class="px-3 py-2 text-center">
                                {{ ucfirst(str_replace(['1','2','3'], [' 1',' 2',' 3'], $type)) }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Regrouper les lignes par matricule
                        $grouped = collect($preview)->groupBy('matricule');
                    @endphp
                    @foreach($grouped as $matricule => $lignes)
                        @php
                            $premiLigne = $lignes->first();
                            $nomPrenom  = explode(' ', $premiLigne['eleve'], 2);
                            $hasInvalid = $lignes->contains('valid', false);
                        @endphp
                        <tr class="{{ $hasInvalid ? 'table-danger' : '' }}">
                            <td class="px-3">{{ $matricule }}</td>
                            <td class="px-3">{{ $nomPrenom[0] ?? '' }}</td>
                            <td class="px-3">{{ $nomPrenom[1] ?? '' }}</td>
                            @foreach($types as $type)
                                @php
                                    $ligne = $lignes->firstWhere('type', $type);
                                @endphp
                                <td class="px-3 text-center {{ $ligne && !$ligne['valid'] ? 'text-danger fw-bold' : '' }}">
                                    {{ $ligne ? $ligne['note'] : '—' }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer bg-white border-top py-3 px-4 d-flex gap-2">
        <button class="btn btn-outline-secondary rounded-2" wire:click="backToStep2">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </button>
        <button class="btn btn-success rounded-2" wire:click="sauvegarderNotes">
            <span wire:loading wire:target="sauvegarderNotes"
                  class="spinner-border spinner-border-sm me-1"></span>
            <i class="bi bi-check2-circle me-1"></i> Confirmer et sauvegarder
        </button>
    </div>
</div>