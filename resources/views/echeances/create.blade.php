@extends('classes.layout')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">

    <h4 class="mb-3">➕ Ajouter des échéances – {{ $frais->nom }}</h4>

    <form action="{{ route('frais.echeances.store', $frais->id) }}" method="POST">
        @csrf

        <div class="card shadow-sm">
            <div class="card-body">

                <table class="table" id="echeances-table">
                    <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Montant</th>
                        <th>Date limite</th>
                        <th width="50"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><input name="echeances[0][nom]" class="form-control" required></td>
                        <td><input name="echeances[0][montant]" type="number" class="form-control" required></td>
                        <td><input name="echeances[0][date_limite]" type="date" class="form-control" required></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>

                <button type="button" class="btn btn-secondary mb-3" onclick="addRow()">➕ Ajouter ligne</button>

                <div class="text-end">
                    <a href="{{ route('frais.echeances.index', $frais->id) }}" class="btn btn-light">Annuler</a>
                    <button class="btn btn-success">💾 Enregistrer</button>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
let index = 1;

function addRow() {
    const tbody = document.querySelector('#echeances-table tbody');
    const tr = document.createElement('tr');

    tr.innerHTML = `
        <td><input name="echeances[${index}][nom]" class="form-control" required></td>
        <td><input name="echeances[${index}][montant]" type="number" class="form-control" required></td>
        <td><input name="echeances[${index}][date_limite]" type="date" class="form-control" required></td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">✖</button></td>
    `;

    tbody.appendChild(tr);
    index++;
}
</script>
@endsection
