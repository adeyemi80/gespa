
@extends('tableau.neutre')

@section('title', 'ADMIN')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container-fluid py-3 h-100">
    <div class="dashboard-scroll">
{{-- 🟩 GROUPE 0 :ETABLISSEMENT --}}
        <h6 class="section-title">ETABLISSEMENT</h6>
        <div class="row g-3 mb-4">

            @php
                $cards2 = [
                    ['route'=>'etablissements.create','color'=>'info','icon'=>'journal-bookmark-fill','title'=>'ENREGISTRER UN ETABLISSEMNT '],
                    ['route'=>'etablissements.index','color'=>'info','icon'=>'file-earmark-arrow-up','title'=>'ETABLISSEMNTS'],
                    ['route'=>'eleves.create','color'=>'info','icon'=>'file-earmark-arrow-up','title'=>'ENREGISTRER UNE INSCRIPTION'],
                     ['route'=>'eleves.modifier','color'=>'info','icon'=>'journal-bookmark-fill','title'=>'MODIFIER LES INFORMATIONS D\'UN ELEVE'],
                   
                    
                    
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
        {{-- 🟦 GROUPE 1 : ANNEES SCOLAIRES, TRIMESTRES, CLASSES & MATIERES--}}
        <h6 class="section-title">📥 ANNÉES SCOLAIRES, TRIMESTRES, CLASSES & MATIERES</h6>
        <div class="row g-3 mb-4">

            @php
                $cards1 = [
                    ['route'=>'annees.create','color'=>'primary','icon'=>'people-fill','title'=>'CREER UNE ANNEE SCOLAIRE '],
                    ['route'=>'trimestres.create','color'=>'primary','icon'=>'person-lines-fill','title'=>'CREATION DES TRIMESTRES'],
                    ['route'=>'classes.create','color'=>'primary','icon'=>'journal-text','title'=>'CREER UNE CLASSE'],
                    ['route'=>'matieres.create','color'=>'primary','icon'=>'clipboard-check','title'=>'CREER UNE MATIERE'],
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
        <h6 class="section-title"></h6>
        <div class="row g-3 mb-4">

            @php
                $cards2 = [
                    ['route'=>'annees.index','color'=>'info','icon'=>'journal-bookmark-fill','title'=>'LES ANNEES SCOLAIRES'],
                    ['route'=>'trimestres.index','color'=>'info','icon'=>'file-earmark-arrow-up','title'=>'LES TRIMESTRES'],
                    ['route'=>'classes.index','color'=>'info','icon'=>'file-text','title'=>'LES CLASSES'],
                    ['route'=>'matieres.index','color'=>'info','icon'=>'files','title'=>'LES MATIERES'],
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
 {{-- 🟩 GROUPE 2 : ELEVES ENSEIGNANTS --}}
        <h6 class="section-title">📘ELEVES, ENSEIGNANTS & FRAIS</h6>
        <div class="row g-3 mb-4">

            @php
                $cards2 = [
                    ['route'=>'eleves.index','color'=>'primary','icon'=>'journal-bookmark-fill','title'=>'LES ELEVES'],
                     ['route'=>'enseignants.index','color'=>'primary','icon'=>'journal-bookmark-fill','title'=>'LES ENSEIGNANTS'],
                     ['route'=>'enseignants.create','color'=>'primary','icon'=>'journal-bookmark-fill','title'=>'UN CREER UN ENSEIGNANTS'],
                     ['route'=>'frais.annee_classe.index','color'=>'primary','icon'=>'journal-bookmark-fill','title'=>'LES FRAIS'],
                   
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

         {{-- 🟩 GROUPE 2 : NOTES & PÉDAGOGIE --}}
        <h6 class="section-title">📘 Notes & Pédagogie</h6>
        <div class="row g-3 mb-4">

            @php
                $cards2 = [
                    ['route'=>'td.dirige','color'=>'primary','icon'=>'journal-bookmark-fill','title'=>'TRAVAUX DIRIGES'],
                    ['route'=>'notes.import.index','color'=>'success','icon'=>'file-earmark-arrow-up','title'=>'IMPORTER UNE OU DES NOTES'],
                    ['route'=>'fiches.index','color'=>'info','icon'=>'file-text','title'=>'FICHE DE NOTES'],
                    ['route'=>'fiches.formulaire','color'=>'warning','icon'=>'files','title'=>'FICHES DE NOTES DE CLASSE'],
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
        <h6 class="section-title">💰 PEDAGOGIE </h6>
        <div class="row g-3 mb-3">

            @php
                $cards3 = [
                    ['route'=>'enseignements.index','color'=>'success','icon'=>'wallet2','title'=>'ENSEIGNEMENTS'],
                     ['route'=>'inscriptions.index','color'=>'success','icon'=>'wallet2','title'=>'LES INSCRIPTIONS'],
                     ['route'=>'eleves-externes-td.create','color'=>'success','icon'=>'wallet2','title'=>'ENREGISTRER LES EXTERNES AUX TD'],
                     ['route'=>'eleves-externes-td.index','color'=>'success','icon'=>'wallet2','title'=>'LES EXTERNES AUX TD'],
                     

                     
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

         {{-- 🟥 GROUPE 4 : GALERIES & MEDIAS --}}
        <h6 class="section-title">💰 GALERIES & MEDIAS</h6>
        <div class="row g-3 mb-3">

            @php
                $cards3 = [
                    ['route'=>'galeries.create','color'=>'success','icon'=>'wallet2','title'=>'CREER UNE GALERIE'],
                    ['route'=>'galeries.index','color'=>'danger','icon'=>'cash-stack','title'=>'LES GALERIES'],
                    ['route'=>'medias.index','color'=>'primary','icon'=>'person-plus-fill','title'=>'LES MEDIAS'],
                    ['route'=>'inscriptions-td-externes.index','color'=>'primary','icon'=>'person-plus-fill','title'=>'INSCRIPTIONS DES EXTERNES AUX TD'],
                    
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