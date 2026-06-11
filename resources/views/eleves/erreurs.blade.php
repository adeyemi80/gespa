@extends('tableau.neutre')

@section('content')
<div class="container">
    <h3 class="mb-4">🚫 Erreurs d'importation des élèves</h3>

    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('eleves.erreurs.export') }}" class="btn btn-outline-secondary">
            📥 Exporter les lignes invalides en CSV
        </a>
        <a href="{{ route('eleves.index') }}" class="btn btn-outline-danger">
            ❌ Fermer cette page
        </a>
    </div>

    <div class="alert alert-warning">
        <strong>⚠️ Attention :</strong> Corrigez les erreurs ci-dessous puis réimportez un fichier propre.
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th style="width: 80px;">Ligne</th>
                <th>Données reçues</th>
                <th>Erreurs détectées</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($erreurs as $erreur)
                <tr>
                    <td>{{ $erreur['ligne'] }}</td>
                    <td>
                        @foreach ($erreur['data'] as $champ => $valeur)
                            <strong>{{ ucfirst($champ) }}:</strong> {{ $valeur ?? 'N/A' }}<br>
                        @endforeach
                    </td>
                    <td>
                        <ul class="mb-0">
                            @foreach ($erreur['erreurs'] as $message)
                                <li class="text-danger">{{ $message }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
