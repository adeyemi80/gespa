@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">
    <h2 class="mb-4">Liste des examens blancs</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($examens->isEmpty())
        <p>Aucun examen blanc disponible.</p>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Année</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Classes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($examens as $examen)
                    <tr>
                        <td>{{ $examen->id }}</td>
                        <td>{{ $examen->type }}</td>
                        <td>{{ $examen->annee->libelle ?? '' }}</td>
                        <td>{{ $examen->date_debut }}</td>
                        <td>{{ $examen->date_fin }}</td>
                        <td>
                            @foreach($examen->classes as $classe)
                                <span class="badge bg-secondary">{{ $classe->nom }}</span>
                            @endforeach
                        </td>
                        <td>
                            <!-- Télécharger le modèle Excel -->
                            <a href="{{ route('examens.notes.template', $examen->id) }}" class="btn btn-sm btn-success mb-1">
                                Télécharger le modèle
                            </a>

                            <!-- Importer les notes -->
                            <a href="{{ route('examens.notes.import.form', $examen->id) }}" class="btn btn-sm btn-primary mb-1">
                                IMPORTATION DES NOTES
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection