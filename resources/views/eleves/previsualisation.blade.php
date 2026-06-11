@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h4 class="mb-0 text-primary">👁️ Prévisualisation import élèves</h4>
            <small class="text-muted">
                Classe : <strong>{{ $classe->nom ?? 'N/A' }}</strong> 
                | Année : <strong>{{ $annee->nom ?? 'N/A' }}</strong>
            </small>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('eleves.import') }}" class="btn btn-secondary">
                ← Retour import
            </a>
        </div>
    </div>

    {{-- 📊 STATISTIQUES --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['valides'] ?? 0 }}</h3>
                    <small>✅ Valides</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['erreurs'] ?? 0 }}</h3>
                    <small>❌ Erreurs</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3>{{ ($stats['valides'] ?? 0) + ($stats['erreurs'] ?? 0) }}</h3>
                    <small>📄 Total lignes</small>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Lignes VALIDES (toutes colonnes) --}}
    @if(count($valides))
        <div class="alert alert-success">
            ✅ <strong>{{ count($valides) }}</strong> ligne(s) prêtes à importer 
            <span class="badge bg-success ms-2">
                👨‍👩‍👧‍👦 + Parents créés automatiquement
            </span>
        </div>

        <form action="{{ route('eleves.import.valider') }}" method="POST" class="mb-4">
            @csrf
            <button type="submit" class="btn btn-success btn-lg">
                <i class="bi bi-check-circle"></i> 
                ✅ VALIDER {{ count($valides) }} élèves
            </button>
        </form>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">✅ Lignes valides (aperçu)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Sexe</th>
                                <th>Matricule</th>
                                <th>Statut</th>
                                <th>Père</th>
                                <th>Email</th>
                                <th>Tél</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(array_slice($valides, 0, 10) as $ligne) {{-- 10 premières --}}
                                <tr>
                                    <td><strong>{{ $ligne['nom'] ?? '-' }}</strong></td>
                                    <td>{{ $ligne['prenom'] ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ $ligne['sexe'] == 'M' ? 'bg-primary' : 'bg-pink' }}">
                                            {{ $ligne['sexe'] }}
                                        </span>
                                    </td>
                                    <td><code>{{ $ligne['matricule'] ?? '-' }}</code></td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucfirst($ligne['statut'] ?? '-') }}
                                        </span>
                                    </td>
                                    <td>{{ $ligne['nom_pere'] ?? '-' }} {{ $ligne['prenom_pere'] ?? '' }}</td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $ligne['email'] ?? '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        <small>{{ $ligne['telephone'] ?? '-' }}</small>
                                    </td>
                                </tr>
                            @endforeach
                            @if(count($valides) > 10)
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-3">
                                        ... et {{ count($valides) - 10 }} autres lignes
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning text-center py-5">
            <i class="bi bi-exclamation-triangle fs-1 mb-3 d-block"></i>
            ℹ️ Aucune ligne valide détectée
        </div>
    @endif

    {{-- ❌ ERREURS DÉTAILLÉES --}}
    @if(count($erreurs))
        <div class="alert alert-danger">
            ⚠️ <strong>{{ count($erreurs) }}</strong> erreur(s) à corriger
            <a href="{{ route('eleves.import.erreurs') }}" class="btn btn-sm btn-outline-light ms-2">
                📥 Excel erreurs
            </a>
        </div>

        <div class="card border-danger shadow">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0">❌ Détail des erreurs</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-danger table-sm">
                        <thead>
                            <tr>
                                <th>Ligne</th>
                                <th>Données</th>
                                <th>Erreurs</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($erreurs as $err)
                                <tr>
                                    <td>
                                        <span class="badge bg-danger">{{ $err['ligne'] }}</span>
                                    </td>
                                    <td>
                                        <small>
                                            {{ $err['nom'] ?? '-' }} {{ $err['prenom'] ?? '' }}
                                            @if($err['nom_pere'] ?? false)
                                                | {{ $err['nom_pere'] }} {{ $err['prenom_pere'] }}
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <ul class="mb-0 small">
                                            @foreach($err['erreurs'] as $e)
                                                <li class="text-danger">{{ $e }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
