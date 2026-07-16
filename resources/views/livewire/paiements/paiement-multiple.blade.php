<div>
    {{-- Écoute l'événement pour ouvrir la popup --}}
    {{-- Lien invisible pour contourner le blocage popup --}}
<a id="ticket-link" href="#" target="_blank" style="display:none"></a>

<script>
    document.addEventListener('livewire:initialized', () => {

        Livewire.on('ouvrirTicket', ({ numeroLot }) => {
            const link = document.getElementById('ticket-link');
            link.href = `/paiements/ticket/${numeroLot}`;
            link.click();
        });

        Livewire.on('masquerSucces', () => {
            setTimeout(() => {
                @this.set('successMessage', '');
            }, 10000);
        });

    });
</script>

    {{-- SUCCESS --}}
    @if ($successMessage)
        <div class="alert alert-success alert-dismissible py-2">
            ✅ {{ $successMessage }}
            <button type="button" class="btn-close" wire:click="$set('successMessage', '')"></button>
        </div>
    @endif

    {{-- ERREURS --}}
    @if ($errors->has('frais'))
        <div class="alert alert-danger py-2">
            ❌ {{ $errors->first('frais') }}
        </div>
    @endif

    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-primary text-white p-3">
            <h5 class="mb-0 fw-bold">💰 Enregistrer un paiement multiple</h5>
        </div>

        <div class="card-body p-3">

            {{-- ANNÉE / CYCLE / CLASSE --}}
            <div class="row g-2 mb-2">

                <div class="col-md-4">
                    <label class="fw-bold small">📅 Année</label>
                    <select wire:model.live="anneeId"
                            class="form-select form-select-sm bg-light border-primary">
                        @foreach ($annees as $annee)
                            <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold small">Cycle</label>
                    <select wire:model.live="cycleId"
                            class="form-select form-select-sm bg-light border-primary">
                        <option value="">-- Choisir --</option>
                        @foreach ($cycles as $cycle)
                            <option value="{{ $cycle->id }}">{{ $cycle->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold small">Classe</label>
                    <select wire:model.live="classeId"
                            class="form-select form-select-sm bg-light border-primary">
                        <option value="">-- Classe --</option>
                        @foreach ($classes as $c)
                            <option value="{{ $c['id'] }}">{{ $c['nom'] }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            {{-- ÉLÈVE --}}
            <div class="mb-2">
                <label class="fw-bold small">Élève</label>
                <select wire:model.live="inscriptionId"
                        class="form-select form-select-sm bg-light border-primary">
                    <option value="">-- Élève --</option>
                    @foreach ($inscriptions as $i)
                        <option value="{{ $i['id'] }}">{{ $i['nom'] }}</option>
                    @endforeach
                </select>
            </div>

            {{-- FRAIS --}}
            @if (count($fraisDisponibles) > 0)
                <div class="mb-3">
                    <label class="fw-bold small">
                        📚 Frais
                        <span class="badge bg-info">{{ count($fraisDisponibles) }}</span>
                        <small class="text-muted fw-normal ms-1">(cliquez pour sélectionner)</small>
                    </label>

                    <div class="border rounded p-2 bg-light">
                        @foreach ($fraisDisponibles as $i => $frais)
                            <div class="form-check mb-1">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="frais_{{ $i }}"
                                       wire:click="toggleFrais({{ $i }})"
                                       {{ $frais['selectionne'] ? 'checked' : '' }}>
                                <label class="form-check-label small" for="frais_{{ $i }}">
                                    {{ $frais['nom'] }}
                                    <span class="text-muted">
                                        — Reste :
                                        <strong class="text-danger">
                                            {{ number_format($frais['reste'], 0, ',', ' ') }} FCFA
                                        </strong>
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
           {{-- APRÈS --}}
@elseif ($inscriptionId && $aucunFraisAffecte)
    <div class="alert alert-warning py-2 small">
        ⚠️ Aucun frais n'a été affecté à cette inscription.
    </div>
@elseif ($inscriptionId)
    <div class="alert alert-success py-2 small">
        ✅ Tous les frais de cet élève sont soldés.
    </div>
@endif

            {{-- RÉSUMÉ --}}
            @if (collect($fraisDisponibles)->where('selectionne', true)->isNotEmpty())
                <div class="row g-2 bg-light p-2 rounded border mb-2">
                    <div class="col-md-4">
                        <input class="form-control form-control-sm"
                               value="{{ number_format($totalFrais, 0, ',', ' ') }} FCFA"
                               placeholder="Total frais" readonly>
                    </div>
                    <div class="col-md-4">
                        <input class="form-control form-control-sm"
                               value="{{ number_format($totalPaye, 0, ',', ' ') }} FCFA"
                               placeholder="Total payé" readonly>
                    </div>
                    <div class="col-md-4">
                        <input class="form-control form-control-sm text-danger fw-bold"
                               value="{{ number_format($totalReste, 0, ',', ' ') }} FCFA"
                               placeholder="Reste" readonly>
                    </div>
                </div>

                {{-- MONTANTS PAR FRAIS --}}
                <div class="mb-2">
                    <div class="bg-light p-2 rounded border">
                        @foreach ($fraisDisponibles as $i => $frais)
                            @if ($frais['selectionne'])
                                <div class="mb-1 row g-1 align-items-center">
                                    <div class="col-md-7">
                                        <small class="fw-bold">{{ $frais['nom'] }}</small>
                                        <small class="text-muted">
                                            — Reste : {{ number_format($frais['reste'], 0, ',', ' ') }} FCFA
                                        </small>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="number"
                                               wire:model.live="montants.{{ $frais['frais_id'] }}"
                                               min="1"
                                               max="{{ $frais['reste'] }}"
                                               class="form-control form-control-sm
                                                      {{ isset($montants[$frais['frais_id']]) && $montants[$frais['frais_id']] > $frais['reste'] ? 'is-invalid' : '' }}"
                                               placeholder="Montant à payer">
                                        @if (isset($montants[$frais['frais_id']]) && $montants[$frais['frais_id']] > $frais['reste'])
                                            <div class="invalid-feedback" style="font-size:11px">
                                                Max : {{ number_format($frais['reste'], 0, ',', ' ') }} FCFA
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        {{-- Total à encaisser --}}
                        <div class="border-top mt-2 pt-2 d-flex justify-content-between">
                            <span class="fw-bold small">Total à encaisser :</span>
                            <span class="fw-bold text-success">
                                {{ number_format($this->totalASaisir, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- MODE & DATE --}}
            <div class="row g-2 mt-1">
                <div class="col-md-6">
                    <label class="fw-bold small">Mode paiement</label>
                    <select wire:model="modePaiement"
                            class="form-select form-select-sm bg-light border-primary">
                        <option value="">-- Choisir --</option>
                        <option value="Espèces">Espèces</option>
                        <option value="Mobile Money">Mobile Money</option>
                        <option value="Chèque">Chèque</option>
                    </select>
                    @error('modePaiement')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="fw-bold small">Date</label>
                    <input type="date"
                           wire:model="datePaiement"
                           class="form-control form-control-sm border-primary">
                    @error('datePaiement')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- BOUTON --}}
            <div class="text-end mt-3">
                <button wire:click="enregistrer"
                        wire:loading.attr="disabled"
                        class="btn btn-success btn-sm">
                    <span wire:loading.remove wire:target="enregistrer">
                        💾 Enregistrer et imprimer le reçu
                    </span>
                    <span wire:loading wire:target="enregistrer">
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        Enregistrement...
                    </span>
                </button>
            </div>

        </div>
    </div>
</div>