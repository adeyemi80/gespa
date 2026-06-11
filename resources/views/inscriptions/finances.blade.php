@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">

    <h3 class="mb-4 text-primary">
        💳 Situation financière de l’élève
    </h3>

    {{-- Infos élève --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body row">
            <div class="col-md-4">
                <strong>👨‍🎓 Élève :</strong><br>
                {{ $inscription->eleve->nom }} {{ $inscription->eleve->prenom }}
            </div>
            <div class="col-md-4">
                <strong>🏫 Classe :</strong><br>
                {{ $inscription->classe->nom }}
            </div>
            <div class="col-md-4">
                <strong>📅 Année scolaire :</strong><br>
                {{ $inscription->annee->nom }}
            </div>
        </div>
    </div>

    {{-- Tableau finances --}}
    <div class="card shadow border-0">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Frais</th>
                        <th>Montant à payer</th>
                        <th>Montant payé</th>
                        <th>Reste</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($lignes as $ligne)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ $ligne->frais->description }}
                            </td>

                            <td>
                                {{ number_format($ligne->montant_frais, 0, ' ', ' ') }} FCFA
                            </td>

                            <td class="text-success fw-semibold">
                                {{ number_format($ligne->montant_paye, 0, ' ', ' ') }} FCFA
                            </td>

                            <td class="text-danger fw-semibold">
                                {{ number_format($ligne->reste, 0, ' ', ' ') }} FCFA
                            </td>

                            <td>
                                @if($ligne->statut === 'soldé')
                                    <span class="badge bg-success">Soldé</span>
                                @elseif($ligne->statut === 'partiellement_payé')
                                    <span class="badge bg-warning text-dark">Partiel</span>
                                @else
                                    <span class="badge bg-danger">Non payé</span>
                                @endif
                            </td>

                            <td>
                                @if($ligne->reste > 0)
                                    <a href="{{ route('paiements.create', [
                                        'inscription_id' => $inscription->id,
                                        'frais_id' => $ligne->frais_id
                                    ]) }}"
                                    class="btn btn-sm btn-primary">
                                        💰 Payer
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Aucun frais associé à cette inscription.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                {{-- Totaux --}}
                @if($lignes->count())
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="2">TOTAL</td>
                        <td>
                            {{ number_format($lignes->sum('montant_frais'), 0, ' ', ' ') }} FCFA
                        </td>
                        <td class="text-success">
                            {{ number_format($lignes->sum('montant_paye'), 0, ' ', ' ') }} FCFA
                        </td>
                        <td class="text-danger">
                            {{ number_format($lignes->sum('reste'), 0, ' ', ' ') }} FCFA
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>

        </div>
    </div>

    {{-- Bouton retour --}}
    <div class="mt-4">
        <a href="{{ route('inscriptions.index') }}" class="btn btn-secondary">
            ⬅ Retour aux inscriptions
        </a>
    </div>

</div>
@endsection

<style>
body { background:#f1f7ff }
.card { border-radius:10px }
.table th, .table td { vertical-align: middle }
</style>
