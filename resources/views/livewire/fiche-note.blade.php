<div>
        <div class="row mb-3">
            <!-- Sélection Année -->
            <div class="col">
                <select wire:model="annee_id" class="form-control">
                    <option value="">Année</option>
                    @foreach($annees as $a)
                        <option value="{{ $a->id }}">{{ $a->nom }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Sélection Trimestre -->
            <div class="col">
                <select wire:model="trimestre_id" class="form-control">
                    <option value="">Trimestre</option>
                    @foreach($trimestres as $t)
                        <option value="{{ $t->id }}">{{ $t->nom }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Sélection Classe -->
            <div class="col">
                <select wire:model="classe_id" class="form-control">
                    <option value="">Classe</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->nom }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Sélection Matière -->
            <div class="col">
                <select wire:model="matiere_id" class="form-control">
                    <option value="">Matière</option>
                    @foreach($matieres as $m)
                        <option value="{{ $m->id }}">{{ $m->nom }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="button" wire:click="generer" class="btn btn-primary">
            Générer
        </button>

        <button type="button" wire:click="exportPdf" class="btn btn-danger">
            PDF
        </button>
  

    <hr>

    @if(!empty($classement) && count($classement))
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Devoir</th>
                    <th>MCC</th>
                    <th>Composition</th>
                    <th>Moyenne</th>
                    <th>Rang</th>
                </tr>
            </thead>
            <tbody>
                @foreach($classement as $res)
                    <tr>
                        <td>{{ $res['eleve']->nom }} {{ $res['eleve']->prenom }}</td>
                        <td>{{ $res['note']->devoir ?? '-' }}</td>
                        <td>{{ $res['note']->mcc ?? '-' }}</td>
                        <td>{{ $res['note']->composition ?? '-' }}</td>
                        <td>{{ isset($res['moyenne']) ? number_format($res['moyenne'], 2) : '-' }}</td>
                        <td>{{ $res['rang'] ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">Aucun résultat à afficher. Sélectionnez les filtres et cliquez sur "Générer".</p>
    @endif

</div>