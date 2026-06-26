@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">
    <h2>Détail de l'examen</h2>

    <p><strong>Type :</strong> {{ $examen->type }}</p>
    <p><strong>Période :</strong> {{ $examen->date_debut }} - {{ $examen->date_fin }}</p>

    <h4>Actions</h4>

   {{-- <form action="/examens/{{ $examen->id }}/participants" method="POST" class="d-inline">
        @csrf
        <button class="btn btn-success">Générer participants</button>
    </form>

    <form action="/examens/{{ $examen->id }}/epreuves" method="POST" class="d-inline">
        @csrf
        <button class="btn btn-warning">Générer épreuves</button>
    </form>--}}

   <a href="{{ route('examens.notes.import.form', $examen->id) }}" class="btn btn-primary">
    IMPORTATION DES NOTES
</a>
<a href="{{ route('examens.classement', $examen->id) }}" class="btn btn-primary">
    TELECHARGER LE CLASSEMENT 
</a>
<a href="{{ route('examens.notes.pdf', $examen->id) }}" class="btn btn-primary">
    TELECHARGER LES NOTES
</a>

    <hr>

    <h4>Participants</h4>
<a href="{{ route('examens.export.pdf', $examen->id) }}" class="btn btn-danger">
    Export PDF
</a>
    <table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Numéro de Table</th>
            <th>Nom & Prénom(s)</th>
            <th>Classe</th>
        </tr>
    </thead>
    <tbody>
        @forelse($examen->participants as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->numero_table }}</td>
                <td>
                    {{ optional($p->inscription->eleve)->nom }}
                    {{ optional($p->inscription->eleve)->prenom }}
                </td>
                <td>{{ optional($p->inscription->classe)->nom }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">Aucun participant trouvé</td>
            </tr>
        @endforelse
    </tbody>
</table>
    <h4>Épreuves</h4>

    <table class="table">
        <thead>
            <tr>
                <th>Matière</th>
                <th>Date</th>
                <th>Heure</th>
            </tr>
        </thead>
        <tbody>
            @foreach($examen->epreuves as $e)
                <tr>
                    <td>{{ $e->matiere->nom }}</td>
                    <td>{{ $e->date }}</td>
                    <td>{{ $e->heure_debut }} - {{ $e->heure_fin }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection