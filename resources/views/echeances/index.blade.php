@extends('classes.layout')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">

    <div class="d-flex justify-content-between mb-3">
        <h4>📅 Échéances – {{ $frais->nom }}</h4>

        <a href="{{ route('frais.echeances.create', $frais->id) }}" class="btn btn-primary">
            ➕ Ajouter
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-bordered table-hover">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Montant</th>
                    <th>Date limite</th>
                    <th width="160">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($frais->echeances as $echeance)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $echeance->nom }}</td>
                        <td>{{ number_format($echeance->montant, 0, ',', ' ') }} FCFA</td>
                        <td>{{ \Carbon\Carbon::parse($echeance->date_limite)->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('frais.echeances.show', [$frais->id, $echeance->id]) }}" class="btn btn-sm btn-info">👁</a>
                            <a href="{{ route('frais.echeances.edit', [$frais->id, $echeance->id]) }}" class="btn btn-sm btn-warning">✏</a>

                            <form action="{{ route('frais.echeances.destroy', [$frais->id, $echeance->id]) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Supprimer cette échéance ?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">🗑</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Aucune échéance.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
