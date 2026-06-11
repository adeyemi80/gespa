@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    {{-- Lignes valides --}}
    <div class="card shadow rounded-4 mb-4">
        <div class="card-header bg-success text-white rounded-top-4">
         @if(isset($matiere, $classe))
    <h4 class="mb-0">
        IMPORTATION DE NOTES de {{ $matiere->nom }} Classe de {{ $classe->nom }}
    </h4>
@endif
         <h6 class="mb-0">
                ✅ Lignes valides : {{ count($valides) }}
            </h6>

        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-sm shadow-sm align-middle text-center">
                    <thead class="table-success">
                        <tr>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Interro</th>
                            <th>Devoir 1</th>
                            <th>Devoir 2</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($valides as $valide)
                            <tr>
                                <td>{{ $valide['matricule'] }}</td>
                                <td>{{ $valide['nom'] }}</td>
                                <td>{{ $valide['prenom'] }}</td>
                                <td>{{ $valide['moyenne_interro'] }}</td>
                                <td>{{ $valide['devoir1'] }}</td>
                                <td>{{ $valide['devoir2'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Formulaire de validation --}}
            <form method="POST" action="{{ route('notes.inserer') }}">
                @csrf

                <input type="hidden" name="annee_id" value="{{ $annee_id }}">
                <input type="hidden" name="trimestre_id" value="{{ $trimestre_id }}">
                <input type="hidden" name="matiere_id" value="{{ $matiere_id }}">
                <input type="hidden" name="valides" value="{{ json_encode($valides) }}">

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-success px-4">
                        ✅ Valider l'importation
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Lignes invalides --}}
    <div class="card shadow rounded-4">
        <div class="card-header bg-danger text-white rounded-top-4">
            <h4 class="mb-0">
                ⚠️ Lignes invalides : {{ count($erreurs) }}
            </h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-striped shadow-sm align-middle text-center">
                    <thead class="table-danger">
                        <tr>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Erreur détectée</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($erreurs as $erreur)
                            <tr>
                                <td>{{ $erreur['ligne']['matricule'] ?? '-' }}</td>
                                <td>{{ $erreur['ligne']['nom'] ?? '-' }}</td>
                                <td>{{ $erreur['ligne']['prenom'] ?? '-' }}</td>
                                <td class="text-danger fw-bold">
                                    {{ $erreur['message'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Aucune erreur détectée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
