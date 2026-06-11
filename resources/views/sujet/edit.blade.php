@extends('classes.layout')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="row justify-content-center mt-3">
    <div class="col-md-8">

        @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                {{ $message }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Modifier L'épreuve
                </div>
                <div class="float-end">
                    <a href="{{ route('files.index') }}" class="btn btn-primary btn-sm">&larr; Retour</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('files.update', $sujet->id) }}" method="post">
                    @csrf
                    @method("PUT")

                                <div class="mb-3 row">
                                    <label for="code" class="col-md-4 col-form-label text-md-end text-start">Classe</label>
                                    <div class="col-md-6">
                                    <select id="classe" name="classe_id" class="form-control">
                                        <option value="">Selectionnez la Classe</option>
                                        @foreach($classes as $classe)
                                            <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>{{ $classe->nom }}</option>
                                        @endforeach
                                        @error('annee')
                                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                    </select>
                                    </div>
                                  </div>
                            
                                <div class="mb-3 row">
                                    <label for="code" class="col-md-4 col-form-label text-md-end text-start">Le Trimestre</label>
                                    <div class="col-md-6">
                                      <input type="text" class="form-control @error('trimestre') is-invalid @enderror" id="trimestre" name="trimestre" value="{{ $epreuve->trimestre }}">
                                        @if ($errors->has('trimestre'))
                                            <span class="text-danger">{{ $errors->first('trimestre') }}</span>
                                        @endif
                                    </div>
                                </div>
                            
                                <div class="mb-3 row">
                                    <label for="code" class="col-md-4 col-form-label text-md-end text-start">la Matière</label>
                                    <div class="col-md-6">
                                      <input type="text" class="form-control @error('matiere') is-invalid @enderror" id="matiere" name="matiere" value="{{ $epreuve->matiere }}">
                                        @if ($errors->has('matiere'))
                                            <span class="text-danger">{{ $errors->first('matiere') }}</span>
                                        @endif
                                    </div>
                                </div>
                            
                                <div class="mb-3 row">
                                    <label for="code" class="col-md-4 col-form-label text-md-end text-start">La Nature de l'épreuve</label>
                                    <div class="col-md-6">
                                      <input type="text" class="form-control @error('nature') is-invalid @enderror" id="nature" name="nature" value="{{ $epreuve->nature }}">
                                        @if ($errors->has('nature'))
                                            <span class="text-danger">{{ $errors->first('nature') }}</span>
                                        @endif
                                    </div>
                                </div>
                            
                                <div class="mb-3 row">
                                    <label for="code" class="col-md-4 col-form-label text-md-end text-start">Le fichier</label>
                                    <div class="col-md-6">
                                      <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" value="{{ $epreuve->file }}">
                                        @if ($errors->has('file'))
                                            <span class="text-danger">{{ $errors->first('file') }}</span>
                                        @endif
                                    </div>
                                </div> 
                            
                    
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Modifier">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>    
</div>
    
@endsection