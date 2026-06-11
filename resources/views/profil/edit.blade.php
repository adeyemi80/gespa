@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">

    <h3>✏️ Modifier le profil</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('profil.update', $user->id) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Nom</label>
                <input type="text" name="nom" value="{{ $user->nom }}" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Prénom</label>
                <input type="text" name="prenom" value="{{ $user->prenom }}" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" name="email" value="{{ $user->email }}" class="form-control">
            </div>
             <div class="col-md-6 mb-3">
    <label class="form-label">Rôle</label>

    <input 
        type="text"
        name="role"
        value="{{ $user->role }}"
        class="form-control"
        readonly
    >
</div>

          <div class="col-md-6 mb-3">
    <label>Nouveau mot de passe</label>
    <div class="input-group">
        <input type="password" name="password" id="password" class="form-control">
        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', this)">
            👁️
        </button>
    </div>
</div>

<div class="col-md-6 mb-3">
    <label>Confirmer mot de passe</label>
    <div class="input-group">
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation', this)">
            👁️
        </button>
    </div>
</div>

            <div class="col-md-6 mb-3">
                <label>Photo</label>
                <input type="file" name="photo" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Photo actuelle</label><br>
                <img src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('images/default.png') }}"
                     width="80" class="rounded">
            </div>
        </div>

        <button class="btn btn-primary">💾 Mettre à jour</button>
        <a href="{{ route('tableau.accueil') }}" class="btn btn-secondary">Retour</a>

    </form>

</div>
<script>
function togglePassword(fieldId, button) {
    let input = document.getElementById(fieldId);

    if (input.type === "password") {
        input.type = "text";
        button.innerHTML = "🙈"; // mot de passe visible
    } else {
        input.type = "password";
        button.innerHTML = "👁️"; // mot de passe caché
    }
}
</script>
@endsection