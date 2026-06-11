@extends('tableau.neutre')

@section('title', 'DIRECTION')

@section('content')

<div class="container-fluid h-100">
    {{-- Zone scrollable du dashboard --}}
    <div class="dashboard-scroll">

        {{-- Cartes interactives --}}
        <div class="row g-3 mb-3">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('eleves.index') }}" class="text-decoration-none">
                    <div class="card hover-card text-center border-0 rounded-4 bg-primary text-white">
                        <div class="card-body py-3">
                            <i class="bi bi-people-fill fs-3 mb-1"></i>
                            <h6 class="mb-0">Élèves</h6>
                            <p class="fw-bold mb-0"></p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('classes.index') }}" class="text-decoration-none">
                    <div class="card hover-card text-center border-0 rounded-4 bg-success text-white">
                        <div class="card-body py-3">
                            <i class="bi bi-book-fill fs-3 mb-1"></i>
                            <h6 class="mb-0">Classes</h6>
                            <p class="fw-bold mb-0"></p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('paiements.index') }}" class="text-decoration-none">
                    <div class="card hover-card text-center border-0 rounded-4 bg-warning text-dark">
                        <div class="card-body py-3">
                            <i class="bi bi-wallet2 fs-3 mb-1"></i>
                            <h6 class="mb-0">Paiements</h6>
                            <p class="fw-bold mb-0"></p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('budgets.index') }}" class="text-decoration-none">
                    <div class="card hover-card text-center border-0 rounded-4 bg-danger text-white">
                        <div class="card-body py-3">
                            <i class="bi bi-cash-stack fs-3 mb-1"></i>
                            <h6 class="mb-0">Budget</h6>
                            <p class="fw-bold mb-0"></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Cartes interactives --}}
        <div class="row g-3 mb-3">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('inscription-frais.index') }}" class="text-decoration-none">
                    <div class="card hover-card text-center border-0 rounded-4 bg-primary text-white">
                        <div class="card-body py-3">
                            <i class="bi bi-people-fill fs-3 mb-1"></i>
                            <h6 class="mb-0">Les frais par Élèves</h6>
                            <p class="fw-bold mb-0"></p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('classes.index') }}" class="text-decoration-none">
                    <div class="card hover-card text-center border-0 rounded-4 bg-success text-white">
                        <div class="card-body py-3">
                            <i class="bi bi-book-fill fs-3 mb-1"></i>
                            <h6 class="mb-0">Classes</h6>
                            <p class="fw-bold mb-0"></p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('paiements.index') }}" class="text-decoration-none">
                    <div class="card hover-card text-center border-0 rounded-4 bg-warning text-dark">
                        <div class="card-body py-3">
                            <i class="bi bi-wallet2 fs-3 mb-1"></i>
                            <h6 class="mb-0">Paiements</h6>
                            <p class="fw-bold mb-0"></p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('budgets.index') }}" class="text-decoration-none">
                    <div class="card hover-card text-center border-0 rounded-4 bg-danger text-white">
                        <div class="card-body py-3">
                            <i class="bi bi-cash-stack fs-3 mb-1"></i>
                            <h6 class="mb-0">Budget</h6>
                            <p class="fw-bold mb-0"></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Cartes interactives --}}
        <div class="row g-3 mb-3">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('eleves.index') }}" class="text-decoration-none">
                    <div class="card hover-card text-center border-0 rounded-4 bg-primary text-white">
                        <div class="card-body py-3">
                            <i class="bi bi-people-fill fs-3 mb-1"></i>
                            <h6 class="mb-0">Élèves</h6>
                            <p class="fw-bold mb-0"></p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('classes.index') }}" class="text-decoration-none">
                    <div class="card hover-card text-center border-0 rounded-4 bg-success text-white">
                        <div class="card-body py-3">
                            <i class="bi bi-book-fill fs-3 mb-1"></i>
                            <h6 class="mb-0">Classes</h6>
                            <p class="fw-bold mb-0"></p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('paiements.index') }}" class="text-decoration-none">
                    <div class="card hover-card text-center border-0 rounded-4 bg-warning text-dark">
                        <div class="card-body py-3">
                            <i class="bi bi-wallet2 fs-3 mb-1"></i>
                            <h6 class="mb-0">Paiements</h6>
                            <p class="fw-bold mb-0"></p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('budgets.index') }}" class="text-decoration-none">
                    <div class="card hover-card text-center border-0 rounded-4 bg-danger text-white">
                        <div class="card-body py-3">
                            <i class="bi bi-cash-stack fs-3 mb-1"></i>
                            <h6 class="mb-0">Budget</h6>
                            <p class="fw-bold mb-0"></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Cartes interactives --}}
        <div class="row g-3 mb-3">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('eleves.index') }}" class="text-decoration-none">
                    <div class="card hover-card text-center border-0 rounded-4 bg-primary text-white">
                        <div class="card-body py-3">
                            <i class="bi bi-people-fill fs-3 mb-1"></i>
                            <h6 class="mb-0">Élèves</h6>
                            <p class="fw-bold mb-0"></p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('classes.index') }}" class="text-decoration-none">
                    <div class="card hover-card text-center border-0 rounded-4 bg-success text-white">
                        <div class="card-body py-3">
                            <i class="bi bi-book-fill fs-3 mb-1"></i>
                            <h6 class="mb-0">Classes</h6>
                            <p class="fw-bold mb-0"></p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('paiements.index') }}" class="text-decoration-none">
                    <div class="card hover-card text-center border-0 rounded-4 bg-warning text-dark">
                        <div class="card-body py-3">
                            <i class="bi bi-wallet2 fs-3 mb-1"></i>
                            <h6 class="mb-0">Paiements</h6>
                            <p class="fw-bold mb-0"></p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('budgets.index') }}" class="text-decoration-none">
                    <div class="card hover-card text-center border-0 rounded-4 bg-danger text-white">
                        <div class="card-body py-3">
                            <i class="bi bi-cash-stack fs-3 mb-1"></i>
                            <h6 class="mb-0">Budget</h6>
                            <p class="fw-bold mb-0"></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Cartes interactives --}}
        <div class="row g-3 mb-3">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('eleves.index') }}" class="text-decoration-none">
                    <div class="card hover-card text-center border-0 rounded-4 bg-primary text-white">
                        <div class="card-body py-3">
                            <i class="bi bi-people-fill fs-3 mb-1"></i>
                            <h6 class="mb-0">Élèves</h6>
                            <p class="fw-bold mb-0"></p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('classes.index') }}" class="text-decoration-none">
                    <div class="card hover-card text-center border-0 rounded-4 bg-success text-white">
                        <div class="card-body py-3">
                            <i class="bi bi-book-fill fs-3 mb-1"></i>
                            <h6 class="mb-0">Classes</h6>
                            <p class="fw-bold mb-0"></p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('paiements.index') }}" class="text-decoration-none">
                    <div class="card hover-card text-center border-0 rounded-4 bg-warning text-dark">
                        <div class="card-body py-3">
                            <i class="bi bi-wallet2 fs-3 mb-1"></i>
                            <h6 class="mb-0">Paiements</h6>
                            <p class="fw-bold mb-0"></p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('budgets.index') }}" class="text-decoration-none">
                    <div class="card hover-card text-center border-0 rounded-4 bg-danger text-white">
                        <div class="card-body py-3">
                            <i class="bi bi-cash-stack fs-3 mb-1"></i>
                            <h6 class="mb-0">Budget</h6>
                            <p class="fw-bold mb-0"></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Tu peux ajouter ici tableaux, graphiques, etc. --}}

    </div>
</div>

@endsection

@push('styles')
<style>
/* Empêche la page de dépasser l'écran */
body {
    height: 100vh;
    overflow: hidden;
}

/* Zone scrollable interne */
.dashboard-scroll {
    max-height: calc(100vh - 120px); /* header + padding */
    overflow-y: auto;
    padding-right: 5px;
}

/* Cartes */
.hover-card {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.hover-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 22px rgba(0,0,0,0.25);
    cursor: pointer;
}

/* Compact */
.card-body {
    padding: 1rem;
}
</style>
@endpush
