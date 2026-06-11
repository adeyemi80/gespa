@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    <h4 class="mb-4 text-primary">
        📊 Prévisualisation des données – Importation {{ ucfirst($type) }}
    </h4>

    {{-- ⚠️ Erreurs ligne par ligne --}}
    @if(!empty($errors))
        <div class="alert alert-danger">
            <h5 class="fw-bold">⚠️ {{ count($errors) }} ligne(s) contiennent des erreurs :</h5>
            <ul class="mb-0">
                @foreach($errors as $erreur)
                    <li>
                        <strong>Ligne {{ $erreur['ligne'] }} :</strong>
                        <ul class="mb-2">
                            @foreach($erreur['messages'] as $msg)
                                <li>{{ $msg }}</li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ✅ Table des données valides --}}
    @if(count($rows) > 0)
        <form action="{{ route($type . '.import.confirmer') }}" method="POST">
            @csrf

            {{-- Champs cachés pour transfert --}}
            @if(isset($classe_id))
                <input type="hidden" name="classe_id" value="{{ $classe_id }}">
            @endif
            @if(isset($annee_id))
                <input type="hidden" name="annee_id" value="{{ $annee_id }}">
            @endif
            <input type="hidden" name="fichier" value="{{ $fichier }}">
            <input type="hidden" name="extension" value="{{ $extension }}">
            <input type="hidden" name="type" value="{{ $type }}">

            {{-- 📋 Tableau --}}
            <div class="table-responsive mb-4">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            @foreach(array_keys($rows[0]) as $col)
                                <th>{{ ucfirst($col) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $row)
                            <tr>
                                @foreach($row as $val)
                                    <td>{{ $val }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ✅ Actions --}}
            <div class="d-flex justify-content-center gap-3">
                @if(count($errors) > 0)
                    <div class="alert alert-warning w-100 text-center">
                        ⚠️ Veuillez corriger les erreurs dans le fichier Excel et le recharger.
                    </div>
                    <a href="{{ route($type . '.import.form') }}" class="btn btn-outline-secondary">
                        ⬅ Retour à l'importation
                    </a>
                @else
                    <button type="submit" class="btn btn-success">
                        ✅ Confirmer l'importation
                    </button>
                    <a href="{{ route($type . '.import.form') }}" class="btn btn-outline-secondary">
                        ❌ Annuler
                    </a>
                @endif
            </div>
        </form>
    @else
        <div class="alert alert-info">
            ℹ️ Aucune donnée à afficher.
        </div>
    @endif
</div>
@endsection
