@extends('tableau.neutre')

@section('content')

<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>

<div class="container">

    <h3>🏆 Classement Annuel des Élèves</h3>
<a href="{{ route('classement.annuel.pdf', ['annee_id' => $annee_id]) }}"
   class="btn btn-danger">
    Export PDF
</a>
    {{-- 📅 FILTRE ANNÉE --}}
    <form method="GET" class="row mb-3">

        <div class="col-md-4">

            <label class="form-label">
                📅 Année scolaire
            </label>

            <select name="annee_id"
                    class="form-select"
                    onchange="this.form.submit()">

                @foreach($annees as $a)

                    <option value="{{ $a->id }}"
                        {{ ($annee_id ?? null) == $a->id ? 'selected' : '' }}>

                        {{ $a->nom }}

                        @if($a->en_cours)
                            (Année en cours)
                        @endif

                    </option>

                @endforeach

            </select>

        </div>

    </form>

    {{-- 📊 STATISTIQUES --}}
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card p-3 text-center bg-light">
                <h6>👥 Effectif</h6>
                <h3 class="text-primary">{{ $stats['effectif'] ?? 0 }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 text-center bg-light">
                <h6>📈 Moyenne générale</h6>
                <h3 class="text-info">
                    {{ number_format($stats['moyenne_generale'] ?? 0, 2) }}
                </h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 text-center bg-success text-white">
                <h6>✅ Admis</h6>
                <h3>{{ $stats['admis'] ?? 0 }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 text-center bg-danger text-white">
                <h6>❌ Échoués</h6>
                <h3>{{ $stats['echoues'] ?? 0 }}</h3>
            </div>
        </div>

    </div>

    <hr>

    {{-- 🏆 CLASSEMENT --}}
    <h4>
        🏅 Classement annuel

        @if($annee)

            - {{ $annee->nom }}

            @if($annee->en_cours)
                <span class="badge bg-success">Année en cours</span>
            @endif

        @endif
    </h4>

    @if($topEleves->count() > 0)

        <div class="table-responsive">

            <table class="table table-striped table-hover text-center">

                <thead class="table-dark">

                    <tr>
                        <th>Rang</th>
                        <th>Élève</th>
                        <th>Classe</th>
                        <th>Moyenne annuelle</th>
                        <th>Mention</th>
                        <th>Passage</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($topEleves as $i => $moyenne)

                        @php
                            $m = $moyenne->moyenne_annuelle ?? 0;

                            // 📊 Mention
                            $mention = $m >= 16 ? 'Très bien' :
                                       ($m >= 14 ? 'Bien' :
                                       ($m >= 12 ? 'Assez bien' :
                                       ($m >= 10 ? 'Passable' : 'Échoué')));
                        @endphp

                        <tr>

                            {{-- 🏅 Rang --}}
                            <td>
                                {{ $i + 1 }}
                                @if($i == 0) 🥇
                                @elseif($i == 1) 🥈
                                @elseif($i == 2) 🥉
                                @endif
                            </td>

                            {{-- 👨‍🎓 Élève --}}
                            <td>
                                {{ $moyenne->inscription->eleve->nom ?? '' }}
                                {{ $moyenne->inscription->eleve->prenom ?? '' }}
                            </td>

                            {{-- 🏫 Classe --}}
                            <td>
                                {{ $moyenne->inscription->classe->nom ?? '' }}
                            </td>

                            {{-- 📊 Moyenne --}}
                            <td class="{{ $m >= 10 ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                                {{ number_format($m, 2) }}
                            </td>

                            {{-- 📝 Mention --}}
                            <td>
                                {{ $mention }}
                            </td>

                            {{-- 🎓 Passage (IMPORTANT) --}}
                            <td>
                                @if($m >= 10)
                                    <span class="text-success fw-bold">
                                        {{ $moyenne->passage }}
                                    </span>
                                @else
                                    <span class="text-danger fw-bold">
                                        {{ $moyenne->passage }}
                                    </span>
                                @endif
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="alert alert-warning">
            Aucun résultat pour cette année.
        </div>

    @endif

</div>

@endsection