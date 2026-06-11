@extends('tableau.neutre')

@section('title', 'ENSEIGNANT')

@section('content')
<div class="container-fluid py-3 h-100">
    <div class="dashboard-scroll">

        {{-- 🟦 GROUPE 1 : IMPORTATIONS & SAISIES --}}
        <h6 class="section-title">📥 Importations & Saisies</h6>
        <div class="row g-3 mb-4">

            @php
                $cards1 = [
                    ['route'=>'eleves.import','color'=>'primary','icon'=>'people-fill','title'=>'Importer les élèves'],
                    ['route'=>'parens.import.form','color'=>'warning','icon'=>'person-lines-fill','title'=>'Importer les parents'],
                    ['route'=>'matieres.import.index','color'=>'danger','icon'=>'journal-text','title'=>'Importer les matières'],
                    ['route'=>'notes.import','color'=>'success','icon'=>'clipboard-check','title'=>'Importer des notes'],
                ];
            @endphp

            @foreach($cards1 as $c)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <a href="{{ route($c['route']) }}" class="text-decoration-none">
                        <div class="card dashboard-card bg-{{ $c['color'] }} text-white">
                            <div class="card-body">
                                <i class="bi bi-{{ $c['icon'] }} dashboard-icon"></i>
                                <h6 class="dashboard-title">{{ $c['title'] }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- 🟩 GROUPE 2 : NOTES & PÉDAGOGIE --}}
        <h6 class="section-title">📘 Notes & Pédagogie</h6>
        <div class="row g-3 mb-4">

            @php
                $cards2 = [
                    ['route'=>'td.index','color'=>'primary','icon'=>'journal-bookmark-fill','title'=>'Travaux dirigés'],
                    ['route'=>'notes.import.index','color'=>'success','icon'=>'file-earmark-arrow-up','title'=>'Importer une note'],
                    ['route'=>'fiches.index','color'=>'info','icon'=>'file-text','title'=>'Fiche par matière'],
                    ['route'=>'fiches.formulaire','color'=>'warning','icon'=>'files','title'=>'Fiches par classe'],
                ];
            @endphp

            @foreach($cards2 as $c)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <a href="{{ route($c['route']) }}" class="text-decoration-none">
                        <div class="card dashboard-card bg-{{ $c['color'] }} text-white">
                            <div class="card-body">
                                <i class="bi bi-{{ $c['icon'] }} dashboard-icon"></i>
                                <h6 class="dashboard-title">{{ $c['title'] }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- 🟥 GROUPE 3 : FINANCES & GESTION --}}
        <h6 class="section-title">💰 Finances & Gestion</h6>
        <div class="row g-3 mb-3">

            @php
                $cards3 = [
                    ['route'=>'paiements.createUP','color'=>'success','icon'=>'wallet2','title'=>'Enregistrer un paiement'],
                    ['route'=>'depenses.create','color'=>'danger','icon'=>'cash-stack','title'=>'Enregistrer une dépense'],
                    ['route'=>'budgets.create','color'=>'secondary','icon'=>'pie-chart-fill','title'=>'Créer un budget'],
                    ['route'=>'eleves.create','color'=>'primary','icon'=>'person-plus-fill','title'=>'Ajouter un élève'],
                ];
            @endphp

            @foreach($cards3 as $c)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <a href="{{ route($c['route']) }}" class="text-decoration-none">
                        <div class="card dashboard-card bg-{{ $c['color'] }} text-white">
                            <div class="card-body">
                                <i class="bi bi-{{ $c['icon'] }} dashboard-icon"></i>
                                <h6 class="dashboard-title">{{ $c['title'] }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

    </div>
</div>
@push('styles')
<style>
/* Fond général */
body {
    height: 100vh;
    overflow: hidden;
    background: linear-gradient(180deg, #f4f7fc, #eef2f7);
}

/* Zone scrollable */
.dashboard-scroll {
    max-height: calc(100vh - 110px);
    overflow-y: auto;
    padding-right: 6px;
}

/* Titres de section */
.section-title {
    font-weight: 700;
    color: #495057;
    margin-bottom: .75rem;
}

/* Cartes dashboard */
.dashboard-card {
    border-radius: 1rem;
    min-height: 130px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    transition: all .35s ease;
    box-shadow: 0 6px 16px rgba(0,0,0,.12);
    position: relative;
    overflow: hidden;
}

/* Icône */
.dashboard-icon {
    font-size: 2.3rem;
    margin-bottom: .4rem;
    transition: transform .35s ease;
}

/* Titre */
.dashboard-title {
    font-size: .95rem;
    font-weight: 600;
    margin: 0;
}

/* 🔥 EFFET AU SURVOL */
.dashboard-card:hover {
    transform: translateY(-8px) scale(1.03);
    box-shadow: 0 18px 36px rgba(0,0,0,.30);
    filter: brightness(1.15);
}

/* Icône animée */
.dashboard-card:hover .dashboard-icon {
    transform: scale(1.2) rotate(-2deg);
}

/* Halo lumineux */
.dashboard-card::after {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: inherit;
    opacity: 0;
    transition: opacity .35s ease;
    box-shadow: inset 0 0 0 2px rgba(255,255,255,.45);
}

.dashboard-card:hover::after {
    opacity: 1;
}

/* Curseur */
.dashboard-card {
    cursor: pointer;
}
</style>
@endpush
@endsection