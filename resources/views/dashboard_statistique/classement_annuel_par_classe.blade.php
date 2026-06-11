@extends('tableau.neutre')

@section('content')

<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary mb-3">
    ⬅️ Retour
</button>

<div class="container">

    <h3>🏆 Classement Annuel par Classe</h3>

    {{-- 📄 EXPORT PDF --}}
    <a href="{{ route('classement.annuel.par.classe.pdf', [
        'annee_id' => $annee_id,
        'classe_id' => $classe_id
    ]) }}"
    class="btn btn-danger mb-3">
        📄 Export PDF
    </a>

    {{-- 🔎 FILTRES --}}
    <form method="GET" class="row mb-3">

        <div class="col-md-6">
            <label class="form-label">📅 Année scolaire</label>
            <select name="annee_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Toutes les années --</option>

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

        <div class="col-md-6">
            <label class="form-label">🏫 Classe</label>
            <select name="classe_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Toutes les classes --</option>

                @foreach($classes as $c)
                    <option value="{{ $c->id }}"
                        {{ ($classe_id ?? null) == $c->id ? 'selected' : '' }}>
                        {{ $c->nom }}
                    </option>
                @endforeach

            </select>
        </div>

    </form>

    <hr>

    {{-- 📊 STATS --}}
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

    {{-- 🏆 CLASSEMENT PAR CLASSE --}}
    <h4>🏅 Classement par classe</h4>

    @forelse($classesData as $data)

        @if($data['eleves']->count() > 0) {{-- 🔥 FILTRE IMPORTANT --}}
        
        <div class="card mb-4 p-3 shadow-sm">

            <h5 class="text-primary mb-3">
                {{ $data['classe']->nom }}
            </h5>

            <table class="table table-striped table-hover text-center">

                <thead class="table-dark">
                    <tr>
                        <th>Rang</th>
                        <th>Élève</th>
                        <th>Moyenne annuelle</th>
                        <th>Mention</th>
                        <th>Passage</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($data['eleves'] as $i => $moyenne)

                        @php
                            $m = $moyenne->moyenne_annuelle ?? 0;

                            $classeActuelle = $moyenne->inscription->classe ?? null;

                            $classeSuivante = $classeActuelle
                                ? \App\Models\Classe::where('ordre', $classeActuelle->ordre + 1)->first()
                                : null;

                            $mention = $m >= 16 ? 'Très bien' :
                                       ($m >= 14 ? 'Bien' :
                                       ($m >= 12 ? 'Assez bien' :
                                       ($m >= 10 ? 'Passable' : 'Échoué')));
                        @endphp

                        <tr>

                            <td>
                                {{ $i + 1 }}
                                @if($i === 0) 🥇
                                @elseif($i === 1) 🥈
                                @elseif($i === 2) 🥉
                                @endif
                            </td>

                            <td>
                                {{ $moyenne->inscription->eleve->nom ?? '' }}
                                {{ $moyenne->inscription->eleve->prenom ?? '' }}
                            </td>

                            <td class="{{ $m >= 10 ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                                {{ number_format($m, 2) }}
                            </td>

                            <td>{{ $mention }}</td>

                            <td>
                                @if($m >= 10)
                                    <span class="badge bg-success">
                                        ⬆️ Passe en : {{ $classeSuivante->nom ?? 'Fin de cycle' }}
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        🔁 Redouble {{ $classeActuelle->nom ?? '' }}
                                    </span>
                                @endif
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        @endif {{-- 🔥 FIN FILTRE CLASSES VIDES --}}

    @empty
        <div class="alert alert-warning">
            Aucune donnée disponible.
        </div>
    @endforelse

</div>

@endsection