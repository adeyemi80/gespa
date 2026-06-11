@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">
    <h3>Modifier utilisateur</h3>

    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')

        {{-- Nom --}}
        <input type="text" name="nom" value="{{ $user->nom }}" class="form-control mb-2">

        {{-- PréNom --}}
        <input type="text" name="prenom" value="{{ $user->prenom }}" class="form-control mb-2">

        {{-- Email --}}
        <input type="email" name="email" value="{{ $user->email }}" class="form-control mb-2">

        {{-- Rôle --}}
        <select name="role" class="form-control mb-2">
            @foreach(['admin','directeur','censeur','parent','enseignant','surveillant','secretaire','comptable'] as $role)
                <option value="{{ $role }}" @selected($user->role === $role)>
                    {{ ucfirst($role) }}
                </option>
            @endforeach
        </select> 

        {{-- Mot de passe --}}
        <div class="input-group mb-2">
            <input type="password" id="password" name="password" class="form-control"
                   placeholder="Nouveau mot de passe (optionnel)">
            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                <i class="fa fa-lock"></i>
            </button>
        </div>

        {{-- Confirmation --}}
        <div class="input-group mb-3">
            <input type="password" id="password_confirmation" name="password_confirmation"
                   class="form-control"
                   placeholder="Confirmer le nouveau mot de passe (optionnel)">
            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                <i class="fa fa-lock"></i>
            </button>
        </div>

        {{-- Bouton --}}
        <button class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-lock');
            icon.classList.add('fa-unlock');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-unlock');
            icon.classList.add('fa-lock');
        }
    }

    document.getElementById('togglePassword').addEventListener('click', function () {
        togglePassword('password', this);
    });

    document.getElementById('togglePasswordConfirm').addEventListener('click', function () {
        togglePassword('password_confirmation', this);
    });
</script>
@endpush