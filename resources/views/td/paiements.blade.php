@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">

    <div class="card shadow-sm">
        <div class="card-body">

            <h3 class="mb-3 text-primary">
                Paiements TD — {{ $session->classe->nom }}
            </h3>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <p>
                <strong>Date :</strong>
                {{ \Carbon\Carbon::parse($session->date_td)->format('d/m/Y') }}
            </p>

            {{-- FRAIS --}}
            <div class="alert alert-info">
                @foreach(['seance' => 'séance', 'mois' => 'mois', 'annee' => 'année'] as $k => $label)
                    @if(isset($fraisTd[$k]))
                        <p class="mb-1">
                            <strong>Frais TD par {{ $label }} :</strong>
                            {{ number_format($fraisTd[$k]->montant, 0, ',', ' ') }} FCFA
                        </p>
                    @endif
                @endforeach
            </div>

            <form action="{{ route('td.enregistrer-paiements') }}" method="POST">
                @csrf

                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Élève</th>
                            <th class="text-center">Payé ?</th>
                            <th class="text-center">Type de frais</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participants as $part)
                        <tr>
                            <td>
                                {{ $part->inscription->eleve->nom }}
                                {{ $part->inscription->eleve->prenom }}
                            </td>

                            <td class="text-center">
                                <input
                                    type="checkbox"
                                    name="paiements[{{ $part->id }}]"
                                    class="form-check-input paiement-check"
                                >
                            </td>

                            <td>
                                <select
                                    name="types[{{ $part->id }}]"
                                    class="form-select form-select-sm type-select"
                                    disabled
                                >
                                    <option value="">-- Choisir --</option>
                                    @foreach(['seance' => 'Séance', 'mois' => 'Mensuel', 'annee' => 'Annuel'] as $k => $label)
                                        @if($fraisTd->has($k))
                                            <option value="{{ $k }}">{{ $label }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('td.index') }}" class="btn btn-secondary">⬅ Retour</a>
                    <button class="btn btn-success">💾 Enregistrer</button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- JS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checks  = document.querySelectorAll('.paiement-check');
    const selects = document.querySelectorAll('.type-select');

    checks.forEach((check, index) => {
        check.addEventListener('change', function () {
            const select = selects[index];

            if (this.checked) {
                select.disabled = false;
                select.required = true;
            } else {
                select.value = '';
                select.required = false;
                select.disabled = true;
            }
        });
    });
});
</script>
@endsection