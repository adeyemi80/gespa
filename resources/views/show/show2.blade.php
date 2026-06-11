@extends('tableau.neutre_registre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
@csrf
        @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                {{ $message }}
            </div>
        @endif
<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 80vh; background: #f0f8ff;">
    <div class="text-center">
        <img src="{{ asset('images/74f4308b-78c8-4c7f-b76a-66f9b0fc699d.png') }}" 
             alt="Complexe Scolaire Le Glorieux" 
             class="img-fluid rounded shadow-lg" 
             style="max-width: 600px; border: 5px solid #77dd77;">
        <h1 class="mt-4 text-primary fw-bold" style="font-family: 'Comic Sans MS', cursive;">
            Complexe Scolaire <br> <span class="text-success">Le Glorieux</span>
        </h1>
        <p class="fs-5 text-muted mt-2">Maternelle • Primaire • Secondaire</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // ici tu peux ajouter des animations si besoin
</script>
@endsection