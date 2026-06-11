@extends('classes.layout')

@section('content')
<div class="container py-5">

    <h4 class="mb-4">🔍 Prévisualisation des Notes Importées</h4>

    {{-- ✅ Affichage des lignes valides avec formulaire d'enregistrement --}}
    <h5>Nombre de lignes valides : {{ count($valides ?? []) }}</h5>
    
    @if(isset($valides) && count($valides ?? []) > 0)
    <form action="{{ route('notes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Champs cachés pour le contexte --}}
        <input type="hidden" name="matiere_id" value="{{ $matiere_id ?? '' }}">
        <input type="hidden" name="annee_id" value="{{ $annee_id ?? '' }}">
        <input type="hidden" name="trimestre_id" value="{{ $trimestre_id ?? '' }}">

        <div class="alert alert-success">
            ✅ <strong>{{ count($valides ?? []) }}</strong> ligne(s) valide(s)
        </div>

        <div class="table-responsive mb-3">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Matricule</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Moyenne d'Interro</th>
                        <th>Devoir 1</th>
                        <th>Devoir 2</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($valides as $i => $ligne)
                    <tr>
                        <td>
                            {{ $ligne['matricule'] }}
                            <input type="hidden" name="notes[{{ $i }}][matricule]" value="{{ $ligne['matricule'] }}">
                        </td>
                      <td>{{ $ligne['nom'] ?? '' }}</td>
                        <td>{{ $ligne['prenom'] ?? '' }}</td>
                        <td>
                            {{ $ligne['moyenne_interro'] }}
                            <input type="hidden" name="notes[{{ $i }}][moyenne_interro]" value="{{ $ligne['moyenne_interro'] }}">
                        </td>
                        <td>
                            {{ $ligne['devoir1'] }}
                            <input type="hidden" name="notes[{{ $i }}][devoir1]" value="{{ $ligne['devoir1'] }}">
                        </td>
                        <td>
                            {{ $ligne['devoir2'] }}
                            <input type="hidden" name="notes[{{ $i }}][devoir2]" value="{{ $ligne['devoir2'] }}">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">
                💾 Valider et Enregistrer
            </button>
        </div>
    </form>
    @endif

    {{-- ❌ Affichage des lignes invalides --}}
    @if(isset($invalides) && count($invalides ?? []) > 0)
    <div class="alert alert-danger mt-4">
        ❌ <strong>{{ count($invalides ?? []) }}</strong> ligne(s) invalide(s)
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle">
            <thead class="table-danger">
                <tr>
                    <th>Ligne</th>
                    <th>Données</th>
                    <th>Erreur(s)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invalides as $index => $item)
                <tr>
                    <td>#{{ $index + 1 }}</td>
                    <td>
                        <pre class="mb-0">{{ json_encode($item['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </td>
                    <td>
                        <ul class="mb-0">
                            @foreach($item['erreurs'] as $erreur)
                                <li>{{ $erreur }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
@endsection
