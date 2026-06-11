<div class="container py-5">
    {{-- MESSAGES --}}
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session()->has('error'))
        <div 
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 15000)"
            x-show="show"
            x-transition.opacity
            class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 🔥 ALERT CONFIRMATION CORRIGÉE --}}
    @if (session()->has('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {!! session('warning') !!}
            
            <div class="mt-3">
                {{-- ✅ CORRIGÉ : confirmeRemplacement --}}
                <button wire:click="confirmerRemplacement" 
                        wire:loading.attr="disabled"
                        class="btn btn-danger btn-sm">
                    <span wire:loading.remove>✅ Oui, écraser</span>
                    <span wire:loading>⏳ Confirmation...</span>
                </button>
                <button wire:click="cancelImport" class="btn btn-secondary btn-sm ms-2">
                    ❌ Annuler
                </button>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            📥 IMPORTATION DES NOTES
        </div>

        <div class="card-body">
            <div class="row g-3">
                {{-- ANNÉE --}}
                <div class="col-md-3">
                    <label class="fw-bold">Année</label>
                    <select wire:model.live="annee_id" class="form-select">
                        <option value="">-- Choisir Une Année --</option>
                        @foreach($annees as $a)
                            <option value="{{ $a->id }}">{{ $a->nom }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- CLASSE --}}
                <div class="col-md-3">
                    <label class="fw-bold">Classe</label>
                    <select wire:model.live="classe_id" class="form-select" @disabled(empty($classes))>
                        <option value="">-- Choisir une classe --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->nom }}</option>
                        @endforeach
                    </select>
                    @if(empty($classes))
                        <small class="text-muted">⚠️ Aucune classe disponible</small>
                    @endif
                </div>

                {{-- TRIMESTRE --}}
                <div class="col-md-3">
                    <label class="fw-bold">Trimestre</label>
                    <select wire:model.live="trimestre_id" class="form-select" @disabled(!$annee_id)>
                        <option value="">-- Choisir --</option>
                        @foreach($trimestres as $t)
                            <option value="{{ $t->id }}">{{ $t->nom }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- MATIÈRE --}}
                <div class="col-md-3">
                    <label class="fw-bold">📚 Matière</label>
                    <select wire:model.live="matiere_id" class="form-select" @disabled(!$classe_id)>
                        <option value="">-- Choisir --</option>
                        @foreach($matieres as $m)
                            <option value="{{ $m->id }}">{{ $m->nom }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- FICHIER --}}
                <div class="col-12">
                    <label class="fw-bold">Fichier Excel</label>
                    <input type="file" wire:model="fichier" accept=".xlsx" class="form-control">
                    
                   @if($fichierName)
    <div class="d-flex align-items-center gap-2 mt-1">
        <small class="text-success">
            📎 {{ $fichierName }}
        </small>

        <button type="button"
                wire:click="viderFichier"
                class="btn btn-sm btn-danger">
            Retirer
        </button>
    </div>
@endif
                    
                    @if($feuilleSelectionnee)
                        <div class="text-success small mt-1">
                            📄 Feuille : <strong>{{ $feuilleSelectionnee }}</strong>
                        </div>
                    @endif
                    
                    @error('fichier') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                {{-- BOUTONS --}}
                <div class="col-md-6">
                    <button wire:click="previewFile" 
                            wire:loading.attr="disabled"
                            class="btn btn-primary w-100"
                            @disabled(!$annee_id || !$classe_id || !$trimestre_id || !$matiere_id || !$fichier)>
                        <span wire:loading.remove wire:target="previewFile">📊 Prévisualiser</span>
                        <span wire:loading wire:target="previewFile">⏳ Chargement...</span>
                    </button>
                </div>

                <div class="col-md-6">
                    <p class="small text-muted mb-1">Télécharger le modèle</p>
                    <button wire:click="downloadTemplate" 
                            class="btn btn-outline-success w-100"
                            @disabled(!$annee_id || !$classe_id || !$trimestre_id)>
                        ⬇️ Modèle Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- PREVIEW --}}
    @if(!empty($preview))
        <div class="card mt-4 shadow">
            <div class="card-header bg-info text-white d-flex justify-content-between">
                <span>📋 Prévisualisation ({{ count($preview) }} lignes)</span>
                <span class="badge bg-light text-dark">{{ $feuilleSelectionnee ?? 'N/A' }}</span>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 500px;">
                    <table class="table table-sm mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th><th>Matricule</th><th>Nom</th><th>Prénom</th>
                                <th>Interro</th><th>Devoir1</th><th>Devoir2</th><th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($preview as $i => $row)
                                @php 
                                    $data = $row['data'] ?? []; 
                                    $errors = $row['errors'] ?? []; 
                                    $nbNotes = $row['nbNotes'] ?? 0;
                                @endphp
                                <tr class="{{ count($errors) ? 'table-danger' : ($nbNotes ? 'table-success' : 'table-secondary') }}">
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $data['Matricule'] ?? '-' }}</td>
                                    <td>{{ $data['Nom'] ?? '-' }}</td>
                                    <td>{{ $data['Prénom'] ?? '-' }}</td>
                                    <td>{{ $data['Moyenne Interrogation'] ?? '-' }}</td>
                                    <td>{{ $data['Devoir1'] ?? '-' }}</td>
                                    <td>{{ $data['Devoir2'] ?? '-' }}</td>
                                    <td>
                                        @if(count($errors))
                                            <span class="badge bg-danger">Erreur</span>
                                        @elseif($nbNotes == 0)
                                            <span class="badge bg-secondary">Sans note</span>
                                        @else
                                            <span class="badge bg-success">{{ $nbNotes }}/3</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- IMPORT --}}
                @php
                    $erreurs = collect($preview)->where('errors', '!=', [])->count();
                    $aImporter = collect($preview)->where('nbNotes', '>', 0)->count();
                @endphp

                <div class="p-3 border-top">
                    @if($erreurs > 0)
                        <div class="alert alert-danger">
                            ❌ {{ $erreurs }} erreur(s) - Corrigez avant import
                        </div>
                    @else
                        <button wire:click="importer"
                                wire:loading.attr="disabled"
                                class="btn btn-success w-100 btn-lg">
                            <span wire:loading.remove>🚀 Importer {{ $aImporter }} notes</span>
                            <span wire:loading>⏳ Importation en cours...</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>