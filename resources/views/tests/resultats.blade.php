@extends('tableau.neutre')

@section('content')
<div class="container py-4">
    <h3>Résultats de l'import</h3>

    @if(!empty($created) && count($created) > 0)
        <div class="alert alert-success">
            {{ count($created) }} fichier(s) importé(s) avec succès.
        </div>

        <table class="table table-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Classe</th>
                    <th>Matière</th>
                    <th>Date</th>
                    <th>Fichier (chemin)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($created as $t)
                <tr>
                    <td>{{ $t->id }}</td>
                    <td>{{ $t->titre }}</td>
                    <td>{{ $t->classe->nom ?? $t->classe_id }}</td>
                    <td>{{ $t->matiere->nom ?? $t->matiere_id }}</td>
                    <td>{{ optional($t->date)->format('Y-m-d') }}</td>
                    <td>{{ $t->fichier }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">
            Aucun fichier importé.
        </div>
    @endif

    @if(!empty($errors) && count($errors) > 0)
        <div class="alert alert-danger mt-3">
            <h5>Erreurs</h5>
            <ul class="mb-0">
                @foreach($errors as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <a href="{{ route('tests.import.index') }}" class="btn btn-primary mt-4">Nouvel import</a>
</div>
@endsection
