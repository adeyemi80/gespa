<div class="container py-5">

    {{-- MESSAGES --}}
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            📥 Importation des Notes
        </div>

        <div class="card-body">
            <div class="row g-3">

                {{-- ANNÉE --}}
                <div class="col-md-3">
                    <label>Année</label>
                    <select wire:model.live="annee_id" class="form-select">
                        <option value="">-- Choisir Une Année --</option>
                        @foreach($annees as $a)
                            <option value="{{ $a->id }}">{{ $a->nom }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- CYCLE --}}
                <div class="col-md-3">
                    <label>Cycle</label>
                    <select wire:model.live="cycle_id" class="form-select">
                        <option value="">-- Choisir un cycle --</option>
                        @foreach($cycles as $cycle)
                            <option value="{{ $cycle->id }}">{{ $cycle->nom }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- CLASSE --}}
                <div class="col-md-3">
                    <label>Classe</label>
                    <select wire:model.live="classe_id" class="form-select">
    <option value="">-- Choisir --</option>
    @foreach($classes as $c)
        <option value="{{ $c->id }}">
            {{ $c->nom }}
        </option>
    @endforeach
</select>
                </div>

                {{-- TRIMESTRE --}}
                <div class="col-md-3">
                    <label>Trimestre</label>
                    <select wire:model.live="trimestre_id" class="form-select">
                        <option value="">-- Choisir --</option>
                        @foreach($trimestres as $t)
                            <option value="{{ $t->id }}">{{ $t->nom }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- MATIÈRE --}}
                <div class="col-md-3">
                    <label>📚 Matière</label>
                    <select wire:model.live="matiere_id" class="form-select">
                        <option value="">-- Choisir --  </option>
                        @foreach($matieres as $m)
                            <option value="{{ $m->id }}">{{ $m->nom }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- FICHIER --}}
                <input type="file"
       wire:model="fichier"
       accept=".xlsx"
       class="form-control">

@if($fichierName)
    <small class="text-success">
        📎 Fichier chargé : {{ $fichierName }}
    </small>
@endif

                    @if($feuilleSelectionnee ?? false)
                        <div class="text-success small mt-1">
                            📄 Feuille : <strong>{{ $feuilleSelectionnee }}</strong>
                        </div>
                    @endif
                </div>

                {{-- BOUTONS --}}
                <div class="col-md-6">
                    <button wire:click="previewFile"
                            wire:loading.attr="disabled"
                            class="btn btn-primary w-100">
                        <span wire:loading.remove wire:target="previewFile">📊 Prévisualiser ⚡</span>
                        <span wire:loading wire:target="previewFile">⏳ Chargement...</span>
                    </button>
                </div>

                <div class="col-md-6">
                    <p class="small">
                        Télécharger le modèle Excel après sélectionné(année + cycle + classe + trimestre)
                    </p>

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
                <span class="badge bg-light text-dark">
                    Feuille: {{ $feuilleSelectionnee ?? 'N/A' }}
                </span>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 500px;">
                    <table class="table table-sm mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Matricule</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Moyenne Interrogation</th>
                                <th>Devoir1</th>
                                <th>Devoir2</th>
                                <th>Statut</th>
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
                                    <td>{{ $data['Matricule'] ?? '' }}</td>
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
                                            <span class="badge bg-success">OK</span>
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

                <div class="p-3">
                    @if($erreurs > 0)
                        <div class="alert alert-danger">
                            ❌ {{ $erreurs }} erreur(s) détectée(s)
                        </div>
                    @else
                        <button wire:click="importer" class="btn btn-success w-100">
                            🚀 Importer {{ $aImporter }} notes
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>