@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">

    <h3>➕ Créer un utilisateur</h3>

    <form method="POST" action="{{ route('profil.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Nom</label>
                <input type="text" name="nom" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Prénom</label>
                <input type="text" name="prenom" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Rôle</label>
                <select name="role" class="form-select" required>
                    <option value="">--Choisir--</option>
                    @foreach(['admin','secretaire','comptable','enseignant','directeur','censeur','surveillant','parent'] as $role)
                        <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Mot de passe</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Photo</label>
                <input type="file" name="photo" class="form-control">
            </div>
        </div>

        <button class="btn btn-success">💾 Enregistrer</button>
        <a href="{{ route('profil.index') }}" class="btn btn-secondary">Retour</a>

    </form>

</div>
@endsection