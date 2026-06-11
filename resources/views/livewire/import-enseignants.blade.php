<div class="card shadow-sm">
    <div class="card-body">

        {{-- Bouton téléchargement modèle --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Importation des enseignants</h5>

            <button wire:click="telechargerModele"
                    class="btn btn-outline-primary btn-sm">
                📥 Télécharger le modèle Excel
            </button>
        </div>

        {{-- Upload --}}
        <div class="mb-3">
            <input type="file"
                   wire:model="fichier"
                   class="form-control"
                   accept=".xlsx">

            @error('fichier')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>

        {{-- Loader --}}
        <div wire:loading wire:target="fichier" class="text-info mb-2">
            ⏳ Chargement du fichier...
        </div>

        {{-- Erreurs --}}
        @if(count($erreurs) > 0)
            <div class="alert alert-danger">
                <strong>❌ {{ count($erreurs) }} erreur(s) détectée(s)</strong>
                <ul class="mb-0">
                    @foreach($erreurs as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Message erreur global --}}
        @if(session()->has('error'))
            <div class="alert alert-warning">
                {{ session('error') }}
            </div>
        @endif

        {{-- Success --}}
        @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Preview --}}
        @if(count($preview) > 0)
            <h6 class="mt-4">👀 Prévisualisation des données</h6>

            <div class="table-responsive">
                <table class="table table-bordered table-striped mt-2">
                    <thead class="table-dark">
                        <tr>
                            @foreach($headers as $head)
                                <th>{{ $head }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($preview as $index => $row)
                            <tr>
                                @foreach($row as $cell)
                                    <td>{{ $cell }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Bouton importer --}}
            <button wire:click="importer"
                    wire:loading.attr="disabled"
                    class="btn btn-success mt-3">
                ✅ Importer les données
            </button>
        @endif

    </div>
</div>