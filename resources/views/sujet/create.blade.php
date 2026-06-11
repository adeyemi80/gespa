@extends('classes.layout')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
   <center>
            <div class="col-lg-12 margin-tb">
                <div class="pull-left mb-2">
                    <div class="card">
                        <div class="card-header">
                    <h2>Ajouter des Inscriptionss</h2>
                    <div class="float-end">
                        <a href="{{ route('files.index') }}" class="btn btn-primary btn-sm">&larr; Retour en arrière</a>
                    </div>
                </div>
            
        @if (session('status'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <p><div class="mb-3 row">
                <label for="code" class="col-md-4 col-form-label text-md-end text-start">Selectionnez la Classe</label>
                <div class="col-md-6">
                <select id="classe_id" name="classe_id" class="form-control" required>
                    <option value="">Selectionnez la Classe</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>{{ $classe->nom }}</option>
                    @endforeach
                    @error('classe_id')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
                </select></div>
              </div></p> 
        
            <div class="mb-3 row">
                <label for="code" class="col-md-4 col-form-label text-md-end text-start">Selectionnez le Trimestre</label>
                <div class="col-md-6">
                    <select name="trimestre"  class="form-control form-select @error('trimestre') is-invalid
                    @enderror" id="trimestre" value="{{old('trimestre')}}">
                    <option value="">Selectionner le Trimestre</option>
                        <option value="trimestre1">Trimestre1</option>
                      <option value="trimestre2">Trimestre2</option>
                      <option value="trimestre3">Trimestre3</option>
                    </select>
                  @error('trimestre')
                  <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
              @enderror
                </div>
            </div> 

            <div class="mb-3 row">
                <label for="code" class="col-md-4 col-form-label text-md-end text-start">Selectionnez la Matière</label>
                <div class="col-md-6">
                    <select name="matiere"  class="form-control form-select @error('matiere') is-invalid
                    @enderror" id="matiere" value="{{old('matiere')}}">
                    <option value="">Selectionner la Matière</option>
                        <option value="allemand">Allemand</option>
                        <option value="anglais">Anglais</option>
                        <option value="communication">Communication écrite</option>
                        <option value="conduite">Conduite</option>
                        <option value="eps">EPS</option>
                        <option value="espagnol">Espagnol</option>
                        <option value="français">Français</option>
                        <option value="histoire">Histoire et Géographie</option>
                        <option value="lecture">Lecture</option>
                        <option value="pct">PCT</option>
                        <option value="svt">SVT</option>
                        <option value="philosophie">Philosophie</option>
                      
                    </select>
                  @error('matiere')
                  <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
              @enderror
                </div>
            </div> 
        
            <div class="mb-3 row">
                <label for="code" class="col-md-4 col-form-label text-md-end text-start">Selectionnez la Nature de l'épreuve</label>
                <div class="col-md-6">
                    <select name="nature"  class="form-control form-select @error('nature') is-invalid
                    @enderror" id="nature" value="{{old('nature')}}">
                    <option value="">Selectionner la Nature de l'épreuve</option>
                        <option value="composition1">Composition1</option>
                        <option value="composition2">Composition2</option>
                        <option value="composition3">Composition3</option>
                        <option value="devoir1">Devoir1</option>
                        <option value="devoir2">Devoir2</option>
                        <option value="devoir3">Devoir3</option>
                        <option value="examen blanc 1">Examen Blanc1</option>
                        <option value="examen blanc 2">Examen Blanc2</option>
                        <option value="examen blanc 3">Examen Blanc3</option>
                        <option value="examen blanc depatemental">Examen Blanc Départemental</option>
                        <option value="examen blanc national">Examen Blanc National</option>
                    </select>
                  @error('nature')
                  <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
              @enderror
                </div>
            </div> 

            <div class="mb-3 row">
                <label for="code" class="col-md-4 col-form-label text-md-end text-start">Selectionnez le fichier</label>
                <div class="col-md-6">
                  <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" value="{{ old('file') }}">
                  @error('file')
                  <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
              @enderror
                </div>
            </div> 
        
            
        
            <button type="submit" class="btn btn-primary btn-sm">Ajouter l'épreuve</button>
       
        </form></div></div>
    </div>
</center> 
@endsection