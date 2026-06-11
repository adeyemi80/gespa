@extends('tableau.neutre')

@section('title', 'Prévisualisation des matières')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-4">

    {{-- Messages flash --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('importees'))
        <div class="alert alert-info">
            {{ session('importees') }} matières importées.
            @if(session('dejaExistantes') && count(session('dejaExistantes')) > 0)
                <br>Matières déjà existantes : {{ implode(', ', session('dejaExistantes')) }}
            @endif
        </div>
    @endif

    <h4>Prévisualisation des matières pour la classe {{ $classe->nom }}</h4>

    @if(count($rows) > 0)
        <form action="{{ route('matieres.inserer') }}" method="POST">
            @csrf

            <table class="table table-bordered mt-3">
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
                            @foreach($row as $cell)
                                <td>
                                    <input type="text" name="rows[{{ $i }}][]" value="{{ $cell }}" class="form-control" readonly>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Champs cachés --}}
            <input type="hidden" name="classe_id" value="{{ $classe_id }}">
            <input type="hidden" name="annee_id" value="{{ $annee_id }}">

            <button type="submit" class="btn btn-success">Importer les matières</button>
            <a href="{{ route('matieres.import') }}" class="btn btn-secondary ms-2">Retour à l'import</a>
        </form>
    @else
        <div class="alert alert-warning">Aucune matière trouvée dans le fichier.</div>
        <a href="{{ route('matieres.import') }}" class="btn btn-secondary mt-2">Retour à l'import</a>
    @endif

</div>
@endsection