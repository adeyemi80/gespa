```blade
@extends('tableau.neutre')

@section('content')

<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary btn-lg mb-3">
    ⬅️ Retour
</button>


<div class="container-fluid py-4">


    <div class="row justify-content-center">


        <div class="col-xl-8 col-lg-10">


            <div class="card shadow border-0">


                <!-- En-tête -->

                <div class="card-header bg-danger text-white py-3">


                    <div class="d-flex justify-content-between align-items-center">


                        <h3 class="mb-0 fw-bold">

                            <i class="fas fa-eye me-2"></i>

                            Détail du retrait de capital

                        </h3>



                        <div>


                            <a href="{{ route('retraits-capital.edit', $retraitCapital) }}"
                               class="btn btn-warning btn-lg me-2">

                                <i class="fas fa-edit"></i>
                                Modifier

                            </a>



                            <a href="{{ route('retraits-capital.index') }}"
                               class="btn btn-light btn-lg">

                                <i class="fas fa-arrow-left"></i>
                                Retour

                            </a>


                        </div>


                    </div>


                </div>




                <!-- Corps -->

                <div class="card-body p-4">


                    <div class="row g-4">



                        <div class="col-md-6">


                            <label class="form-label fw-bold text-secondary fs-5">

                                <i class="fas fa-user me-1"></i>
                                Investisseur

                            </label>


                            <div class="form-control form-control-lg bg-light">

                                {{ $retraitCapital->investissement->investisseur->nom ?? '—' }}

                                {{ $retraitCapital->investissement->investisseur->prenom ?? '' }}

                            </div>


                        </div>





                        <div class="col-md-6">


                            <label class="form-label fw-bold text-secondary fs-5">

                                <i class="fas fa-chart-line me-1"></i>
                                Investissement

                            </label>


                            <div class="form-control form-control-lg bg-light">


                                <span class="badge bg-primary fs-6">

                                    #{{ $retraitCapital->investissement_id }}

                                </span>


                            </div>


                        </div>





                        <div class="col-md-6">


                            <label class="form-label fw-bold text-secondary fs-5">

                                <i class="fas fa-calendar-alt me-1"></i>
                                Date du retrait

                            </label>


                            <div class="form-control form-control-lg bg-light">

                                {{ \Carbon\Carbon::parse($retraitCapital->date_retrait)->format('d/m/Y') }}

                            </div>


                        </div>





                        <div class="col-md-6">


                            <label class="form-label fw-bold text-secondary fs-5">

                                <i class="fas fa-coins me-1"></i>
                                Montant retiré

                            </label>


                            <div class="form-control form-control-lg bg-light fw-bold text-danger">

                                {{ number_format($retraitCapital->montant,0,',',' ') }} F CFA

                            </div>


                        </div>





                        <div class="col-md-6">


                            <label class="form-label fw-bold text-secondary fs-5">

                                <i class="fas fa-credit-card me-1"></i>
                                Mode de retrait

                            </label>


                            <div class="form-control form-control-lg bg-light">


                                @if($retraitCapital->mode_retrait)


                                    <span class="badge bg-secondary fs-6">

                                        {{ $retraitCapital->mode_retrait }}

                                    </span>


                                @else

                                    <span class="text-muted">

                                        Non renseigné

                                    </span>


                                @endif


                            </div>


                        </div>





                        <div class="col-md-6">


                            <label class="form-label fw-bold text-secondary fs-5">

                                <i class="fas fa-receipt me-1"></i>
                                Référence

                            </label>


                            <div class="form-control form-control-lg bg-light">

                                {{ $retraitCapital->reference ?: 'Non renseignée' }}

                            </div>


                        </div>





                        <div class="col-12">


                            <label class="form-label fw-bold text-secondary fs-5">

                                <i class="fas fa-file-signature me-1"></i>
                                Motif

                            </label>


                            <div class="form-control form-control-lg bg-light">

                                {{ $retraitCapital->motif ?: 'Aucun motif renseigné' }}

                            </div>


                        </div>





                        <div class="col-12">


                            <label class="form-label fw-bold text-secondary fs-5">

                                <i class="fas fa-comment-alt me-1"></i>
                                Observation

                            </label>


                            <div class="form-control bg-light fs-5"
                                 style="min-height:150px;">


                                {!! nl2br(e($retraitCapital->observation ?: 'Aucune observation')) !!}


                            </div>


                        </div>



                    </div>


                </div>





                <!-- Pied -->

                <div class="card-footer bg-light">


                    <div class="row fs-6">


                        <div class="col-md-6 text-muted">


                            <i class="fas fa-plus-circle text-success me-1"></i>


                            <strong>Créé le :</strong>


                            {{ $retraitCapital->created_at->format('d/m/Y à H:i') }}


                        </div>




                        <div class="col-md-6 text-md-end text-muted">


                            @if($retraitCapital->updated_at &&
                                $retraitCapital->updated_at->ne($retraitCapital->created_at))


                                <i class="fas fa-edit text-warning me-1"></i>


                                <strong>Modifié le :</strong>


                                {{ $retraitCapital->updated_at->format('d/m/Y à H:i') }}


                            @endif


                        </div>


                    </div>


                </div>


            </div>


        </div>


    </div>


</div>


@endsection
```
