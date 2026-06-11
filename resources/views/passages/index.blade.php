@extends('tableau.neutre')
@if(session()->has('success'))
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 8000)"
        x-show="show"
        class="alert alert-success"
    >
        {{ session('success') }}
    </div>
@endif
@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
@if(session()->has('success'))
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 8000)"
        x-show="show"
        class="alert alert-success"
    >
        {{ session('success') }}
    </div>
@endif
    <livewire:passage-wizard />
@endsection