<div>
    {{-- ══════════ FILTRES ══════════ --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">

                {{-- Année --}}
                <div class="col-md-2">
                    <label class="form-label">Année</label>
                    <select wire:model.live="annee_id" class="form-select">
                        <option value="">-- Toutes --</option>
                        @foreach($annees as $annee)
                            <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Cycle --}}
                <div class="col-md-2">
                    <label class="form-label">Cycle</label>
                    <select wire:model.live="cycle_id" class="form-select">
                        <option value="">-- Tous --</option>
                        @foreach($cycles as $cycle)
                            <option value="{{ $cycle->id }}">{{ $cycle->nom }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Classe --}}
                <div class="col-md-2">
                    <label class="form-label">Classe</label>
                    <select wire:model.live="classe_id" class="form-select"
                            @disabled(!$annee_id)>
                        <option value="">-- Toutes --</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Élève --}}
                <div class="col-md-2">
                    <label class="form-label">Élève</label>
                    <select wire:model.live="inscription_id" class="form-select"
                            @disabled(!$classe_id)>
                        <option value="">-- Tous --</option>
                        @foreach($inscriptions as $inscription)
                            <option value="{{ $inscription->id }}">
                                {{ $inscription->eleve->nom }} {{ $inscription->eleve->prenom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Frais --}}
                <div class="col-md-2">
                    <label class="form-label">Frais</label>
                    <select wire:model.live="frais_id" class="form-select"
                            @disabled(!$classe_id)>
                        <option value="">-- Tous --</option>
                        @foreach($frais as $f)
                            <option value="{{ $f->id }}">{{ $f->description }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Dates --}}
                <div class="col-md-1">
                    <label class="form-label">Du</label>
                    <input type="date" wire:model.live="date_debut" class="form-control">
                </div>
                <div class="col-md-1">
                    <label class="form-label">Au</label>
                    <input type="date" wire:model.live="date_fin" class="form-control">
                </div>

            </div>

            {{-- Boutons --}}
            <div class="mt-3 d-flex gap-2">
                <button wire:click="resetFiltres" class="btn btn-secondary btn-sm">
                    <i class="bi bi-x-circle"></i> Réinitialiser
                </button>

                @if($annee_id || $cycle_id || $classe_id || $inscription_id || $frais_id || $date_debut || $date_fin)
                    <button wire:click="exportPdf" class="btn btn-danger btn-sm">
                        <i class="bi bi-file-earmark-pdf"></i> Exporter PDF
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ══════════ INDICATEUR DE CHARGEMENT ══════════ --}}
    <div wire:loading class="alert alert-info py-2">
        Chargement en cours...
    </div>

    {{-- ══════════ CONTENU CONDITIONNEL ══════════ --}}
    @if($annee_id || $cycle_id || $classe_id || $inscription_id || $frais_id || $date_debut || $date_fin)

        {{-- ══════════ TABLEAU ══════════ --}}
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Élève</th>
                        <th>Classe</th>
                        <th>Année</th>
                        <th>Frais</th>
                        <th>Montant Payé</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paiements as $paiement)
                        <tr>
                            <td>{{ $paiement->id }}</td>
                            <td>{{ $paiement->inscription->eleve->nom }} {{ $paiement->inscription->eleve->prenom }}</td>
                            <td>{{ $paiement->inscription->classe->nom }}</td>
                            <td>{{ $paiement->inscription->annee->nom }}</td>
                            <td>{{ $paiement->frais->description }}</td>
                            <td>{{ number_format($paiement->montant_verse, 0, ',', ' ') }} F</td>
                            <td>{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Aucun paiement trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ══════════ PAGINATION ══════════ --}}
        <div class="mt-3">
            {{ $paiements->links() }}
        </div>

    @else

        {{-- ══════════ MESSAGE D'INVITE ══════════ --}}
        <div class="text-center text-muted py-5">
            <i class="bi bi-funnel fs-1 d-block mb-3 opacity-25"></i>
            <p class="fs-5">Sélectionnez au moins un filtre pour afficher les paiements.</p>
        </div>

    @endif
</div>