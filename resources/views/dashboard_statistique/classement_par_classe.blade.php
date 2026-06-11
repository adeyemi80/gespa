@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">

<h3>🏫 Classement par classe</h3>
<a href="{{ route('classement.par.classe.pdf', [
    'annee_id' => $annee_id,
    'trimestre_id' => $trimestre_id
]) }}"
class="btn btn-danger mb-3">
    📄 Export PDF
</a>
{{-- 🔎 FILTRES --}}
<form method="GET" class="row mb-4">

    {{-- ANNÉE --}}
    <div class="col-md-5">
        <select name="annee_id" class="form-control" required>
            <option value="">-- Année --</option>
            @foreach($annees as $annee)
                <option value="{{ $annee->id }}"
                    {{ ($annee_id ?? '') == $annee->id ? 'selected' : '' }}>
                    {{ $annee->nom ?? 'Année '.$annee->id }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- TRIMESTRE --}}
    <div class="col-md-5">
        <select name="trimestre_id" class="form-control">
            <option value="">-- Trimestre (optionnel) --</option>
            @foreach($trimestres as $trimestre)
                <option value="{{ $trimestre->id }}"
                    {{ ($trimestre_id ?? '') == $trimestre->id ? 'selected' : '' }}>
                    {{ $trimestre->nom ?? 'Trimestre '.$trimestre->id }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- BOUTON --}}
    <div class="col-md-2">
        <button class="btn btn-primary w-100">
            🔍
        </button>
    </div>

</form>

<hr>

{{-- 🏫 CLASSEMENTS --}}
@foreach($classes as $data)

    <div class="card mb-4 p-3">

        <h4 class="text-primary">
            {{ $data['classe']->nom ?? 'Classe' }}
        </h4>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Rang</th>
                    <th>Élève</th>
                    <th>Moyenne</th>
                </tr>
            </thead>

            <tbody>
                @foreach($data['eleves'] as $i => $ins)
                <tr>

                    {{-- 🏆 RANG --}}
                    <td>
                        {{ $i + 1 }}
                        @if($i == 0) 🥇
                        @elseif($i == 1) 🥈
                        @elseif($i == 2) 🥉
                        @endif
                    </td>

                    {{-- 👨‍🎓 ÉLÈVE --}}
                    <td>
                        {{ $ins->eleve->nom ?? '' }} {{ $ins->eleve->prenom ?? '' }}
                    </td>

                    {{-- 📊 MOYENNE --}}
                    <td class="{{ $ins->moyenne >= 10 ? 'text-success' : 'text-danger' }}">
                        {{ $ins->moyenne }}
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

@endforeach

</div>

<script>
document.querySelectorAll('select').forEach(select => {
    select.addEventListener('change', () => {
        select.form.submit();
    });
});
</script>

@endsection