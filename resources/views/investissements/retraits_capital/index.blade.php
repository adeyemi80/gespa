```blade
@extends('tableau.neutre')

@section('content')

<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary btn-lg mb-3">
    ⬅️ Retour
</button>


<div class="container-fluid py-4">

    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="fw-bold text-dark">
            <i class="fas fa-hand-holding-usd text-danger me-2"></i>
            Gestion des retraits de capital
        </h2>

        <a href="{{ route('retraits-capital.create') }}"
           class="btn btn-danger btn-lg">

            <i class="fas fa-plus-circle me-1"></i>
            Nouveau retrait

        </a>

    </div>


    <!-- Messages -->
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show fs-5">

            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}

            <button class="btn-close" data-bs-dismiss="alert"></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show fs-5">

            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}

            <button class="btn-close" data-bs-dismiss="alert"></button>

        </div>

    @endif



    <!-- Recherche -->
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-light">

            <h4 class="mb-0 fw-bold">
                <i class="fas fa-search me-2"></i>
                Recherche
            </h4>

        </div>


        <div class="card-body">

            <form method="GET"
                  action="{{ route('retraits-capital.index') }}">

                <div class="row">

                    <div class="col-md-9 mb-3">

                        <label class="form-label fs-5 fw-semibold">
                            Investisseur
                        </label>


                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control form-control-lg"
                               placeholder="Nom, prénom ou téléphone">


                    </div>


                    <div class="col-md-3 mb-3 d-flex align-items-end">


                        <button class="btn btn-dark btn-lg me-2">

                            <i class="fas fa-search me-1"></i>
                            Rechercher

                        </button>



                        @if(request()->filled('search'))

                            <a href="{{ route('retraits-capital.index') }}"
                               class="btn btn-secondary btn-lg">

                                <i class="fas fa-sync-alt"></i>

                            </a>

                        @endif


                    </div>

                </div>


            </form>

        </div>

    </div>




    <!-- Tableau -->

    <div class="card shadow-sm border-0">


        <div class="card-header bg-danger text-white">

            <div class="d-flex justify-content-between align-items-center">

                <h4 class="mb-0 fw-bold">

                    <i class="fas fa-list me-2"></i>
                    Liste des retraits

                </h4>


                <span class="badge bg-light text-dark fs-6">

                    {{ $retraits->total() }} retrait(s)

                </span>


            </div>

        </div>



        <div class="card-body p-0">


            <div class="table-responsive">


                <table class="table table-hover table-striped align-middle fs-5 mb-0">


                    <thead class="table-light">


                        <tr>

                            <th>Date</th>
                            <th>Investisseur</th>
                            <th>Investissement</th>
                            <th class="text-end">Montant</th>
                            <th>Mode</th>
                            <th>Motif</th>
                            <th class="text-center">Actions</th>

                        </tr>


                    </thead>



                    <tbody>


                    @forelse($retraits as $retrait)


                        <tr>


                            <td>

                                {{ \Carbon\Carbon::parse($retrait->date_retrait)->format('d/m/Y') }}

                            </td>



                            <td class="fw-semibold">

                                {{ $retrait->investissement->investisseur->nom ?? '-' }}

                                {{ $retrait->investissement->investisseur->prenom ?? '' }}

                            </td>



                            <td>

                                <span class="badge bg-primary fs-6">

                                    #{{ $retrait->investissement_id }}

                                </span>

                            </td>



                            <td class="text-end fw-bold text-danger">

                                {{ number_format($retrait->montant,0,',',' ') }}

                                F CFA

                            </td>



                            <td>


                                @if($retrait->mode_retrait)

                                    <span class="badge bg-secondary fs-6">

                                        {{ $retrait->mode_retrait }}

                                    </span>

                                @else

                                    -

                                @endif


                            </td>



                            <td>

                                {{ $retrait->motif ?: '-' }}

                            </td>



                            <td class="text-center">


                                <div class="btn-group">


                                    <a href="{{ route('retraits-capital.show',$retrait) }}"
                                       class="btn btn-info btn-lg text-white">

                                        <i class="fas fa-eye"></i>

                                    </a>



                                    <a href="{{ route('retraits-capital.edit',$retrait) }}"
                                       class="btn btn-warning btn-lg">

                                        <i class="fas fa-edit"></i>

                                    </a>



                                    <form action="{{ route('retraits-capital.destroy',$retrait) }}"
                                          method="POST"
                                          onsubmit="return confirm('Confirmer la suppression de ce retrait ?')">

                                        @csrf
                                        @method('DELETE')


                                        <button class="btn btn-danger btn-lg">

                                            <i class="fas fa-trash"></i>

                                        </button>


                                    </form>


                                </div>


                            </td>


                        </tr>


                    @empty


                        <tr>

                            <td colspan="7"
                                class="text-center py-5 fs-4 text-muted">


                                <i class="fas fa-folder-open fa-3x mb-3"></i>

                                <br>

                                Aucun retrait de capital trouvé.


                            </td>


                        </tr>


                    @endforelse


                    </tbody>


                </table>


            </div>


        </div>



        @if($retraits->hasPages())

            <div class="card-footer">

                {{ $retraits->appends(request()->query())->links() }}

            </div>

        @endif


    </div>


</div>


@endsection
```
