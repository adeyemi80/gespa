<div>
    {{-- ══════════════════════ FILTRES ══════════════════════ --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white fw-bold">
            Récapitulatif TD
        </div>
        <div class="card-body">
            <div class="row g-3">

                {{-- Année --}}
                <div class="col-md-2">
                    <label class="form-label">Année</label>
                    <select wire:model.live="annee_id" class="form-select @error('annee_id') is-invalid @enderror">
                        <option value="">Choisir</option>
                        @foreach($annees as $annee)
                            <option wire:key="annee-{{ $annee->id }}" value="{{ $annee->id }}">
                                {{ $annee->libelle ?? $annee->nom ?? $annee->id }}
                            </option>
                        @endforeach
                    </select>
                    @error('annee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Cycle --}}
                <div class="col-md-2">
                    <label class="form-label">Cycle</label>
                    <select wire:model.live="cycle_id" class="form-select">
                        <option value="">Choisir</option>
                        @foreach($cycles as $cycle)
                            <option wire:key="cycle-{{ $cycle->id }}" value="{{ $cycle->id }}">
                                {{ $cycle->nom ?? $cycle->libelle ?? $cycle->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Classe --}}
                <div class="col-md-2">
                    <label class="form-label">Classe</label>
                    <select wire:model.live="classe_id" class="form-select @error('classe_id') is-invalid @enderror">
                        <option value="">Choisir</option>
                        @foreach($classes as $classe)
                            <option wire:key="classe-{{ $classe->id }}" value="{{ $classe->id }}">
                                {{ $classe->niveau }}
                            </option>
                        @endforeach
                    </select>
                    @error('classe_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Mode : mois ou année --}}
                <div class="col-md-2">
                    <label class="form-label">Période</label>
                    <select wire:model.live="mode" class="form-select">
                        <option value="mois">Par mois</option>
                        <option value="annee">Par année</option>
                    </select>
                </div>

                {{-- Mois (visible seulement si mode=mois) --}}
                @if($mode === 'mois')
                <div class="col-md-1">
                    <label class="form-label">Mois</label>
                    <select wire:model.live="mois" class="form-select @error('mois') is-invalid @enderror">
                        <option value="">—</option>
                        <option value="10">Octobre</option>
                        <option value="11">Novembre</option>
                        <option value="12">Décembre</option>
                        <option value="1">Janvier</option>
                        <option value="2">Février</option>
                        <option value="3">Mars</option>
                        <option value="4">Avril</option>
                        <option value="5">Mai</option>
                    </select>
                    @error('mois')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                @endif

                {{-- Bouton Récap Classe --}}
                <div class="col-md-2 d-flex align-items-end">
                    <button wire:click="calculerClasse"
                            wire:loading.attr="disabled"
                            class="btn btn-success w-100">
                        <span wire:loading wire:target="calculerClasse"
                              class="spinner-border spinner-border-sm me-1"></span>
                        <i class="bi bi-table me-1"></i>Récap Classe
                    </button>
                      <button wire:click="calculerToutesClasses" class="btn btn-warning w-100">
    <i class="bi bi-grid me-1"></i> Toutes les classes
</button>
                </div>

            </div>

            {{-- Séparateur : recherche élève individuel --}}
            <hr class="my-3">
            <p class="text-muted small mb-2">
                <i class="bi bi-person me-1"></i>Recherche individuelle (optionnel) :
            </p>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Élève</label>
                    <select wire:model.live="eleve_id" class="form-select @error('eleve_id') is-invalid @enderror">
                        <option value="">Choisir</option>
                        @foreach($eleves as $insc)
                            <option wire:key="eleve-{{ $insc->eleve_id }}" value="{{ $insc->eleve_id }}">
                                {{ $insc->nom }} {{ $insc->prenom }}
                            </option>
                        @endforeach
                    </select>
                    @error('eleve_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button wire:click="calculer"
                            wire:loading.attr="disabled"
                            class="btn btn-primary w-100">
                        <span wire:loading wire:target="calculer"
                              class="spinner-border spinner-border-sm me-1"></span>
                        Afficher élève
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════ TABLEAU RÉCAP CLASSE ══════════════════════ --}}
    @if(!empty($recapClasse))
        @php
            $labelPeriode = $mode === 'mois'
                ? 'Mois ' . $mois
                : 'Année ' . ($annees->firstWhere('id', $annee_id)?->libelle ?? $annee_id);
        @endphp

        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center bg-success text-white">
                <span class="fw-bold">
                    <i class="bi bi-table me-1"></i>
                    Récapitulatif par classe — {{ $classes->firstWhere('id', $classe_id)?->niveau }}
                    — {{ $labelPeriode }}
                </span>
                <a href="{{ route('td.recap.classe.pdf', [
                        'annee_id'  => $annee_id,
                        'classe_id' => $classe_id,
                        'mois'      => $mode === 'mois' ? $mois : null,
                        'mode'      => $mode,
                    ]) }}"
                    target="_blank"
                    class="btn btn-sm btn-light text-danger">
                    <i class="bi bi-file-earmark-pdf me-1"></i>PDF
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm mb-0 align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th class="text-start" style="min-width:130px">Nom</th>
                                <th class="text-start" style="min-width:130px">Prénom</th>
                                <th>Nbre TD suivi</th>
                                <th>Dû cumulé</th>
                                <th>Payé cumulé</th>
                                <th>Reste</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recapClasse as $i => $ligne)
                                <tr class="{{ $ligne['reste'] > 0 ? 'table-warning' : '' }}">
                                    <td>{{ $ligne['nom'] }}</td>
                                    <td>{{ $ligne['prenom'] }}</td>
                                    <td class="text-center">{{ $ligne['nb_td'] }}</td>
                                    <td class="text-end">{{ number_format($ligne['du'],    0, ',', ' ') }} F</td>
                                    <td class="text-end text-success fw-semibold">
                                        {{ number_format($ligne['paye'],  0, ',', ' ') }} F
                                    </td>
                                    <td class="text-end fw-bold {{ $ligne['reste'] > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($ligne['reste'], 0, ',', ' ') }} F
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-secondary fw-bold text-center">
                            <tr>
                                <td colspan="2" class="text-start">TOTAUX</td>
                                <td>{{ $totaux['nb_td'] }}</td>
                                <td class="text-end">{{ number_format($totaux['du'],    0, ',', ' ') }} F</td>
                                <td class="text-end text-success">{{ number_format($totaux['paye'],  0, ',', ' ') }} F</td>
                                <td class="text-end text-danger">{{ number_format($totaux['reste'], 0, ',', ' ') }} F</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- ══════════════════════ RÉSULTAT ÉLÈVE UNIQUE (inchangé) ══════════════════════ --}}
    @if(!empty($resultat))
        <div class="card shadow-sm mb-3">
            <div class="card-header">
                {{ $resultat['eleve']->nom }} {{ $resultat['eleve']->prenom }}
                — {{ $resultat['classe']->niveau }}
                — Mois {{ $mois }}
                <span class="badge bg-secondary ms-2">
                    Mode : {{ $resultat['mode_paiement'] }}
                </span>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-3">
                <div class="card border-secondary h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Arriéré avant ce mois</h6>
                        <h4>{{ number_format($resultat['arriere_avant_ce_mois'], 0, ',', ' ') }} F</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-primary h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Dû ce mois</h6>
                        <h4>{{ number_format($resultat['montant_du_mois'], 0, ',', ' ') }} F</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-primary h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Dû cumulé</h6>
                        <h4>{{ number_format($resultat['montant_du_cumule'], 0, ',', ' ') }} F</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-success h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Payé cumulé</h6>
                        <h4>{{ number_format($resultat['montant_paye_cumule'], 0, ',', ' ') }} F</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-danger h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Reste à payer</h6>
                        <h4 class="text-danger">
                            {{ number_format($resultat['reste_a_payer_cumule'], 0, ',', ' ') }} F
                        </h4>
                    </div>
                </div>
            </div>
            @if($resultat['avance'] > 0)
                <div class="col-md-3">
                    <div class="card border-success h-100">
                        <div class="card-body">
                            <h6 class="text-muted">Avance</h6>
                            <h4 class="text-success">
                                {{ number_format($resultat['avance'], 0, ',', ' ') }} F
                            </h4>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="mt-3">
            <a href="{{ route('td.recap.pdf', [
                    'annee_id'  => $annee_id,
                    'classe_id' => $classe_id,
                    'eleve_id'  => $eleve_id,
                    'mois'      => $mois,
                ]) }}"
                target="_blank"
                class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i>Exporter en PDF
            </a>
        </div>
    @endif
</div>