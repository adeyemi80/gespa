@extends('classes.layout')

@section('title', 'Bienvenue')

@section('content')
<h1>Bulletin scolaire</h1>

<table border="1" cellpadding="5">
    <tr>
        <th>Trimestre</th>
        <th>Moyenne</th>
    </tr>
    @foreach($moyennesTrimestres as $trimestre => $moyenne)
        <tr>
            <td>{{ $trimestre }}</td>
            <td>{{ $moyenne ?? 'N/A' }}</td>
        </tr>
    @endforeach
    <tr>
        <th>Moyenne Annuelle</th>
        <th>{{ $moyenneAnnuelle ?? 'N/A' }}</th>
    </tr>
</table>
@endsection