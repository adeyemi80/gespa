<div>
    <div class="row mb-3">
        {{-- Année --}}
        <div class="col">
            <select wire:model="annee_id" class="form-control">
                <option value="">Année</option>
                @foreach($annees as $a)
                    <option value="{{ $a->id }}">{{ $a->nom }}</option>
                @endforeach
            </select>
        </div>

        {{-- Trimestre --}}
        <div class="col">
            <select wire:model="trimestre_id" class="form-control">
                <option value="">Trimestre</option>
                @foreach($trimestres as $t)
                    <option value="{{ $t->id }}">{{ $t->nom }}</option>
                @endforeach
            </select>
        </div>

        {{-- Classe (cycle_id = 3 uniquement) --}}
        <div class="col">
            <select wire:model="classe_id" class="form-control">
                <option value="">Classe</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}">{{ $c->nom }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <button type="button" wire:click="generer" class="btn btn-primary">
        Générer
    </button>

    <button type="button" wire:click="exportPdf" class="btn btn-danger">
        📥 PDF
    </button>

    <hr>

    @if(!empty($fiches) && count($fiches))
        @foreach($fiches as $fiche)
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        {{ $fiche['matiere']->nom }}
                        <small class="text-muted">(Coef. {{ $fiche['matiere']->coefficient }})</small>
                    </h5>

                    <table class="table table-bordered table-sm text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th rowspan="2">N°</th>
                                <th rowspan="2">Nom et Prénoms</th>
                                <th colspan="5">Évaluation Ponctuelle d'Étape (EPE)</th>
                                <th rowspan="2">Devoir1</th>
                                <th rowspan="2">Devoir2</th>
                                <th rowspan="2">Moyenne</th>
                                <th rowspan="2">Moy. Coef</th>
                                <th rowspan="2">Rang</th>
                            </tr>
                            <tr>
                                <th>EPE1</th>
                                <th>EPE2</th>
                                <th>EPE3</th>
                                <th>EPE4</th>
                                <th>Moy EPE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fiche['resultats'] as $i => $res)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="text-start">
                                        {{ $res['eleve']->nom }} {{ $res['eleve']->prenom }}
                                    </td>
                                    <td></td><td></td><td></td><td></td><td></td>
                                    <td></td><td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @else
        <p class="text-muted">
            Aucun résultat. Sélectionnez les filtres et cliquez sur "Générer".
        </p>
    @endif
</div>