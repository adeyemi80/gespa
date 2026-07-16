@extends('tableau.neutre')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            {{ $type->nom }}
            <span class="badge bg-secondary align-middle">{{ $type->code }}</span>
            @if ($type->actif)
                <span class="badge bg-success align-middle">Actif</span>
            @else
                <span class="badge bg-secondary align-middle">Inactif</span>
            @endif
        </h1>
        <div>
            <a href="{{ route('types-depenses.edit', $type) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <a href="{{ route('types-depenses.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h6 class="text-muted">Catégorie</h6>
            <p>
                <a href="{{ route('categories-depenses.show', $type->categorie) }}">
                    {{ $type->categorie->nom }}
                </a>
            </p>

            @if ($type->description)
                <h6 class="text-muted">Description</h6>
                <p class="mb-0">{{ $type->description }}</p>
            @endif
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <span class="fw-semibold">Dépenses récentes rattachées à ce type</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>N° pièce</th>
                        <th>Libellé</th>
                        <th class="text-end">Montant</th>
                        <th>Date</th>
                        <th class="text-center">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($type->depenses()->latest('date_depense')->take(10)->get() as $depense)
                        <tr>
                            <td>{{ $depense->numero_piece }}</td>
                            <td>{{ $depense->libelle }}</td>
                            <td class="text-end">{{ number_format($depense->montant, 0, ',', ' ') }} FCFA</td>
                            <td>{{ $depense->date_depense->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ ucfirst($depense->statut) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Aucune dépense rattachée à ce type pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection