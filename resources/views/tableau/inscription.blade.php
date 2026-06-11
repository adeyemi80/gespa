@extends('tableau.neutre')

@section('title', 'le glorieux')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container-fluid py-3 h-100">
    <div class="dashboard-scroll">

        {{-- 🟩 GROUPE1 : INSCRIPTIONS --}}
        <h6 class="section-title">INSCRIPTIONS</h6>
        <div class="row g-3 mb-4">

            @php
                $cards2 = [
                    ['route'=>'eleves.create','color'=>'info','icon'=>'journal-bookmark-fill','title'=>'INSCRIRE UN ELEVE'],
                    
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