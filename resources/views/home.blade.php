@extends('classes.layout')

@section('title', 'Bienvenue')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- Carte de bienvenue -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Bienvenue {{ Auth::user()->name }} 👋</h5>
                    @if(Auth::user()->photo)
                        <img src="{{ asset('storage/photos/' . Auth::user()->photo) }}" alt="Photo de profil"
                             class="rounded-circle" width="45" height="45">
                    @else
                        <i class="bi bi-person-circle fs-2 text-white"></i>
                    @endif
                </div>

                <div class="card-body">
                    <p class="lead">Heureux de vous revoir sur notre plateforme.</p>

                    <div class="row mt-4">

                        <!-- Lien profil -->
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('profil.index') }}" class="text-decoration-none">
                                <div class="card border-primary h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-person-lines-fill fs-1 text-primary"></i>
                                        <h6 class="mt-2">Mon Profil</h6>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Lien déconnexion -->
                        <div class="col-md-6 mb-3">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="card border-danger h-100 w-100 btn p-0" type="submit">
                                    <div class="card-body text-center">
                                        <i class="bi bi-box-arrow-right fs-1 text-danger"></i>
                                        <h6 class="mt-2">Se déconnecter</h6>
                                    </div>
                                </button>
                            </form>
                        </div>

                    </div>

                    <!-- Message ou lien supplémentaire -->
                    <div class="text-center mt-4">
                        <small class="text-muted">Merci d’utiliser notre application.</small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
