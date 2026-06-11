<div>
    {{-- FILTRES --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <span class="fw-semibold fs-5">
                <i class="bi bi-funnel text-primary me-2"></i>Paramètres
            </span>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary">Année scolaire</label>
                    <select class="form-select" wire:model.live="annee_id">
                        @foreach($annees as $a)
                            <option value="{{ $a->id }}">{{ $a->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary">Classe</label>
                    <select class="form-select" wire:model.live="classe_id">
                        <option value="">— Choisir —</option>
                        @foreach($classes as $cl)
                            <option value="{{ $cl->id }}">{{ $cl->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary">Matière</label>
                    <select class="form-select" wire:model.live="matiere_id" @disabled(!$classe_id)>
                        <option value="">— Choisir —</option>
                        @foreach($matieres as $m)
                            <option value="{{ $m->id }}">{{ $m->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary">Trimestre</label>
                    <select class="form-select" wire:model.live="trimestre_id">
                        <option value="">— Choisir —</option>
                        @foreach($trimestres as $t)
                            <option value="{{ $t->id }}">{{ $t->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary rounded-2"
                        wire:click="chargerNotes"
                        @disabled(!$annee_id || !$classe_id || !$matiere_id || !$trimestre_id)>
                    <span wire:loading wire:target="chargerNotes"
                          class="spinner-border spinner-border-sm me-1"></span>
                    <i class="bi bi-table me-1"></i> Charger les élèves
                </button>
            </div>
        </div>
    </div>

    {{-- MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- TABLEAU DES NOTES --}}
    @if(!empty($notes))
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <span class="fw-semibold fs-5">
                <i class="bi bi-pencil-square text-primary me-2"></i>
                Saisie des notes ({{ count($notes) }} élèves)
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th class="px-3 py-2">Matricule</th>
                            <th class="px-3 py-2">Élève</th>
                            <th class="px-3 py-2 text-center">Interro 1</th>
                            <th class="px-3 py-2 text-center">Interro 2</th>
                            <th class="px-3 py-2 text-center">Interro 3</th>
                            <th class="px-3 py-2 text-center bg-info bg-opacity-25">Moy. Interro</th>
                            <th class="px-3 py-2 text-center">Devoir 1</th>
                            <th class="px-3 py-2 text-center">Devoir 2</th>
                            <th class="px-3 py-2 text-center bg-success bg-opacity-25">Moy. Matière</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notes as $inscriptionId => $note)
                        <tr>
                            <td class="px-3">{{ $note['matricule'] }}</td>
                            <td class="px-3">{{ $note['eleve'] }}</td>

                            {{-- INTERROGATIONS --}}
                            @foreach(['interrogation1','interrogation2','interrogation3'] as $champ)
                            <td class="px-2 text-center">
                                <input type="number"
                                       class="form-control form-control-sm text-center"
                                       style="width:70px; margin:auto"
                                       min="0" max="20" step="0.25"
                                       wire:model.live="notes.{{ $inscriptionId }}.{{ $champ }}"
                                       placeholder="—">
                            </td>
                            @endforeach

                            {{-- MOYENNE INTERRO --}}
                            <td class="px-3 text-center bg-info bg-opacity-10">
                                <span class="fw-semibold text-primary">
                                    {{ $note['moyenne_interro'] !== null ? number_format($note['moyenne_interro'], 2) : '—' }}
                                </span>
                            </td>

                            {{-- DEVOIRS --}}
                            @foreach(['devoir1','devoir2'] as $champ)
                            <td class="px-2 text-center">
                                <input type="number"
                                       class="form-control form-control-sm text-center"
                                       style="width:70px; margin:auto"
                                       min="0" max="20" step="0.25"
                                       wire:model.live="notes.{{ $inscriptionId }}.{{ $champ }}"
                                       placeholder="—">
                            </td>
                            @endforeach

                            {{-- MOYENNE MATIERE --}}
                            <td class="px-3 text-center bg-success bg-opacity-10">
                                <span class="fw-bold text-success fs-6">
                                    {{ $note['moyenne_matiere'] !== null ? number_format($note['moyenne_matiere'], 2) : '—' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-top py-3 px-4">
            <button class="btn btn-success rounded-2" wire:click="sauvegarder">
                <span wire:loading wire:target="sauvegarder"
                      class="spinner-border spinner-border-sm me-1"></span>
                <i class="bi bi-check2-circle me-1"></i> Sauvegarder toutes les notes
            </button>
        </div>
    </div>
    @endif
</div>