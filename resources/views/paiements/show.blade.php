@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <h3 class="mb-4 text-primary">👁️ Détails du Paiement</h3>

    <div class="card shadow border-0">
        <div class="card-body">

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>👨‍🎓 Élève</th>
                        <td>
                            {{ $paiement->inscription->eleve->nom ?? '-' }}
                            {{ $paiement->inscription->eleve->prenom ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>📚 Classe</th>
                        <td>{{ $paiement->inscription->classe->nom ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>📆 Année scolaire</th>
                        <td>{{ $paiement->inscription->annee->nom ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>💸 Frais</th>
                        <td>{{ $paiement->frais->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>💰 Montant versé</th>
                        <td class="fw-bold text-success">
                            {{ number_format($paiement->montant_verse, 0, ',', ' ') }} FCFA
                        </td>
                    </tr>
                    <tr>
                        <th>🏦 Mode de paiement</th>
                        <td>{{ $paiement->mode_paiement }}</td>
                    </tr>
                    <tr>
                        <th>🧾 Numéro de reçu</th>
                        <td>{{ $paiement->numero_recu ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>🗓️ Date de paiement</th>
                        <td>{{ $paiement->date_paiement?->format('d/m/Y') }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('paiements.index') }}" class="btn btn-secondary">
                    ⬅️ Retour
                </a>

                <div>
                    <a href="{{ route('paiements.edit', $paiement->id) }}" class="btn btn-warning">
                        ✏️ Modifier
                    </a>

                    <form action="{{ route('paiements.destroy', $paiement->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Confirmer la suppression ?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">
                            🗑️ Supprimer
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
