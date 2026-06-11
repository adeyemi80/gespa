@extends('tableau.neutre')

@section('content')
<div class="container py-4">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">💰 Frais par élève</h5>
        </div>

        <div class="card-body">

            {{-- ✅ Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- 🚀 Livewire Component --}}
   @livewire(\App\Livewire\InscriptionFraisTable::class)


        </div>
    </div>

</div>
@endsection
