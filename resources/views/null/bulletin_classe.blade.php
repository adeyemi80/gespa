@extends('classes.layout')

@section('title', 'Bienvenue')

@section('content')

<h1>Bulletin - Classe {{ $classe_id }} - Trimestre {{ $trimestre_id }}</h1>

<table border="1" cellpadding="5">
    <tr>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Moyenne</th>
    </tr>
    @foreach($bulletin as $ligne)
        <tr>
            <td>{{ $ligne['nom'] }}</td>
            <td>{{ $ligne['prenom'] }}</td>
            <td>{{ $ligne['moyenne'] ?? 'N/A' }}</td>
        </tr>
    @endforeach
</table>
@endsection