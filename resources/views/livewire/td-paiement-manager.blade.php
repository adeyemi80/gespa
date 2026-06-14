<div>
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            Paiements TD
        </div>
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-2">
                    <label>Année</label>
                    <select wire:model="annee_id" class="form-select">
                        @foreach($annees as $annee)
                            <option value="{{ $annee->id }}">
                                {{ $annee->libelle ?? $annee->nom ?? $annee->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label>Cycle</label>
                    <select wire:model.live="cycle_id" class="form-select">
                        <option value="">Choisir</option>
                        @foreach($cycles as $cycle)
                            <option value="{{ $cycle->id }}">
                                {{ $cycle->nom ?? $cycle->libelle ?? $cycle->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label>Classe</label>
                    <select wire:model.live="classe_id" class="form-select">
                        <option value="">Choisir</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}">
                                {{ $classe->niveau }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
    <label>Élève</label>
    <select wire:model="eleve_id" class="form-select">
        <option value="">Choisir</option>
        @foreach($eleves as $insc)
            <option wire:key="eleve-option-{{ $insc->eleve_id }}" value="{{ $insc->eleve_id }}">
                {{ $insc->nom }} {{ $insc->prenom }}
            </option>
        @endforeach
    </select>
</div>

                <div class="col-md-3">
                    <label>Montant</label>
                    <input type="number"
                           wire:model="montant"
                           class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Date</label>
                    <input type="date"
                           wire:model="date_paiement"
                           class="form-control">
                </div>

            </div>

            <button wire:click="save" class="btn btn-success mt-3">
                Enregistrer
            </button>
        </div>
    </div>

   {{-- <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Élève</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paiements as $paiement)
                <tr>
                    <td>{{ $paiement->date_paiement }}</td>
                    <td>{{ $paiement->eleve->nom ?? '' }} {{ $paiement->eleve->prenom ?? '' }}</td>
                    <td>{{ number_format($paiement->montant, 0, ',', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>--}}
</div>