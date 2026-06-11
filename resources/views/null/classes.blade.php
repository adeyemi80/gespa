@extends('classes.layout')

@section('content')
<div class="container py-5" style="background-color:#f8f9fa; min-height:100vh;">

    <h4 class="text-primary fw-bold mb-4">📑 Bulletins par Classe</h4>

    {{-- Messages d'erreurs Laravel --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Messages de session (succès / erreur) --}}
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Formulaire de sélection --}}
    <form action="{{ route('bulletins.classe', ['classe_id' => 0, 'trimestre_id' => 0, 'annee_id' => 0]) }}" 
      method="GET" 
      onsubmit="event.preventDefault(); 
               window.location=this.action.replace('/0/', '/' + document.getElementById('classe_id').value + '/') 
                                         .replace('/0/', '/' + document.getElementById('trimestre_id').value + '/') 
                                         .replace('/0', '/' + document.getElementById('annee_id').value);">

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="classe_id" class="form-label fw-bold">Classe</label>
                <select name="classe_id" id="classe_id" class="form-select" required>
                    <option value="">-- Sélectionner une classe --</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="trimestre_id" class="form-label fw-bold">Trimestre</label>
                <select name="trimestre_id" id="trimestre_id" class="form-select" required>
                    <option value="">-- Sélectionner un trimestre --</option>
                    @foreach($trimestres as $trimestre)
                        <option value="{{ $trimestre->id }}">{{ $trimestre->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="annee_id" class="form-label fw-bold">Année scolaire</label>
                <select name="annee_id" id="annee_id" class="form-select" required>
                    <option value="">-- Sélectionner une année --</option>
                    @foreach($annees as $annee)
                        <option value="{{ $annee->id }}">{{ $annee->debut }} - {{ $annee->fin }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if(!isset($bulletins) || count($bulletins) == 0)
    <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Afficher</button>
    </div>
@endif

    </form>

    {{-- Résultats --}}
    @if(isset($bulletins) && count($bulletins) > 0)
    <div class="mb-3" >
        <a href="{{ route('bulletins.classe.pdf', [
            'classe_id' => $bulletins[0]['classe']->id,
            'trimestre_id' => $bulletins[0]['trimestre']->id,
            'annee_id' => $bulletins[0]['annee']->id
        ]) }}" 
        class="btn btn-success">
            Télécharger les bulletins PDF
        </a>
    </div>

    @foreach($bulletins as $index => $data)
        @include('bulletins.templateClasse', $data)
        @if(!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach
@else
    <div class="alert alert-warning mt-4">
        ⚠️ Aucun bulletin trouvé pour les critères sélectionnés.
    </div>
@endif

</div>
@endsection
