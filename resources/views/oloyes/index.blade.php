@extends('tableau.neutre')

@section('content')

<div class="container-fluid py-4">

    <!-- Titre -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold text-primary">
                🏠 OLOYE
            </h1>
            <p class="text-muted mb-0">
                Gestion des dépenses 
            </p>
        </div>

        <a href="{{ route('oloyes.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle"></i>
            Nouvelle dépense
        </a>
        <a href="{{ route('oloyes.pdf') }}"
   class="btn btn-danger btn-lg">

    <i class="bi bi-file-earmark-pdf"></i>
    Exporter PDF

</a>
    </div>

    <!-- Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Carte Total -->
    <div class="row mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total des dépenses
                    </h6>

                    <h2 class="text-danger fw-bold">

                        {{ number_format($total,0,',',' ') }}

                        FCFA

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card border-0 shadow">

                <div class="card-body">

                    <h6 class="text-muted">
                        Nombre de dépenses
                    </h6>

                    <h2 class="fw-bold text-primary">

                        {{ $oloyes->total() }}

                    </h2>

                </div>

            </div>

        </div>

    </div>

    <!-- Tableau -->

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">

                Liste des dépenses

            </h5>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                    <tr>

                        <th>#</th>

                        <th>Date</th>

                        <th>Libellé</th>

                        <th>Catégorie</th>

                        <th>Bénéficiaire</th>

                        <th class="text-end">
                            Montant
                        </th>

                        <th width="180">
                            Actions
                        </th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($oloyes as $depense)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($depense->date)->format('d/m/Y') }}
                            </td>

                            <td>

                                {{ $depense->libelle }}

                            </td>

                            <td>

                                {{ $depense->categorie }}

                            </td>

                            <td>

                                {{ $depense->beneficiaire }}

                            </td>

                            <td class="text-end fw-bold text-danger">

                                {{ number_format($depense->montant,0,',',' ') }}

                            </td>

                           <td class="text-nowrap">

    <a href="{{ route('oloyes.show', $depense) }}"
       class="btn btn-info btn-sm"
       title="Voir">
        <i class="bi bi-eye-fill"></i>
    </a>

    <a href="{{ route('oloyes.edit', $depense) }}"
       class="btn btn-warning btn-sm"
       title="Modifier">
        <i class="bi bi-pencil-square"></i>
    </a>

    <form action="{{ route('oloyes.destroy', $depense) }}"
          method="POST"
          class="d-inline">

        @csrf
        @method('DELETE')

        <button type="submit"
                class="btn btn-danger btn-sm"
                title="Supprimer"
                onclick="return confirm('Supprimer cette dépense ?')">
            <i class="bi bi-trash-fill"></i>
        </button>

    </form>

</td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="text-center">

                                Aucune dépense enregistrée.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $oloyes->links() }}

            </div>

        </div>

    </div>

</div>

@endsection