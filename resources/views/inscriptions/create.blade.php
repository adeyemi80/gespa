@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<center>
    <div class="col-lg-12 margin-tb text-bg-dark">
        <div class="pull-left mb-2">
            @csrf
            @if ($message = Session::get('success'))
                <div class="alert alert-success" role="alert">
                    {{ $message }}
                </div>
            @endif
            <div class="card text-bg-dark">
                <div class="card-header ">
                    <h2>Ajouter une Inscription</h2>
                    <div class="float-end">
                        <a href="{{ route('inscriptions.index') }}" class="btn btn-primary btn-sm">&larr; Retour en arrière</a>
                    </div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success mb-1 mt-1">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('inscriptions.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <table cellspacing="40%" border="1" class="text-bg-dark">
                        
                                <p>
                                    <div class="mb-3 row">
                                        <label class="col-md-4 col-form-label text-md-end text-start">Classe</label>
                                        <div class="col-md-6">
                                            <select id="classe_id" name="classe_id" class="btn btn-primary" required>
                                                <option value="">Sélectionnez la classe</option>
                                                @foreach($classes as $classe)
                                                    <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>{{ $classe->nom }}</option>
                                                @endforeach
                                            </select>
                                            @error('classe_id')
                                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </p>
                           
                                <div class="mb-3 row">
                                    <label class="col-md-4 col-form-label text-md-end text-start">Élève</label>
                                    <div class="col-md-6">
                                        <select name="eleve_id" class="btn btn-primary" required>
                                            <option value="">Sélectionnez l'élève</option>
                                            @foreach($eleves as $eleve)
                                                <option value="{{ $eleve->id }}" {{ old('eleve_id') == $eleve->id ? 'selected' : '' }}>
                                                    {{ $eleve->nom }} {{ $eleve->prenom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('eleve_id')
                                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                           
                                <div class="mb-3 row">
                                    <label class="col-md-4 col-form-label text-md-end text-start">Année scolaire</label>
                                    <div class="col-md-6">
                                        <select name="annee_id" class="btn btn-primary" required>
                                            <option value="">Sélectionnez l'année</option>
                                            @foreach($annees as $annee)
                                                <option value="{{ $annee->id }}" {{ old('annee_id') == $annee->id ? 'selected' : '' }}>
                                                    {{ $annee->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('annee_id')
                                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                           
                                <div class="mb-3 row">
                                    <label class="col-md-4 col-form-label text-md-end text-start">Date d'inscription</label>
                                    <div class="col-md-6">
                                        <input type="date" class="form-control @error('date_inscription') is-invalid @enderror"
                                               id="date_inscription" name="date_inscription" value="{{ old('date_inscription') ?? now()->toDateString() }}" required>
                                        @error('date_inscription')
                                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                           
                                <button type="submit" class="btn btn-primary btn-sm">Ajouter l'inscription</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</center>
@endsection
