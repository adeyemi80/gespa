@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
    @livewire(\App\Livewire\TdPaiementManager::class)
     @livewire(\App\Livewire\TdPresenceManager::class)
      @livewire(\App\Livewire\TdRecapManager::class)
@endsection