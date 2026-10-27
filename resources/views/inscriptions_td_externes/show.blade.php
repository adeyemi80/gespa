@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container-fluid py-4">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                <i class="fas fa-file-invoice text-primary me-2"></i>
                Détails de l'inscription TD
            </h3>

            <p class="text-muted mb-0">
                Référence : TD-{{ str_pad($inscription->id, 5, '0', STR_PAD_LEFT) }}
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('inscriptions-td-externes.index') }}"
               class="btn btn-outline-secondary rounded-pill">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>

            <a href="{{ route('inscriptions-td-externes.edit', $inscription->id) }}"
               class="btn btn-primary rounded-pill">
                <i class="fas fa-edit me-2"></i> Modifier
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $total = (float) ($inscription->montant_total ?? 0);
        $paye = (float) ($totalPaye ?? 0);
        $reste = max(0, (float) ($resteAPayer ?? ($total - $paye)));
        $taux = $total > 0 ? min(100, ($paye / $total) * 100) : 0;
    @endphp

    {{-- Cartes financières --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Montant total</p>
                            <h4 class="fw-bold mb-0">
                                {{ number_format($total, 0, ',', ' ') }} FCFA
                            </h4>
                        </div>
                        <div class="rounded-circle bg-primary-subtle p-3">
                            <i class="fas fa-file-invoice-dollar fa-lg text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Montant payé</p>
                            <h4 class="fw-bold text-success mb-0">
                                {{ number_format($paye, 0, ',', ' ') }} FCFA
                            </h4>
                        </div>
                        <div class="rounded-circle bg-success-subtle p-3">
                            <i class="fas fa-check-circle fa-lg text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Reste à payer</p>
                            <h4 class="fw-bold {{ $reste > 0 ? 'text-danger' : 'text-success' }} mb-0">
                                {{ number_format($reste, 0, ',', ' ') }} FCFA
                            </h4>
                        </div>
                        <div class="rounded-circle bg-danger-subtle p-3">
                            <i class="fas fa-wallet fa-lg text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Progression du paiement --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0">Progression du paiement</h6>
                <span class="fw-bold">{{ number_format($taux, 1, ',', ' ') }} %</span>
            </div>

            <div class="progress" style="height: 12px;">
                <div class="progress-bar {{ $taux >= 100 ? 'bg-success' : 'bg-primary' }}"
                     role="progressbar"
                     style="width: {{ $taux }}%;"
                     aria-valuenow="{{ $taux }}"
                     aria-valuemin="0"
                     aria-valuemax="100">
                </div>
            </div>

            <div class="d-flex justify-content-between mt-2 small text-muted">
                <span>Payé : {{ number_format($paye, 0, ',', ' ') }} FCFA</span>
                <span>Total : {{ number_format($total, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Informations de l'élève --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-user text-primary me-2"></i>
                        Informations de l'élève
                    </h5>
                </div>

                <div class="card-body p-4 pt-0">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tr>
                                <th class="text-muted" style="width: 40%;">Matricule</th>
                                <td class="fw-semibold">
                                    {{ $inscription->eleve->matricule ?? '—' }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-muted">Nom et prénom</th>
                                <td class="fw-semibold">
                                    {{ $inscription->eleve->nom ?? '—' }}
                                    {{ $inscription->eleve->prenom ?? '' }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-muted">Sexe</th>
                                <td>{{ $inscription->eleve->sexe ?? '—' }}</td>
                            </tr>

                            <tr>
                                <th class="text-muted">Téléphone</th>
                                <td>{{ $inscription->eleve->telephone ?? '—' }}</td>
                            </tr>

                            <tr>
                                <th class="text-muted">Téléphone du parent</th>
                                <td>{{ $inscription->eleve->telephone_parent ?? '—' }}</td>
                            </tr>

                            <tr>
                                <th class="text-muted">Adresse</th>
                                <td>{{ $inscription->eleve->adresse ?? '—' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Informations de l'inscription --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-clipboard-list text-primary me-2"></i>
                        Informations de l'inscription
                    </h5>
                </div>

                <div class="card-body p-4 pt-0">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tr>
                                <th class="text-muted" style="width: 40%;">Année scolaire</th>
                                <td>
                                    {{ $inscription->annee->libelle
                                        ?? $inscription->annee->nom
                                        ?? $inscription->annee_id }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-muted">Classe</th>
                                <td>{{ $inscription->classe->nom ?? '—' }}</td>
                            </tr>

                            <tr>
                                <th class="text-muted">Frais TD</th>
                                <td>
                                    {{ $inscription->frais->nom
                                        ?? $inscription->frais->libelle
                                        ?? 'Frais TD' }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-muted">Date d'inscription</th>
                                <td>
                                    {{ $inscription->date_inscription
                                        ? \Illuminate\Support\Carbon::parse($inscription->date_inscription)->format('d/m/Y')
                                        : '—' }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-muted">Statut</th>
                                <td>
                                    @if($inscription->statut === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($inscription->statut ?? 'Inconnu') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th class="text-muted">Observations</th>
                                <td>{{ $inscription->observations ?: 'Aucune observation' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Historique des paiements --}}
    <div class="card border-0 shadow-sm rounded-4 mt-4">
        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-history text-primary me-2"></i>
                    Historique des paiements
                </h5>

                <span class="badge bg-primary rounded-pill">
                    {{ isset($paiements) ? $paiements->count() : 0 }} paiement(s)
                </span>
            </div>
        </div>

        <div class="card-body p-4 pt-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Montant versé</th>
                            <th>Mode de paiement</th>
                            <th>Référence</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse(($paiements ?? collect()) as $paiement)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ $paiement->date_paiement
                                    ? \Illuminate\Support\Carbon::parse($paiement->date_paiement)->format('d/m/Y')
                                    : ($paiement->created_at?->format('d/m/Y') ?? '—') }}
                            </td>

                            <td class="fw-semibold text-success text-nowrap">
                                {{ number_format((float) ($paiement->montant_verse ?? 0), 0, ',', ' ') }}
                                FCFA
                            </td>

                            <td>
                                {{ $paiement->mode_paiement ?? '—' }}
                            </td>

                            <td>
                                {{ $paiement->reference
                                    ?? $paiement->numero_recu
                                    ?? $paiement->code
                                    ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-info-circle me-2"></i>
                                Aucun paiement enregistré pour cette inscription.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                    @if(isset($paiements) && $paiements->count() > 0)
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="2">Total payé</th>
                                <th colspan="3" class="text-success">
                                    {{ number_format($paye, 0, ',', ' ') }} FCFA
                                </th>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

</div>
@endsection