@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">
    <h3 class="mb-3">🚫 Erreurs d'importation</h3>

    <div class="alert alert-warning">
        <strong>Corrigez les erreurs ci-dessous :</strong>
    </div>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Ligne</th>
                <th>Données</th>
                <th>Erreurs</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($erreurs as $erreur)
                <tr>
                    <td>{{ $erreur['ligne'] }}</td>
                    <td>
                        @foreach ($erreur['data'] as $champ => $valeur)
                            <strong>{{ $champ }}:</strong> {{ $valeur ?? 'N/A' }}<br>
                        @endforeach
                    </td>
                    <td>
                        <ul>
                            @foreach ($erreur['erreurs'] as $msg)
                                <li class="text-danger">{{ $msg }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
