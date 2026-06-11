<div class="mt-3">
    <table class="table table-bordered text-center">
        <thead class="table-primary">
            <tr>
                <th>Élève</th>
                <th>Classe</th>
                <th>Moyenne</th>
                <th>Rang</th>
                <th>Mention</th>
            </tr>
        </thead>

        <tbody>
            @forelse($bulletins as $bulletin)
                <tr>
                    <td>{{ $bulletin->inscription->eleve->nom ?? '' }}</td>
                    <td>{{ $bulletin->inscription->classe->nom ?? '' }}</td>
                    <td>{{ $bulletin->moyenne_trimestrielle }}</td>
                    <td>{{ $bulletin->rang_trimestre }}</td>
                    <td>{{ $bulletin->mention ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Aucun bulletin disponible</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>