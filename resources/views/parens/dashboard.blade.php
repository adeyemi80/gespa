@extends('tableau.neutre')

@section('title', 'Dashboard Parent')

@section('content')

<style>

    .note-card{
        border-radius:20px;
        transition:0.3s ease;
        overflow:hidden;
    }

    .note-card:hover{
        transform:translateY(-5px);
    }

    .note-moyenne{
        font-size:1.2rem;
        font-weight:bold;
    }

    .resume-box{
        border-radius:18px;
    }

</style>

<button
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
    class="btn btn-secondary mb-3">
    ⬅️ Retour
</button>

<div class="container mt-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="fw-bold">
            👨‍👩‍👧‍👦 INFORMATIONS AUX PARENTS
        </h2>

        <a href="{{ route('parens.dashboard') }}"
           class="btn btn-primary">

            🔄 Actualiser

        </a>

    </div>

    {{-- STATISTIQUES --}}
    <div class="row mb-4">

        <div class="col-md-4 mb-3">

            <div class="card bg-primary text-white shadow border-0">

                <div class="card-body text-center">

                    <h5>🔔 Notifications</h5>

                    <h2>{{ $notificationsCount }}</h2>

                    <small>non lues</small>

                </div>

            </div>

        </div>

        <div class="col-md-4 mb-3">

            <div class="card bg-success text-white shadow border-0">

                <div class="card-body text-center">

                    <h5>📚 Enfants</h5>

                    <h2>{{ $inscriptions->count() }}</h2>

                    <small>inscrits</small>

                </div>

            </div>

        </div>

        <div class="col-md-4 mb-3">

            <div class="card bg-info text-white shadow border-0">

                <div class="card-body text-center">

                    <h5>📨 Messages</h5>

                    <h2>{{ $messages->count() }}</h2>

                    <small>récents</small>

                </div>

            </div>

        </div>

    </div>

    {{-- AUCUN ENFANT --}}
    @if($inscriptions->isEmpty())

        <div class="alert alert-warning text-center py-5">

            👶 Aucun enfant inscrit trouvé pour cette année scolaire.

        </div>

    @else

    {{-- FILTRES : ANNÉE / TRIMESTRE / ENFANT --}}
    <form method="GET" class="mb-4">

        <div class="d-flex flex-wrap align-items-center gap-3">

            {{-- ANNÉE --}}
            <div class="d-flex align-items-center gap-2">

                <label class="fw-bold mb-0">
                    🗓️ Année :
                </label>

                <select name="annee_id"
                        class="form-select w-auto"
                        onchange="this.form.submit()">

                    @foreach($annees as $a)

                        <option value="{{ $a->id }}"
                            {{ $annee_id == $a->id ? 'selected' : '' }}>

                            {{ $a->nom }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- TRIMESTRE --}}
            <div class="d-flex align-items-center gap-2">

                <label class="fw-bold mb-0">
                    📅 Trimestre :
                </label>

                <select name="trimestre_id"
                        class="form-select w-auto"
                        onchange="this.form.submit()">

                    @foreach([1,2,3] as $t)

                        <option value="{{ $t }}"
                            {{ $trimestre == $t ? 'selected' : '' }}>

                            Trimestre {{ $t }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- ENFANT --}}
            <div class="d-flex align-items-center gap-2">

                <label class="fw-bold mb-0">
                    👧 Enfant :
                </label>

                <select name="eleve_id"
                        class="form-select w-auto"
                        onchange="this.form.submit()">

                    @foreach($inscriptions as $insc)

                        <option value="{{ $insc->eleve_id }}"
                            {{ $eleve_id == $insc->eleve_id ? 'selected' : '' }}>

                            {{ $insc->eleve->nom }} {{ $insc->eleve->prenom }}
                            ({{ $insc->classe->nom ?? 'N/A' }})

                        </option>

                    @endforeach

                </select>

            </div>

        </div>

    </form>

    {{-- ENFANT SÉLECTIONNÉ --}}
    @php

        $inscription = $inscriptions->firstWhere('eleve_id', $eleve_id);

    @endphp

    @if(!$inscription)

        <div class="alert alert-warning text-center py-5">

            👶 Aucun enfant sélectionné.

        </div>

    @else

        @php

            $eleveNotes = $notes->get($inscription->id, collect());

            $moyenneData = $moyennes->get($inscription->id);

            $moyenneGenerale = $moyenneData->moyenne_trimestrielle ?? null;

            $rang = $moyenneData->rang_trimestre ?? '-';

            $moyenneAnnuelle = $moyenneData->moyenne_annuelle ?? null;

        @endphp

        <div class="card shadow-sm border-0 mb-5">

            {{-- HEADER ELEVE --}}
            <div class="card-header bg-light d-flex justify-content-between align-items-center">

                <div>

                    <h4 class="mb-1">

                        👦
                        {{ $inscription->eleve->nom }}
                        {{ $inscription->eleve->prenom }}

                    </h4>

                    <small class="text-muted">

                        Classe :
                        {{ $inscription->classe->nom ?? 'N/A' }}
                        —
                        Année : {{ $annee->nom ?? 'N/A' }}

                    </small>

                </div>

                <span class="badge bg-dark fs-6">

                    Trimestre {{ $trimestre }}

                </span>

            </div>

            <div class="card-body">

                {{-- PAS DE NOTES --}}
                @if($eleveNotes->isEmpty())

                    <div class="alert alert-light text-center">

                        Aucune note disponible.

                    </div>

                @else

                {{-- CARTES MATIERES --}}
                <div class="row">

                    @foreach($eleveNotes as $note)

                        @php

                            $m = $note->moyenne_matiere;

                        @endphp

                        <div class="col-md-4 mb-4">

                            <div class="card shadow-sm border-0 h-100 note-card">

                                <div class="card-body">

                                    {{-- MATIERE --}}
                                    <div class="d-flex justify-content-between align-items-center mb-3">

                                        <h5 class="fw-bold mb-0">

                                            📘
                                            {{ $note->matiere->nom ?? '-' }}

                                        </h5>

                                    </div>

                                    {{-- INTERRO --}}
                                    <p class="mb-2">

                                        <strong>Interro :</strong>

                                        {{ $note->moyenne_interro ?? '-' }}

                                    </p>

                                    {{-- DEVOIR 1 --}}
                                    <p class="mb-2">

                                        <strong>Devoir 1 :</strong>

                                        {{ $note->devoir1 ?? '-' }}

                                    </p>

                                    {{-- DEVOIR 2 --}}
                                    <p class="mb-3">

                                        <strong>Devoir 2 :</strong>

                                        {{ $note->devoir2 ?? '-' }}

                                    </p>

                                    {{-- PROGRESS --}}
                                    @if($m !== null)

                                        <div class="progress mb-3"
                                             style="height:20px; border-radius:10px;">

                                            <div class="progress-bar"
                                                 role="progressbar"
                                                 style="width: {{ $m * 5 }}%;">

                                                {{ number_format($m,1) }}

                                            </div>

                                        </div>

                                    @endif

                                    {{-- MOYENNE --}}
                                    <div class="mb-3">

                                        <strong>Moyenne :</strong>

                                        <span class="note-moyenne">

                                            @if($m !== null)

                                                {{ number_format($m,2) }}

                                            @else

                                                -

                                            @endif

                                        </span>

                                    </div>

                                    {{-- MENTION --}}
                                    <div>

                                        @if($m === null)

                                            <span class="badge bg-secondary">
                                                — Aucune note
                                            </span>

                                        @elseif($m >= 18)

                                            <span class="badge bg-success">
                                                🟢 Excellent
                                            </span>

                                        @elseif($m >= 16)

                                            <span class="badge bg-primary">
                                                🔵  Très Bien
                                            </span>

                                        @elseif($m >= 14)

                                            <span class="badge bg-primary">
                                                🔵  Bien
                                            </span>

                                        @elseif($m >= 12)

                                            <span class="badge bg-info text-dark">
                                                🔷 Assez Bien
                                            </span>

                                        @elseif($m >= 10)

                                            <span class="badge bg-warning text-dark">
                                                🟠 Passable
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                🔴 Insuffisant
                                            </span>

                                        @endif

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

                {{-- RESUME --}}
                <div class="row mt-4">

                    <div class="col-md-6">

                        <div class="card border-0 shadow-sm bg-light resume-box">

                            <div class="card-body">

                                <h5 class="fw-bold mb-4">

                                    📌 Résumé du trimestre

                                </h5>

                                <p>

                                    <strong>Moyenne Trimestrielle :</strong>

                                    @if($moyenneGenerale !== null)

                                        <span class="fw-bold text-primary">

                                            {{ number_format($moyenneGenerale,2) }}

                                        </span>

                                    @else

                                        -

                                    @endif

                                </p>

                                <p>

                                    <strong>Rang :</strong>

                                    <span class="badge bg-info">

                                        {{ $rang }}

                                    </span>

                                </p>

                                <p>

                                    <strong>Moyenne Annuelle :</strong>

                                    @if($moyenneAnnuelle !== null)

                                        {{ number_format($moyenneAnnuelle,2) }}

                                    @else

                                        -

                                    @endif

                                </p>

                                <p class="mb-0">

                                    <strong>Décision :</strong>

                                    @if($moyenneGenerale !== null)

                                        @if($moyenneGenerale >= 10)

                                            <span class="badge bg-success">

                                                ✅ Admis

                                            </span>

                                        @else

                                            <span class="badge bg-danger">

                                                ❌ Ajourné

                                            </span>

                                        @endif

                                    @else

                                        -

                                    @endif

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- CONDUITES --}}
                @if(isset($conduites) && $conduites->isNotEmpty())

                    <div class="row mt-4">

                        <div class="col-12">

                            <div class="card border-0 shadow-sm">

                                <div class="card-body">

                                    <h5 class="fw-bold mb-3">
                                        🧭 Conduite
                                    </h5>

                                    <ul class="list-group list-group-flush">

                                        @foreach($conduites as $conduite)

                                            <li class="list-group-item">
                                                {{ $conduite->note_conduite ?? $conduite->commentaire ?? '—' }}
                                            </li>

                                        @endforeach

                                    </ul>

                                </div>

                            </div>

                        </div>

                    </div>

                @endif

                {{-- BULLETINS --}}
                @if(isset($bulletins) && $bulletins->isNotEmpty())

                    <div class="row mt-4">

                        <div class="col-12">

                            <div class="card border-0 shadow-sm">

                                <div class="card-body">

                                    <h5 class="fw-bold mb-3">
                                        📄 Bulletins
                                    </h5>

                                    <ul class="list-group list-group-flush">

                                        @foreach($bulletins as $bulletin)

                                            <li class="list-group-item d-flex justify-content-between align-items-center">

                                                Bulletin —
                                                {{ $bulletin->trimestre_id ?? '' }}

                                                @if(!empty($bulletin->fichier))

                                                    <a href="{{ asset('storage/'.$bulletin->fichier) }}"
                                                       target="_blank"
                                                       class="btn btn-sm btn-outline-primary">

                                                        📥 Télécharger

                                                    </a>

                                                @endif

                                            </li>

                                        @endforeach

                                    </ul>

                                </div>

                            </div>

                        </div>

                    </div>

                @endif

                @endif

            </div>

        </div>

    @endif

    @endif

</div>

@endsection