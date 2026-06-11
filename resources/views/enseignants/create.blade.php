@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">➕ Ajouter un Enseignant</h5>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Veuillez corriger les erreurs ci-dessous :</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('enseignants.store') }}" method="POST">
                        @csrf

                        {{-- Contenu du formulaire extrait dans partial --}}
                        @include('enseignants.form')

                        <div class="text-end mt-4">
                            <a href="{{ route('enseignants.index') }}" class="btn btn-secondary">↩️ Annuler</a>
                            <button type="submit" class="btn btn-success">✅ Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
