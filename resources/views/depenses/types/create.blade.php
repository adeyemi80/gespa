@extends('tableau.neutre')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Nouveau type de dépense</h1>
        <a href="{{ route('types-depenses.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card shadow-sm">
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
        <div class="card-body">
            <form method="POST" action="{{ route('types-depenses.store') }}">
                @include('depenses.types.form')
            </form>
        </div>
    </div>

</div>
@endsection