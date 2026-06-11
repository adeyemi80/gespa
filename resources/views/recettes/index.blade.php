@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <h3 class="mb-4 text-primary">📄 Liste des Recettes</h3>
    {{-- Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 w-50 text-center" style="z-index:1050">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    {{-- Filtres --}}
    <form method="GET" action="{{ route('recettes.index') }}">
        <div class="card mb-4 shadow-sm">
            <div class="card-body row g-3">

                {{-- Année --}}
                <div class="col-md-4">
                    <select name="annee_id" id="annee_filter" class="form-select">
                        <option value="">-- Année scolaire --</option>
                        @foreach($annees as $annee)
                            <option value="{{ $annee->id }}"
                                {{ request('annee_id') == $annee->id ? 'selected' : '' }}>
                                {{ $annee->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Classe --}}
                <div class="col-md-4">
                    <select name="classe_id" id="classe_filter" class="form-select">
                        <option value="">-- Classe --</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}"
                                {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Élève --}}
                <div class="col-md-4">
                    <select name="inscription_id" id="eleve_filter" class="form-select">
                        <option value="">-- Élève --</option>
                        @if(isset($inscriptions))
                            @foreach($inscriptions as $i)
                                <option value="{{ $i->id }}"
                                    {{ request('inscription_id') == $i->id ? 'selected' : '' }}>
                                    {{ $i->eleve->nom }} {{ $i->eleve->prenom }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Boutons --}}
                <div class="col-12 text-end">
                    <button class="btn btn-primary">
                        <i class="bi bi-funnel"></i> Filtrer
                    </button>
                    <a href="{{ route('recettes.index') }}" class="btn btn-secondary">
                        Réinitialiser
                    </a>
                </div>

            </div>
        </div>
    </form>

    {{-- Tableau des recettes --}}
    <div class="card shadow border-0">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Élève</th>
                        <th>Classe</th>
                        <th>Année</th>
                        <th>Frais</th>
                        <th>Montant</th>
                        <th>Mode</th>
                        <th>Date</th>
                        <th>Reçu</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($recettes as $recette)
                        <tr>
                            <td>{{ $recette->id }}</td>

                            <td>
                                {{ $recette->paiement->inscription->eleve->nom ?? '' }}
                                {{ $recette->paiement->inscription->eleve->prenom ?? '' }}
                            </td>

                            <td>
                                {{ $recette->paiement->inscription->classe->nom ?? '' }}
                            </td>

                            <td>
                                <span class="badge bg-info">
                                    {{ $recette->paiement->inscription->annee->nom ?? '' }}
                                </span>
                            </td>

                            <td>
                                {{ $recette->paiement->frais->description ?? '' }}
                            </td>

                            <td class="fw-bold text-success">
                                {{ number_format($recette->montant_verse, 0, ' ', ' ') }} FCFA
                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ $recette->mode_paiement }}
                                </span>
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($recette->date_paiement)->format('d/m/Y') }}
                            </td>

                            <td>{{ $recette->numero_recu }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                Aucune recette trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $recettes->withQueryString()->links() }}
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
setTimeout(() => $('.alert').fadeOut(), 4000);

// Classe → Élèves (filtrage dynamique)
$('#classe_filter').on('change', function () {
    let classeId = $(this).val();
    let anneeId  = $('#annee_filter').val();

    $('#eleve_filter').html('<option value="">-- Élève --</option>');

    if (classeId && anneeId) {
        $.get(`/classes/${classeId}/inscriptions?annee_id=${anneeId}`, function (data) {
            data.forEach(i => {
                $('#eleve_filter').append(
                    `<option value="${i.id}">${i.eleve.nom} ${i.eleve.prenom}</option>`
                );
            });
        });
    }
});
</script>
@endsection

<style>
body { background:#f1f7ff }
.card { border-radius:10px }
</style>
