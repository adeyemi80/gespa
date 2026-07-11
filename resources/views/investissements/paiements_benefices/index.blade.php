@extends('tableau.neutre')

@section('content')

<div class="container-fluid py-4">

    <!-- Bouton Retour -->
    <div class="mb-4">
        <button
            onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
            class="btn btn-secondary btn-lg shadow-sm">
            <i class="bi bi-arrow-left-circle"></i> Retour
        </button>
    </div>


    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

        <h2 class="fw-bold mb-0">
            <i class="bi bi-cash-stack text-primary"></i>
            Paiements de bénéfices
        </h2>


        <a href="{{ route('paiements-benefices.create') }}"
           class="btn btn-primary btn-lg shadow-sm">
            <i class="bi bi-plus-circle"></i>
            Nouveau paiement
        </a>

    </div>


    <!-- Message succès -->
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show shadow-sm">

            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif



    <!-- Tableau -->

    <div class="card shadow border-0">

        <div class="card-header bg-primary text-white">

            <h4 class="mb-0 fw-bold">
                <i class="bi bi-table"></i>
                Historique des paiements
            </h4>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                    <tr class="text-center">

                        <th>Date</th>
                        <th>Investisseur</th>
                        <th>Bénéfice (période)</th>
                        <th>Montant</th>
                        <th>Mode</th>
                        <th>Référence</th>
                        <th width="220">Actions</th>

                    </tr>

                    </thead>


                    <tbody>

                    @forelse ($paiements as $paiement)

                        <tr>

                            <td class="fs-5">

                                {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}

                            </td>


                            <td class="fw-semibold fs-5">

                                {{ $paiement->repartition->investissement->investisseur->nom ?? '—' }}

                                {{ $paiement->repartition->investissement->investisseur->prenom ?? '' }}

                            </td>


                            <td class="text-muted fs-5">

                                @if($paiement->repartition->benefice)

                                    {{ \Carbon\Carbon::parse($paiement->repartition->benefice->date_debut)->format('d/m/Y') }}

                                    →

                                    {{ \Carbon\Carbon::parse($paiement->repartition->benefice->date_fin)->format('d/m/Y') }}

                                @else

                                    —

                                @endif

                            </td>


                            <td class="fw-bold text-success fs-5">

                                {{ number_format($paiement->montant,0,',',' ') }}
                                F CFA

                            </td>


                            <td class="fs-5">

                                {{ $paiement->mode_paiement }}

                            </td>


                            <td class="text-muted fs-5">

                                {{ $paiement->reference ?: '—' }}

                            </td>



                            <td class="text-center">


                                <a href="{{ route('paiements-benefices.show',$paiement) }}"
                                   class="btn btn-info btn-sm text-white"
                                   title="Voir">

                                    <i class="bi bi-eye-fill"></i>

                                </a>


                                <a href="{{ route('paiements-benefices.edit',$paiement) }}"
                                   class="btn btn-warning btn-sm"
                                   title="Modifier">

                                    <i class="bi bi-pencil-fill"></i>

                                </a>



                                <form action="{{ route('paiements-benefices.destroy',$paiement) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Confirmer la suppression de ce paiement ?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            title="Supprimer">

                                        <i class="bi bi-trash-fill"></i>

                                    </button>

                                </form>


                            </td>


                        </tr>


                    @empty


                        <tr>

                            <td colspan="7"
                                class="text-center py-5 text-muted fs-4">

                                <i class="bi bi-inbox"></i>
                                <br>
                                Aucun paiement de bénéfice enregistré.

                            </td>

                        </tr>


                    @endforelse


                    </tbody>


                </table>


            </div>


        </div>


    </div>



    <!-- Pagination -->

    <div class="d-flex justify-content-center mt-4">

        {{ $paiements->links() }}

    </div>


</div>

@endsection