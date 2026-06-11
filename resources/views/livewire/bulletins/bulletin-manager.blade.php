<div class="container-fluid max-w-7xl mx-auto p-6">
    <h1 class="h2 fw-bold mb-4 text-dark">Gestion des Bulletins</h1>

    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Filtres --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h2 class="h5 mb-0 d-flex align-items-center gap-2">
                <i class="fas fa-filter"></i> Filtres
            </h2>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-medium">Année scolaire</label>
                    <select wire:model.live="annee_id" class="form-select form-select-lg">
                        <option value="">Sélectionner une année</option>
                        @foreach($annees as $annee)
                            <option value="{{ $annee['id'] }}">{{ $annee['nom'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">Classe</label>
                    <select wire:model.live="classe_id" class="form-select form-select-lg" {{ !$annee_id ? 'disabled' : '' }}>
                        <option value="">Sélectionner une classe</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe['id'] }}">{{ $classe['nom'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium">Trimestre</label>
                    <select wire:model.live="trimestre_id" class="form-select form-select-lg">
                        <option value="">Sélectionner un trimestre</option>
                        @foreach($trimestres as $trimestre)
                            <option value="{{ $trimestre['id'] }}">{{ $trimestre['nom'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

           <div class="mt-4">
   <button 
    wire:click="generatePdfClasse" 
    class="btn btn-primary btn-lg"
    style="min-width: 200px;"
    wire:loading.attr="disabled"
    wire:target="generatePdfClasse"
>
    <span wire:loading.remove wire:target="generatePdfClasse">
        <i class="fas fa-file-pdf me-2"></i>Télécharger BULLETINS (classe entière)
    </span>
    <span wire:loading wire:target="generatePdfClasse">
        <i class="fas fa-spinner fa-spin me-2"></i>Génération...
    </span>
</button>
</div>
        </div>
    </div>

    {{-- Statistiques --}}
    @if(count($statistiques) > 0)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h2 class="h5 mb-0 d-flex align-items-center gap-2">
                    <i class="fas fa-chart-bar"></i> Statistiques
                </h2>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <p class="text-muted small mb-1">Total Élèves</p>
                                <p class="display-6 fw-bold text-primary">{{ $statistiques['total_eleves'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <p class="text-muted small mb-1">Moyenne Classe</p>
                                <p class="display-6 fw-bold text-success">{{ $statistiques['moyenne_classe'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <p class="text-muted small mb-1">Meilleure Moyenne</p>
                                <p class="display-6 fw-bold text-purple" style="color: #6f42c1 !important;">{{ $statistiques['meilleure_moyenne'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <p class="text-muted small mb-1">Taux de Réussite</p>
                                <p class="display-6 fw-bold text-warning">{{ $statistiques['taux_reussite'] }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <p class="text-muted small mb-1">Plus Faible Moyenne</p>
                                <p class="h4 fw-bold">{{ $statistiques['plus_faible_moyenne'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <p class="text-muted small mb-1">Médiane</p>
                                <p class="h4 fw-bold">{{ $statistiques['moyenne_mediane'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <p class="text-muted small mb-1">Élèves à &lt; 10</p>
                                <p class="h4 fw-bold">{{ $statistiques['eleves_a_checkpoint'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card bg-light border-0">
                    <div class="card-body">
                        <h3 class="h6 fw-bold mb-3">Répartition des mentions</h3>
                        <div class="row g-2 text-center">
                            <div class="col-2">
                                <p class="h4 fw-bold text-success mb-1">{{ $statistiques['mentions']['felicitations'] }}</p>
                                <p class="small text-muted mb-0">Félicitations</p>
                            </div>
                            <div class="col-2">
                                <p class="h4 fw-bold text-primary mb-1">{{ $statistiques['mentions']['tableau_honneur'] }}</p>
                                <p class="small text-muted mb-0">Tableau d'honneur</p>
                            </div>
                            <div class="col-2">
                                <p class="h4 fw-bold text-warning mb-1">{{ $statistiques['mentions']['encouragement'] }}</p>
                                <p class="small text-muted mb-0">Encouragement</p>
                            </div>
                            <div class="col-2">
                                <p class="h4 fw-bold text-info mb-1">{{ $statistiques['mentions']['avertissement'] }}</p>
                                <p class="small text-muted mb-0">Avertissement</p>
                            </div>
                            <div class="col-2">
                                <p class="h4 fw-bold text-danger mb-1">{{ $statistiques['mentions']['blame'] }}</p>
                                <p class="small text-muted mb-0">Blâme</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Liste des bulletins --}}
    @if(count($bulletins) > 0)
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h2 class="h5 mb-0">Bulletins ({{ count($bulletins) }})</h2>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Élève</th>
                                <th class="px-4 py-3">Matricule</th>
                                <th class="px-4 py-3">Moyenne</th>
                                <th class="px-4 py-3">Moy. Sci.</th>
                                <th class="px-4 py-3">Moy. Lit.</th>
                                <th class="px-4 py-3">Rang</th>
                                <th class="px-4 py-3">Mention</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bulletins as $bulletin)
                                <tr>
                                    <td class="px-4 py-3 fw-medium">
                                        {{ $bulletin['inscription']['eleve']['nom'] ?? '' }} {{ $bulletin['inscription']['eleve']['prenom'] ?? '' }}
                                    </td>
                                    <td class="px-4 py-3 text-muted small">{{ $bulletin['inscription']['eleve']['matricule'] ?? '-' }}</td>
                                    <td class="px-4 py-3 fw-bold text-primary">{{ number_format($bulletin['moyenne_trimestre'] ?? 0, 2) }}</td>
                                    <td class="px-4 py-3 small">{{ number_format($bulletin['moyenne_scientifique'] ?? 0, 2) }}</td>
                                    <td class="px-4 py-3 small">{{ number_format($bulletin['moyenne_litteraire'] ?? 0, 2) }}</td>
                                    <td class="px-4 py-3 small">{{ $bulletin['rang_trimestre'] ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $badgeClasses = [
                                                'FÉLICITATION' => 'bg-success',
                                                'TABLEAU D\'HONNEUR' => 'bg-primary',
                                                'ENCOURAGEMENT' => 'bg-warning',
                                                'AVERTISSEMENT' => 'bg-info',
                                                'BLAME' => 'bg-danger',
                                            ];
                                            $badgeClass = $badgeClasses[$bulletin['mention'] ?? ''] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $badgeClass }} fs-7">
                                            {{ $bulletin['mention'] ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                       <button 
    wire:click="generatePdfIndividuel({{ $bulletin['inscription']['id'] }})" 
    class="btn btn-sm btn-outline-danger" 
    title="Télécharger PDF"
    wire:loading.attr="disabled"
    wire:target="generatePdfIndividuel"
>
    <span wire:loading.remove wire:target="generatePdfIndividuel">
        <i class="fas fa-download"></i>
    </span>
    <span wire:loading wire:target="generatePdfIndividuel">
        <i class="fas fa-spinner fa-spin"></i>
    </span>
</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif($annee_id && $classe_id && $trimestre_id)
        <div class="card shadow-sm text-center p-5">
            <div class="card-body">
                <i class="fas fa-inbox display-1 text-muted mb-3"></i>
                <p class="text-muted mb-0">Aucun bulletin trouvé pour ces critères.</p>
            </div>
        </div>
    @endif

    {{-- Modal bulletin individuel --}}
    @if($bulletinSelectionne)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" wire:ignore.self>
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header sticky-top">
                        <h2 class="h4 modal-title fw-bold">
                           Bulletin de
{{ $bulletinSelectionne['inscription']->eleve->nom }}
{{ $bulletinSelectionne['inscription']->eleve->prenom }}
                        </h2>
                        <button wire:click="fermerBulletin" class="btn-close" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-4">
    <div class="col-md-6">
        <strong>Classe :</strong>
        {{ $bulletinSelectionne['classe']->nom ?? '-' }}
    </div>

    <div class="col-md-6">
        <strong>Trimestre :</strong>
        {{ $bulletinSelectionne['trimestre']->nom ?? '-' }}
    </div>

    <div class="col-md-6">
        <strong>Année :</strong>
        {{ $bulletinSelectionne['annee']->nom ?? '-' }}
    </div>

    <div class="col-md-6">
        <strong>Matricule :</strong>
        {{ $bulletinSelectionne['inscription']->eleve->matricule ?? '-' }}
    </div>

    <div class="col-md-6">
        <strong>Moyenne :</strong>
        <span class="fw-bold text-primary">
            {{ number_format($bulletinSelectionne['moyenne_trimestre'] ?? 0, 2) }}
        </span>
    </div>

    <div class="col-md-6">
        <strong>Moyenne Scientifique :</strong>
        {{ number_format($bulletinSelectionne['moyenne_scientifique'] ?? 0, 2) }}
    </div>

    <div class="col-md-6">
        <strong>Moyenne Littéraire :</strong>
        {{ number_format($bulletinSelectionne['moyenne_litteraire'] ?? 0, 2) }}
    </div>

    <div class="col-md-6">
        <strong>Rang :</strong>
        {{ $bulletinSelectionne['rang_trimestre'] ?? '-' }}
    </div>

    <div class="col-md-6">
        <strong>Mention :</strong>
        {{ $bulletinSelectionne['mention'] ?? '-' }}
    </div>

    <div class="col-md-12">
        <strong>Appréciation :</strong>
        {{ $bulletinSelectionne['appreciation'] ?? '-' }}
    </div>
</div>

                    <button 
    wire:click="generatePdfIndividuel({{ $bulletinSelectionne['inscription']->id }})" 
    class="btn btn-danger"
    wire:loading.attr="disabled"
    wire:target="generatePdfIndividuel"
>
    <span wire:loading.remove wire:target="generatePdfIndividuel">
        <i class="fas fa-download me-2"></i>Télécharger PDF
    </span>
    <span wire:loading wire:target="generatePdfIndividuel">
        <i class="fas fa-spinner fa-spin me-2"></i>Génération...
    </span>
</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>