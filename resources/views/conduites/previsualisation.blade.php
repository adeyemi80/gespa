@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    <h3 class="mb-4">📝 Prévisualisation des notes de conduite à importer</h3>

    {{-- Tableau des données --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>Ligne</th>
                    <th>Matricule</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Note</th>
                    <th>Erreur</th>
                </tr>
            </thead>
            <tbody>
                @foreach($donnees as $ligne)
                    <tr class="{{ $ligne['erreur'] ? 'table-danger' : 'table-success' }}">
                        <td class="text-center">{{ $ligne['ligne'] ?? '' }}</td>
                        <td>{{ $ligne['matricule'] ?? '' }}</td>
                        <td>{{ $ligne['nom'] ?? '' }}</td>
                        <td>{{ $ligne['prenom'] ?? '' }}</td>
                        <td class="text-center">{{ $ligne['note_conduite'] ?? '' }}</td>
                        <td class="text-center">
                            @if($ligne['erreur'])
                                <span class="text-danger fw-bold">{{ $ligne['erreur'] }}</span>
                            @else
                                <span class="text-success">Pas d'erreur</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Formulaire de validation --}}
    @if(!empty($valides) && count($valides) > 0)
        <form action="{{ route('conduites.inserer') }}" method="POST" class="mt-3">
            @csrf
            <input type="hidden" name="annee_id" value="{{ $annee_id }}">
            <input type="hidden" name="classe_id" value="{{ $classe_id }}">
            <input type="hidden" name="trimestre_id" value="{{ $trimestre_id }}">
            <input type="hidden" name="valides" value='@json($valides)'>

            <button type="submit" class="btn btn-success">
                ✅ Valider l’importation des {{ count($valides) }} lignes valides
            </button>
        </form>
    @else
        <div class="alert alert-warning mt-4 text-center">
            ⚠️ Aucune ligne valide à importer. Corrigez les erreurs et réessayez.
        </div>
        <div class="text-center mt-2">
            <a href="{{ route('conduites.import') }}" class="btn btn-secondary">
                ← Retour à l’importation
            </a>
        </div>
    @endif
</div>
@endsection
