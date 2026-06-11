<div>
    {{-- ── FILTRES ─────────────────────────────────────────── --}}
    <div class="row g-2 mb-3">

        <div class="col">
            <select wire:model.live="annee_id" class="form-select">
                <option value="">-- Année --</option>
                @foreach($annees as $a)
                    <option value="{{ $a->id }}">{{ $a->nom }}</option>
                @endforeach
            </select>
        </div>

        <div class="col">
            <select wire:model.live="trimestre_id" class="form-select">
                <option value="">-- Trimestre --</option>
                @foreach($trimestres as $t)
                    <option value="{{ $t->id }}">{{ $t->nom }}</option>
                @endforeach
            </select>
        </div>

        <div class="col">
            <select wire:model.live="classe_id" class="form-select">
                <option value="">-- Classe --</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}">{{ $c->nom }}</option>
                @endforeach
            </select>
        </div>

        <div class="col">
            <select wire:model.live="matiere_id" class="form-select">
                <option value="">-- Matière --</option>
                @foreach($matieres as $m)
                    <option value="{{ $m->id }}">{{ $m->nom }}</option>
                @endforeach
            </select>
        </div>

    </div>
        {{-- ── BOUTONS ──────────────────────────────────────────── --}}
<div class="mb-3 d-flex gap-2">
    <button type="button" wire:click="generer" class="btn btn-primary">
        Générer
    </button>

    @if($annee_id && $trimestre_id && $classe_id && $matiere_id)
        <a href="{{ route('fiches.une-matiere.pdf', [
                'annee_id'     => $annee_id,
                'trimestre_id' => $trimestre_id,
                'classe_id'    => $classe_id,
                'matiere_id'   => $matiere_id,
            ]) }}"
            target="_blank"
            class="btn btn-danger">
            📥 PDF
        </a>
    @else
        <button class="btn btn-danger" disabled>📥 PDF</button>
    @endif
    </div>

    <hr>

    {{-- ── TABLEAU ───────────────────────────────────────────── --}}
    @if(!empty($resultats))

        <h5 class="mb-3">
            {{ $classe->nom }} –
            {{ $matiere->nom }}
            <small class="text-muted">(Coef. {{ $coef }})</small>
            – {{ $trimestre->nom }}
        </h5>

        <table class="table table-bordered table-sm text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th rowspan="2">N°</th>
                    <th rowspan="2">Nom et Prénoms</th>
                    <th colspan="5">Évaluation Ponctuelle d'Étape (EPE)</th>
                    <th rowspan="2">Devoir 1</th>
                    <th rowspan="2">Devoir 2</th>
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
                @foreach($resultats as $i => $res)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="text-start">
                            {{ $res['eleve']->nom }} {{ $res['eleve']->prenom }}
                        </td>
                        {{-- EPE --}}
                        <td></td><td></td><td></td><td></td>
                        <td>{{ $res['moy_epe'] ?? '-' }}</td>
                        {{-- Devoirs --}}
                        <td>{{ $res['note']->devoir ?? '-' }}</td>
                        <td>{{ $res['note']->mcc    ?? '-' }}</td>
                        {{-- Moyenne --}}
                        <td>
                            {{ isset($res['moyenne'])
                                ? number_format($res['moyenne'], 2)
                                : '-' }}
                        </td>
                        {{-- Moy. Coef --}}
                        <td>
                            {{ isset($res['moy_coef'])
                                ? number_format($res['moy_coef'], 2)
                                : '-' }}
                        </td>
                        {{-- Rang --}}
                        <td>{{ $res['rang'] ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @else
        <p class="text-muted">
            Sélectionnez les filtres et cliquez sur "Générer".
        </p>
    @endif
</div>