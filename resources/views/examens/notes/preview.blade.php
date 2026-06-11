@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">
    <h2>Prévisualisation des notes</h2>

    <form method="POST" action="{{ route('examens.notes.import') }}">
        @csrf
        <input type="hidden" name="examen_id" value="{{ $examen_id }}">

        <table class="table table-bordered">
            <thead>
                <tr>
                    @foreach($header as $col)
                        <th>{{ $col }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $i => $row)
                    <tr>
                        @foreach($row as $j => $cell)
                            <td class="{{ $errors[$i][$j] ?? '' ? 'bg-danger text-white' : '' }}">
                                <input type="text" name="data[{{ $i }}][{{ $header[$j] }}]" value="{{ $cell }}">
                                @if(isset($errors[$i][$j]))
                                    <small>{{ $errors[$i][$j] }}</small>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button class="btn btn-success">Valider l'importation</button>
    </form>
</div>
@endsection