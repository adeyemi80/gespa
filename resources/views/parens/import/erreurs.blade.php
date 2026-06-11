@extends('tableau.neutre')

@section('title', 'Erreurs d’importation des parens')

@section('content')
<div class="container py-4">
    <h4 class="mb-4 text-danger">🚫 Erreurs lors de l'importation des parens</h4>

    @if(count($erreurs))
        {{-- ⚠️ Alerte erreurs --}}
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            ⚠️ <strong>{{ count($erreurs) }} erreur(s) détectée(s)</strong> dans le fichier importé. Veuillez corriger les données avant de valider l'importation.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>

        {{-- 🧾 Tableau des erreurs --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th style="width: 80px;"># Ligne</th>
                            <th>Données reçues</th>
                            <th>Erreurs détectées</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($erreurs as $erreur)
                            <tr>
                                <td class="text-center fw-bold">{{ $erreur['ligne'] }}</td>
                                <td>
                                    <ul class="mb-0 list-unstyled">
                                        @foreach ($erreur['data'] as $champ => $valeur)
                                            <li><strong>{{ ucfirst($champ) }}:</strong> {{ $valeur ?? 'N/A' }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <ul class="mb-0 text-danger">
                                        @foreach ($erreur['erreurs'] as $message)
                                            <li>{{ $message }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @else
        {{-- ✅ Si aucune erreur --}}
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ <strong>Aucune erreur détectée.</strong> Vous pouvez maintenant confirmer l’importation.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>

        {{-- ✅ Données valides --}}
        <form action="{{ route('parens.import.valider') }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-success mb-3">✅ Valider l’importation</button>
        </form>

        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Téléphone</th>
                            <th>Adresse</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($valides as $ligne)
                            <tr>
                                <td>{{ $ligne['nom'] }}</td>
                                <td>{{ $ligne['prenom'] }}</td>
                                <td>{{ $ligne['telephone'] }}</td>
                                <td>{{ $ligne['adresse'] }}</td>
                                <td>{{ $ligne['email'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('parens.import.form') }}" class="btn btn-secondary">↩️ Retour au formulaire d’importation</a>
    </div>
</div>
@endsection
