<div class="container-fluid py-4">

    {{-- EN-TÊTE DU RAPPORT --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">

        <div class="card-body p-4 text-white"
             style="background: linear-gradient(135deg, #0d6efd, #084298);">

            <div class="d-flex justify-content-between align-items-center gap-3">

                <div>
                    <h3 class="fw-bold mb-1">
                        <i class="bi bi-bar-chart-line me-2"></i>
                        Rapport financier général
                    </h3>

                    <p class="mb-0 opacity-75">
                        Suivi des recettes, des frais attendus et des restes à payer.
                    </p>
                </div>

                <button type="button"
                        class="btn btn-light"
                        data-bs-toggle="collapse"
                        data-bs-target="#exportPdf"
                        aria-expanded="false"
                        aria-controls="exportPdf"
                        title="Afficher ou masquer l'exportation">

                    <i class="bi bi-chevron-down"></i>

                </button>

            </div>

            <div class="collapse mt-3" id="exportPdf">

                <button type="button"
                        wire:click="exporterPdf"
                        wire:loading.attr="disabled"
                        class="btn btn-light fw-semibold">

                    <span wire:loading.remove wire:target="exporterPdf">
                        <i class="bi bi-file-earmark-pdf me-2"></i>
                        Exporter en PDF
                    </span>

                    <span wire:loading wire:target="exporterPdf">
                        Génération du PDF...
                    </span>

                </button>

            </div>

        </div>
    </div>


    {{-- FILTRES --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">

        <div class="card-header bg-white border-0 p-4"
             role="button"
             data-bs-toggle="collapse"
             data-bs-target="#filtresRapport"
             aria-expanded="true"
             aria-controls="filtresRapport"
             style="cursor:pointer">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">
                    <i class="bi bi-funnel me-2 text-primary"></i>
                    Filtres du rapport
                </h5>

                <i class="bi bi-chevron-down"></i>

            </div>

        </div>

        <div class="collapse show" id="filtresRapport">

            <div class="card-body p-4">

                <div class="row g-3">

                    {{-- ANNÉE --}}
                    <div class="col-md-4">

                        <label for="anneeId" class="form-label fw-semibold">
                            Année scolaire
                        </label>

                        <select id="anneeId"
                                class="form-select"
                                wire:model.live="anneeId">

                            <option value="">Toutes les années</option>

                            @foreach($annees as $annee)
                                <option value="{{ $annee->id }}">
                                    {{ $annee->nom }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    {{-- CYCLE --}}
                    <div class="col-md-4">

                        <label for="cycleId" class="form-label fw-semibold">
                            Cycle
                        </label>

                        <select id="cycleId"
                                class="form-select"
                                wire:model.live="cycleId">

                            <option value="">Tous les cycles</option>

                            @foreach($cycles as $cycle)
                                <option value="{{ $cycle->id }}">
                                    {{ $cycle->nom }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    {{-- CLASSE --}}
                    <div class="col-md-4">

                        <label for="classeId" class="form-label fw-semibold">
                            Classe
                        </label>

                        <select id="classeId"
                                class="form-select"
                                wire:model.live="classeId">

                            <option value="">Toutes les classes</option>

                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}">
                                    {{ $classe->nom }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- INDICATEURS SCOLARITÉ --}}
    <h5 class="fw-bold mb-3">
        <i class="bi bi-mortarboard me-2 text-primary"></i>
        Indicateurs de scolarité
    </h5>

    <div class="row g-3 mb-4">

        {{-- SCOLARITÉ ATTENDUE --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4"
                     role="button"
                     data-bs-toggle="collapse"
                     data-bs-target="#attenduScolarite"
                     aria-expanded="true"
                     style="cursor:pointer">

                    <div class="d-flex justify-content-between align-items-center">

                        <div class="text-muted small">
                            Scolarité attendue
                        </div>

                        <i class="bi bi-chevron-down text-muted"></i>

                    </div>

                    <div class="collapse show mt-3" id="attenduScolarite">

                        <h4 class="fw-bold mb-0">
                            {{ number_format($rapport['attenduScolarite'], 0, ',', ' ') }}
                            <small class="fs-6">FCFA</small>
                        </h4>

                    </div>

                </div>

            </div>

        </div>


        {{-- SCOLARITÉ ENCAISSÉE --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4"
                     role="button"
                     data-bs-toggle="collapse"
                     data-bs-target="#encaisseScolarite"
                     aria-expanded="true"
                     style="cursor:pointer">

                    <div class="d-flex justify-content-between align-items-center">

                        <div class="text-muted small">
                            Scolarité encaissée
                        </div>

                        <i class="bi bi-chevron-down text-muted"></i>

                    </div>

                    <div class="collapse show mt-3" id="encaisseScolarite">

                        <h4 class="fw-bold text-success mb-0">
                            {{ number_format($rapport['encaisseScolarite'], 0, ',', ' ') }}
                            <small class="fs-6">FCFA</small>
                        </h4>

                    </div>

                </div>

            </div>

        </div>


        {{-- RESTE SCOLARITÉ --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4"
                     role="button"
                     data-bs-toggle="collapse"
                     data-bs-target="#resteScolarite"
                     aria-expanded="true"
                     style="cursor:pointer">

                    <div class="d-flex justify-content-between align-items-center">

                        <div class="text-muted small">
                            Reste de scolarité
                        </div>

                        <i class="bi bi-chevron-down text-muted"></i>

                    </div>

                    <div class="collapse show mt-3" id="resteScolarite">

                        <h4 class="fw-bold text-danger mb-0">
                            {{ number_format($rapport['resteScolarite'], 0, ',', ' ') }}
                            <small class="fs-6">FCFA</small>
                        </h4>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- INDICATEURS INSCRIPTION --}}
    <h5 class="fw-bold mb-3">
        <i class="bi bi-person-plus me-2 text-primary"></i>
        Indicateurs d'inscription et de réinscription
    </h5>

    <div class="row g-3 mb-4">

        {{-- INSCRIPTIONS ATTENDUES --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4"
                     role="button"
                     data-bs-toggle="collapse"
                     data-bs-target="#attenduInscription"
                     aria-expanded="true"
                     style="cursor:pointer">

                    <div class="d-flex justify-content-between align-items-center">

                        <div class="text-muted small">
                            Inscription et réinscription attendues
                        </div>

                        <i class="bi bi-chevron-down text-muted"></i>

                    </div>

                    <div class="collapse show mt-3" id="attenduInscription">

                        <h4 class="fw-bold mb-0">
                            {{ number_format($rapport['attenduInscription'], 0, ',', ' ') }}
                            <small class="fs-6">FCFA</small>
                        </h4>

                    </div>

                </div>

            </div>

        </div>


        {{-- INSCRIPTIONS ENCAISSÉES --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4"
                     role="button"
                     data-bs-toggle="collapse"
                     data-bs-target="#encaisseInscription"
                     aria-expanded="true"
                     style="cursor:pointer">

                    <div class="d-flex justify-content-between align-items-center">

                        <div class="text-muted small">
                            Inscription et réinscription encaissées
                        </div>

                        <i class="bi bi-chevron-down text-muted"></i>

                    </div>

                    <div class="collapse show mt-3" id="encaisseInscription">

                        <h4 class="fw-bold text-success mb-0">
                            {{ number_format($rapport['encaisseInscription'], 0, ',', ' ') }}
                            <small class="fs-6">FCFA</small>
                        </h4>

                    </div>

                </div>

            </div>

        </div>


        {{-- RESTE INSCRIPTION --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4"
                     role="button"
                     data-bs-toggle="collapse"
                     data-bs-target="#resteInscription"
                     aria-expanded="true"
                     style="cursor:pointer">

                    <div class="d-flex justify-content-between align-items-center">

                        <div class="text-muted small">
                            Reste inscription et réinscription
                        </div>

                        <i class="bi bi-chevron-down text-muted"></i>

                    </div>

                    <div class="collapse show mt-3" id="resteInscription">

                        <h4 class="fw-bold text-danger mb-0">
                            {{ number_format($rapport['resteInscription'], 0, ',', ' ') }}
                            <small class="fs-6">FCFA</small>
                        </h4>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- INDICATEURS GÉNÉRAUX --}}
    <h5 class="fw-bold mb-3">
        <i class="bi bi-cash-stack me-2 text-primary"></i>
        Indicateurs financiers généraux
    </h5>

    <div class="row g-3 mb-4">

        {{-- TOTAL ATTENDU --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4"
                     role="button"
                     data-bs-toggle="collapse"
                     data-bs-target="#attenduGeneral"
                     aria-expanded="true"
                     style="cursor:pointer">

                    <div class="d-flex justify-content-between align-items-center">

                        <div class="text-muted small">
                            Total attendu
                        </div>

                        <i class="bi bi-chevron-down text-muted"></i>

                    </div>

                    <div class="collapse show mt-3" id="attenduGeneral">

                        <h4 class="fw-bold mb-0">
                            {{ number_format($rapport['attenduGeneral'], 0, ',', ' ') }}
                            <small class="fs-6">FCFA</small>
                        </h4>

                    </div>

                </div>

            </div>

        </div>


        {{-- TOTAL ENCAISSÉ --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4"
                     role="button"
                     data-bs-toggle="collapse"
                     data-bs-target="#encaisseGeneral"
                     aria-expanded="true"
                     style="cursor:pointer">

                    <div class="d-flex justify-content-between align-items-center">

                        <div class="text-muted small">
                            Total encaissé
                        </div>

                        <i class="bi bi-chevron-down text-muted"></i>

                    </div>

                    <div class="collapse show mt-3" id="encaisseGeneral">

                        <h4 class="fw-bold text-success mb-0">
                            {{ number_format($rapport['encaisseGeneral'], 0, ',', ' ') }}
                            <small class="fs-6">FCFA</small>
                        </h4>

                    </div>

                </div>

            </div>

        </div>


        {{-- RESTE GÉNÉRAL --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4"
                     role="button"
                     data-bs-toggle="collapse"
                     data-bs-target="#resteGeneral"
                     aria-expanded="true"
                     style="cursor:pointer">

                    <div class="d-flex justify-content-between align-items-center">

                        <div class="text-muted small">
                            Reste général calculé
                        </div>

                        <i class="bi bi-chevron-down text-muted"></i>

                    </div>

                    <div class="collapse show mt-3" id="resteGeneral">

                        <h4 class="fw-bold text-danger mb-0">
                            {{ number_format($rapport['resteGeneral'], 0, ',', ' ') }}
                            <small class="fs-6">FCFA</small>
                        </h4>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- TABLEAU PAR CATÉGORIE --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">

        <div class="card-header bg-white border-0 p-4"
             role="button"
             data-bs-toggle="collapse"
             data-bs-target="#detailCategories"
             aria-expanded="true"
             aria-controls="detailCategories"
             style="cursor:pointer">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-table me-2 text-primary"></i>
                        Détail financier par catégorie
                    </h5>

                    <p class="text-muted mb-0 small">
                        Les résultats tiennent compte des filtres sélectionnés.
                    </p>

                </div>

                <i class="bi bi-chevron-down"></i>

            </div>

        </div>

        <div class="collapse show" id="detailCategories">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th class="ps-4">Code</th>
                                <th>Catégorie</th>
                                <th class="text-end">Montant attendu</th>
                                <th class="text-end">Montant encaissé</th>
                                <th class="text-end pe-4">Reste calculé</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($rapport['categories'] as $categorie)

                                <tr>

                                    <td class="ps-4">
                                        <span class="badge bg-light text-dark border">
                                            {{ $categorie['code'] }}
                                        </span>
                                    </td>

                                    <td>

                                        <div class="fw-semibold">
                                            {{ $categorie['nom'] }}
                                        </div>

                                        @if($categorie['est_achat'])
                                            <small class="text-muted">
                                                Catégorie d'achat
                                            </small>
                                        @endif

                                    </td>

                                    <td class="text-end">
                                        {{ number_format($categorie['attendu'], 0, ',', ' ') }}
                                    </td>

                                    <td class="text-end text-success fw-semibold">
                                        {{ number_format($categorie['encaisse'], 0, ',', ' ') }}
                                    </td>

                                    <td class="text-end pe-4 text-danger">
                                        {{ number_format($categorie['reste'], 0, ',', ' ') }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        Aucune catégorie de recette trouvée.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                        <tfoot class="table-light fw-bold">

                            <tr>

                                <td colspan="2" class="ps-4">
                                    TOTAL
                                </td>

                                <td class="text-end">
                                    {{ number_format($rapport['attenduGeneral'], 0, ',', ' ') }}
                                </td>

                                <td class="text-end text-success">
                                    {{ number_format($rapport['encaisseGeneral'], 0, ',', ' ') }}
                                </td>

                                <td class="text-end pe-4 text-danger">
                                    {{ number_format($rapport['resteGeneral'], 0, ',', ' ') }}
                                </td>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>