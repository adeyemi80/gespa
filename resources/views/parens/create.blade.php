@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="row justify-content-center mt-3">
    <div class="col-md-8">
        @csrf
        @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                {{ $message }}
            </div>
        @endif
        <div class="card text-bg-dark">
            <div class="card-header">
                <div class="float-start">
                   Ajouter un élève
                </div>
                <div class="float-end">
                    <a href="{{ route('shows.create') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('parens.store') }}" method="post">
                    @csrf
                    @if (session('status'))
                    <div class="alert alert-success mb-1 mt-1" id="success-alert" >
                        {{ session('status') }}
                    </div>
                @endif

                  <p> <div class="mb-3 row">
                        <label for="code" class="col-md-4 col-form-label text-md-end text-start">Nom</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom') }}" required>
                          @error('nom')
                          <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                      @enderror
                        </div>
                    </div></p>

                  <p> <div class="mb-3 row">
                        <label for="code" class="col-md-4 col-form-label text-md-end text-start">Prenom</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('prenom') is-invalid @enderror" id="prenom" name="prenom" value="{{ old('prenom') }}" required>
                          @error('prenom')
                          <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                      @enderror
                        </div>
                    </div></p>

                  <p> <div class="mb-3 row">
                        <label for="code" class="col-md-4 col-form-label text-md-end text-start">Numéro de Téléphone</label>
                        <div class="col-md-6">
                          <input type="string" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone') }}" required>
                          @error('telephone')
                          <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                      @enderror
                        </div>
                    </div></p>

                  <p> <div class="mb-3 row">
                        <label for="code" class="col-md-4 col-form-label text-md-end text-start">Email</label>
                        <div class="col-md-6">
                          <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                          @error('email')
                          <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                      @enderror
                        </div>
                    </div></p>
                    <p> <div class="mb-3 row">
                        <label for="code" class="col-md-4 col-form-label text-md-end text-start">MOT DE PASSE</label>
                        <div class="col-md-6">
                          <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}" required>
                          @error('password')
                          <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                      @enderror
                        </div>
                    </div></p>

                  <p> <div class="mb-3 row">
                        <label for="code" class="col-md-4 col-form-label text-md-end text-start">Adresse</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('adresse') is-invalid @enderror" id="adresse" name="adresse" value="{{ old('adresse') }}" required>
                          @error('adresse')
                          <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                      @enderror
                        </div>
                    </div></p>
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Ajouter de Classe">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>    
</div>
    
@endsection


