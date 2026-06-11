<div>
    {{-- Do your work, then step back. --}}
</div>
<div>
    {{-- Include Livewire / Alpine prerequisites in your layout:
         @livewireStyles / @livewireScripts already in app layout
         <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    --}}

    <div class="container py-4">

        {{-- Step 1 : Formulaire & upload --}}
        @if($step === 1)
            <div class="card mb-4">
                <div class="card-header"><strong>Importer des tests (Drag & Drop ou sélection)</strong></div>
                <div class="card-body">

                    <form wire:submit.prevent="goToPreview">

                        <div class="row">
                            <div class="col-md-4">
                                <label>Année scolaire</label>
                               <select wire:model="annee_id" class="form-control">
    <option value="">-- Choisir --</option>
    @foreach($annees as $a)
        <option value="{{ $a->id }}">{{ $a->nom }}</option>
    @endforeach
</select>
                                @error('annee_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Trimestre</label>
                                <select wire:model="trimestre_id" class="form-control" required>
                                    <option value="1">1er trimestre</option>
                                    <option value="2">2e trimestre</option>
                                    <option value="3">3e trimestre</option>
                                </select>
                                @error('trimestre'_id) <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Date </label>
                                <input type="date" wire:model="date" class="form-control" required>
                                @error('date') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>Matière</label>
                               
<select wire:model="matiere_id" class="form-control" required>
    <option value="">-- Choisir --</option>
    @foreach($matieres as $m)
        <option value="{{ $m->id }}">{{ $m->nom }}</option>
    @endforeach
</select>
                                @error('matiere_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Type</label>
                                <input type="text" wire:model="type" class="form-control" placeholder="Interro, Devoir...">
                                @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Titre</label>
                                <input type="text" wire:model="titre" class="form-control" required>
                                @error('titre') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-3">
                            <label>Description (optionnel)</label>
                            <textarea wire:model="description" class="form-control" rows="2"></textarea>
                        </div>

                        {{-- Drag & Drop area with Alpine --}}
                        <div class="mt-4" x-data="{
                            dragging: false,
                            onFiles(event) {
                                let input = $refs.fileinput;
                                // assign files to input; Livewire picks them up on change
                                input.files = event.dataTransfer ? event.dataTransfer.files : event.target.files;
                                input.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                        }"
                        x-on:dragover.prevent="dragging = true"
                        x-on:dragleave.prevent="dragging = false"
                        x-on:drop.prevent="dragging = false; onFiles($event)"
                        class="p-3 border rounded"
                        :class="dragging ? 'border-primary bg-light' : ''"
                        >
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Glisser & déposer les fichiers ici</strong>
                                    <div class="small text-muted">ou cliquer pour sélectionner</div>
                                </div>

                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" @click="$refs.fileinput.click()">Choisir</button>
                                </div>
                            </div>

                            <input type="file" multiple wire:model="uploadedFiles" x-ref="fileinput" class="d-none">

                            <div class="mt-3">
                                <div wire:loading wire:target="uploadedFiles">Téléchargement en cours...</div>

                                @if(!empty($uploadedFiles))
                                    <div class="list-group">
                                        @foreach($uploadedFiles as $i => $f)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $fileMeta[$i]['nom'] ?? $f->getClientOriginalName() }}</strong>
                                                    <div class="small text-muted">{{ number_format(($fileMeta[$i]['size'] ?? $f->getSize())/1024, 2) }} KB</div>
                                                    @if(!empty($fileMeta[$i]['detected']))
                                                        <div class="small text-info">Détecté : {{ $fileMeta[$i]['detected'] }}</div>
                                                    @endif
                                                </div>

                                                <div class="text-end">
                                                    <button type="button" class="btn btn-sm btn-outline-danger" wire:click="$emit('fileRemoved', {{ $i }})">Supprimer</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @error('uploadedFiles') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                                @error('uploadedFiles.*') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-primary">Prévisualiser & continuer</button>
                        </div>

                    </form>

                </div>
            </div>
        @endif

        {{-- Step 2 : Preview (choose classes for each file) --}}
        @if($step === 2)
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div><strong>Prévisualisation et affectation des classes</strong></div>
                    <div>
                        <button class="btn btn-sm btn-secondary" wire:click="$set('step', 1)">← Retour</button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <strong>Résumé :</strong>
                        <div class="small">Année : {{ optional($annees->where('id', $annee_id)->first())->nom ?? '—' }} |
                        Trimestre : {{ $trimestre_id }} |
                        Date : {{ $date }} |
                        Matière : {{ optional($matieres->where('id', $matiere_id)->first())->nom ?? '—' }}</div>
                    </div>

                    <form wire:submit.prevent="importNow">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Fichier</th>
                                        <th>Taille (KB)</th>
                                        <th>Détection</th>
                                        <th>Classe à affecter</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fileMeta as $index => $meta)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $meta['nom'] }}</td>
                                            <td>{{ number_format(($meta['size'] ?? 0)/1024, 2) }}</td>
                                            <td>{{ $meta['detected'] ?? '—' }}</td>
                                            <td style="min-width:220px;">
                                                <select wire:model="fileMeta.{{ $index }}.classe_id" class="form-control">
                                                    <option value="">-- Choisir la classe --</option>
                                                    @foreach($classes as $cl)
                                                        <option value="{{ $cl->id }}">{{ $cl->nom }}</option>
                                                    @endforeach
                                                </select>
                                                @error("fileMeta.{$index}.classe_id") <div class="text-danger">{{ $message }}</div> @enderror
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <div>
                                <button type="button" class="btn btn-secondary" wire:click="$set('step', 1)">Modifier</button>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-success">Importer maintenant</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        @endif

        {{-- Step 3 : Results --}}
        @if($step === 3)
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-success">
                        {{ $resultMessage }}
                    </div>

                    <a href="#" class="btn btn-primary" wire:click="$set('step',1)">Importer d'autres tests</a>
                </div>
            </div>
        @endif

    </div>

    {{-- Livewire upload progress (optional) --}}
    <div wire:loading wire:target="importNow">
        <div class="fixed-bottom p-3">
            <div class="progress" style="height: 6px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <script>
        // optional: scroll to errors when validation occurs
        window.addEventListener('scroll-to-errors', () => {
            setTimeout(()=> {
                const el = document.querySelector('.text-danger');
                if (el) el.scrollIntoView({behavior:'smooth', block:'center'});
            }, 50);
        });
    </script>
</div>
