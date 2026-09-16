@extends('tableau.neutre')

@section('title', 'Nouvelle dépense')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Nouvelle dépense</h1>
       <a href="{{ route('depenses.live', ['ouvrir' => 'creation']) }}"
   class="btn btn-info">
    <i class="bi bi-journal-bookmark-fill"></i>
    ENREGISTRER LES DEPENSES
</a>
    </div>
  @if (session('succes'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('succes') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('erreur'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('erreur') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

</div>
@endsection