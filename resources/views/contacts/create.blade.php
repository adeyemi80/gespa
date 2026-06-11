@extends('tableau.neutre')

@section('content')

<div class="container py-4">

    {{-- Bouton retour --}}
    <div class="mb-4">
        <button 
            onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
            class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Retour
        </button>
    </div>
 @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                {{-- En-tête --}}
                <div class="card-header bg-primary text-white py-3">
                    <h3 class="mb-0 text-center fw-bold">
                        <i class="bi bi-envelope-paper-fill"></i>
                        CONTACTEZ-NOUS
                    </h3>
                </div>

                {{-- Corps --}}
                <div class="card-body p-4 bg-light">

                    <form action="{{ route('contacts.store') }}" method="POST">
                        @csrf

                        {{-- Nom --}}
                        <div class="mb-4">
                            <label for="nom" class="form-label fw-semibold">
                                <i class="bi bi-person-fill"></i>
                                Nom et Prénom
                            </label>

                            <input type="text" 
                                   class="form-control form-control-lg rounded-3 @error('nom') is-invalid @enderror" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom') }}"
                                   placeholder="Entrez votre nom et prénom">

                            @error('nom')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                <i class="bi bi-envelope-fill"></i>
                                Adresse Email
                            </label>

                            <input type="email" 
                                   class="form-control form-control-lg rounded-3 @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   placeholder="Entrez votre email">

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Objet --}}
                        <div class="mb-4">
                            <label for="objet" class="form-label fw-semibold">
                                <i class="bi bi-chat-left-text-fill"></i>
                                Objet
                            </label>

                            <input type="text" 
                                   class="form-control form-control-lg rounded-3 @error('objet') is-invalid @enderror" 
                                   id="objet" 
                                   name="objet" 
                                   value="{{ old('objet') }}"
                                   placeholder="Objet du message">

                            @error('objet')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Message --}}
                        <div class="mb-4">
                            <label for="message" class="form-label fw-semibold">
                                <i class="bi bi-pencil-square"></i>
                                Message
                            </label>

                            <textarea 
                                class="form-control rounded-3 @error('message') is-invalid @enderror" 
                                id="message" 
                                name="message" 
                                rows="5"
                                placeholder="Écrivez votre message ici...">{{ old('message') }}</textarea>

                            @error('message')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Boutons --}}
                        <div class="d-flex justify-content-center gap-3 mt-4">

                            <button type="reset" 
                                    class="btn btn-outline-danger px-4 py-2 rounded-3 shadow-sm">
                                <i class="bi bi-x-circle"></i>
                                Annuler
                            </button>

                            <button type="submit" 
                                    class="btn btn-primary px-4 py-2 rounded-3 shadow-sm">
                                <i class="bi bi-send-fill"></i>
                                Envoyer le message
                            </button>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection