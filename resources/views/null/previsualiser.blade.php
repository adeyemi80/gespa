@extends('classes.layout')

@section('content')
<div class="container">
    <h3>Prévisualisation des données &ndash; Importation {{ ucfirst($type) }}</h3>

    {{-- Affichage des erreurs par ligne --}}
    @if(!empty($errors))
        <div class="alert alert-danger">
            <h5>⚠️ {{ count($errors) }} ligne(s) contiennent des erreurs :</h5>
            <ul>
                @foreach($errors as $erreur)
                    <li>
                        <strong>Ligne {{ $erreur['ligne'] }} :</strong>
                        <ul>
                            @foreach($erreur['messages'] as $msg)
                                <li>{{ $msg }}</li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Table des lignes valides --}}
    @if(count($rows) > 0)
    <form action="{{ route($type . '.import.confirmer') }}" method="POST">
        @csrf
        @if(isset($classe_id))
            <input type="hidden" name="classe_id" value="{{ $classe_id }}">
        @endif
        @if(isset($annee_id))
            <input type="hidden" name="annee_id" value="{{ $annee_id }}">
        @endif
        <input type="hidden" name="fichier" value="{{ $fichier }}">
        <input type="hidden" name="extension" value="{{ $extension }}">
        <input type="hidden" name="type" value="{{ $type }}">

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        @foreach(array_keys($rows[0]) as $col)
                            <th>{{ ucfirst($col) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                        <tr>
                            @foreach($row as $col => $val)
                                <td>{{ $val }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-center mt-3">
            @if(count($errors) > 0)
                <div class="alert alert-danger">
                    ⚠️ {{ count($errors) }} ligne(s) contiennent des erreurs. Corrigez-les dans le fichier Excel et rechargez-le.
                </div>
                <a href="{{ route($type . '.import.form') }}" class="btn btn-secondary">⬅ Retour</a>
            @else
                <button type="submit" class="btn btn-success">✅ Confirmer l'importation</button>
                <a href="{{ route($type . '.import.form') }}" class="btn btn-secondary">Annuler</a>
            @endif
        </div>
    </form>
    @else
        <p>Aucune donnée à afficher </p>
    @endif
</div>
@endsection
