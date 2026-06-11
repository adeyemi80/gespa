@extends('tableau.neutre')

@section('content')
<!-- resources/views/conduites/import.blade.php -->

<form action="{{ route('conduites.previsualiser') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div>
        <label for="classe_id">Classe :</label>
        <select name="classe_id" id="classe_id" required>
            @foreach($classes as $classe)
                <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="trimestre_id">Trimestre :</label>
        <select name="trimestre_id" id="trimestre_id" required>
            @foreach($trimestres as $trimestre)
                <option value="{{ $trimestre->id }}">{{ $trimestre->nom }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="fichier">Fichier (.xlsx, .xls, .csv) :</label>
        <input type="file" name="fichier" id="fichier" accept=".xlsx,.xls,.csv" required>
    </div>

    <button type="submit">Prévisualiser</button>
</form>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const classeSelect = document.getElementById('classe_id');
        const btnModele = document.getElementById('btn-modele');

        classeSelect.addEventListener('change', function () {
            const classeId = this.value;
            if (classeId) {
                btnModele.href = `/conduites/template/${classeId}`;
                btnModele.classList.remove('disabled');
                btnModele.setAttribute('aria-disabled', 'false');
                btnModele.removeAttribute('tabindex');
                btnModele.removeAttribute('role');
            } else {
                btnModele.href = '#';
                btnModele.classList.add('disabled');
                btnModele.setAttribute('aria-disabled', 'true');
                btnModele.setAttribute('tabindex', '-1');
                btnModele.setAttribute('role', 'button');
            }
        });
    });
</script>
@endsection
