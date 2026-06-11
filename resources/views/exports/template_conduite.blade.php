@php echo "\xEF\xBB\xBF"; @endphp
<table>
    <thead>
        <tr>
            <th>Matricule</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Note</th>
        </tr>
    </thead>
    <tbody>
        @foreach($eleves as $eleve)
            <tr>
                <td>{{ $eleve->matricule }}</td>
                <td>{{ $eleve->nom }}</td>
                <td>{{ $eleve->prenom }}</td>
                <td></td> {{-- Note vide à remplir --}}
            </tr>
        @endforeach
    </tbody>
</table>
