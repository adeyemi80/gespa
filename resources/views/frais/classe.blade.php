@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">
    <h2>Frais par classe</h2>

    <form method="GET" action="{{ route('frais.classe') }}" class="mb-4">
        <select name="classe_id" class="form-select" onchange="this.form.submit()">
            <option value="">-- Choisir une classe --</option>
            @foreach($classes as $c)
                <option value="{{ $c->id }}" {{ ($classe_id == $c->id) ? 'selected' : '' }}>
                    {{ $c->nom_classe }}
                </option>
            @endforeach
        </select>
    </form>

    @if($classe && $eleves->count())
        <h3>Classe : {{ $classe->nom_classe }}</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Élève</th>
                    @foreach($frais as $f)
                        <th>{{ $f->nom_frais }} (Total)</th>
                        <th>{{ $f->nom_frais }} (Reste)</th>
                    @endforeach
                    <th>Détails</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eleves as $eleve)
                    <tr>
                        <td>{{ $eleve->nom }} {{ $eleve->prenom }}</td>
                        @foreach($frais as $f)
                            @php $reste = $eleve->resteParFrais($f); @endphp
                            <td>{{ number_format($f->montant_total, 0, ',', ' ') }} FCFA</td>
                            <td>
                                @if($reste == 0)
                                    <span class="badge bg-success">Payé</span>
                                @else
                                    <span class="badge bg-danger">{{ number_format($reste, 0, ',', ' ') }} FCFA</span>
                                @endif
                            </td>
                        @endforeach
                        <td>
                            <a href="{{ route('frais.eleve', $eleve->id) }}" class="btn btn-primary btn-sm">
                                Voir détails
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Sélectionnez une classe pour afficher les élèves et leurs frais.</p>
    @endif
</div>
@endsection
