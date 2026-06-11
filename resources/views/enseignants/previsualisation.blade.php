@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">
    <h4 class="mb-4">👀 Prévisualisation des données à importer</h4>
    @if(count($erreurs))
        <div class="alert alert-warning">
            <strong>⚠️ Des erreurs ont été détectées :</strong> corrigez-les avant de valider.
        </div>
    @endif

    <h5 class="mt-3">✅ Lignes valides : {{ count($valides) }}</h5>
    <table class="table table-sm table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Matricule</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Naissance</th>
                <th>Sexe</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($valides as $i => $eleve)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $eleve['matricule'] }}</td>
                    <td>{{ $eleve['nom'] }}</td>
                    <td>{{ $eleve['prenom'] }}</td>
                    <td>{{ $eleve['date_naissance'] }}</td>
                    <td>{{ $eleve['sexe'] }}</td>
                    <td>{{ $eleve['email'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if (count($erreurs))
        <h5 class="mt-4">🚫 Lignes invalides : {{ count($erreurs) }}</h5>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>Ligne</th>
                    <th>Données</th>
                    <th>Erreurs</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($erreurs as $err)
                    <tr>
                        <td>{{ $err['ligne'] }}</td>
                        <td>
                            @foreach ($err['data'] as $key => $val)
                                <strong>{{ $key }}:</strong> {{ $val }}<br>
                            @endforeach
                        </td>
                        <td>
                            <ul>
                                @foreach ($err['erreurs'] as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <form action="{{ route('eleves.valider_import') }}" method="POST" class="mt-4">
        @csrf
        <button type="submit" class="btn btn-success" {{ count($erreurs) ? 'disabled' : '' }}>
            ✅ Valider l'import
        </button>
        <a href="{{ route('eleves.form') }}" class="btn btn-secondary">🔙 Annuler</a>
    </form>
</div>
@endsection
