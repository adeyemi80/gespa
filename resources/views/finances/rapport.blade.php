@extends('tableau.neutre')
@section('content')
<button
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
    class="btn btn-secondary">
    ⬅️ Retour
</button>

@livewire(\App\Livewire\Rapports\RapportFinancier::class)
@livewire(\App\Livewire\Rapports\EtatPaiementsScolarite::class)


@endsection