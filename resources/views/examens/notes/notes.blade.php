@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">
    <h2>Saisie des notes</h2>

    <form action="{{ route('examens.notes.save') }}" method="POST">
        @csrf

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Élève</th>

                    @foreach($examen->epreuves as $epreuve)
                        <th>{{ $epreuve->matiere->nom }}</th>
                    @endforeach

                    <th>Moyenne</th>
                </tr>
            </thead>

            <tbody>
                @foreach($examen->participants as $participant)
                    <tr>
                        <td>
                            {{ $participant->inscription->eleve->nom ?? '' }}
                        </td>

                        @foreach($examen->epreuves as $epreuve)
                            <td>
                                <input type="number" step="0.01"
                                    name="notes[{{ $participant->id }}][{{ $epreuve->id }}]"
                                    class="form-control">
                            </td>
                        @endforeach

                        <td>
                            {{-- Moyenne affichée côté serveur si tu veux --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button class="btn btn-success">Enregistrer les notes</button>
    </form>
</div>
@endsection