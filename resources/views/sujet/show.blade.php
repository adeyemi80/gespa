@extends('classes.layout')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="row justify-content-center mt-3">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                   Les Informations sur  {{ $file->file }}
                </div>
                <div class="float-end">
                    <a href="{{ route('files.index') }}" class="btn btn-primary btn-sm">&larr; Retour en Arrière</a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <label for="code" class="col-md-4 col-form-label text-md-end text-start"><strong>Trimestre:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                       
                        {{ $epreuve->trimestre }}
                      
                    </div>
                </div>

                <div class="row">
                    <label for="code" class="col-md-4 col-form-label text-md-end text-start"><strong>Matière:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                       
                        {{ $epreuve->matiere }}
                      
                    </div>
                </div>

                    <div class="row">
                        <label for="code" class="col-md-4 col-form-label text-md-end text-start"><strong>Nature:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                           
                            {{ $epreuve->nature }}
                          
                        </div>
                    </div>

                    <div class="row">
                        <label for="code" class="col-md-4 col-form-label text-md-end text-start"><strong>Fichier:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                           
                            {{ $epreuve->file }}
                          
                        </div>
                    </div>

                    <div class="row">
                        <label for="code" class="col-md-4 col-form-label text-md-end text-start"><strong>Classe:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                           
                            {{ $epreuve->classe_id }}
                          
                        </div>
                    </div>


            </div>
        </div>
    </div>    
</div>
    
@endsection