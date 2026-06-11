@extends('tableau.neutre')

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
                   Les Informations sur le contact
                </div>
                <div class="float-end">
                    <a href="{{ route('contacts.index') }}" class="btn btn-primary btn-sm">&larr; Retour en Arrière</a>
                </div>
            </div>
            <div class="card-body">

                    <div class="row">
                        <label for="code" class="col-md-4 col-form-label text-md-end text-start"><strong>Nom:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                           
                            {{ $contact->nom}}
                          
                        </div>
                    </div>

                    <div class="row">
                        <label for="code" class="col-md-4 col-form-label text-md-end text-start"><strong>Email:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                           
                            {{ $contact->email}}
                          
                        </div>
                    </div>

                    <div class="row">
                        <label for="code" class="col-md-4 col-form-label text-md-end text-start"><strong>Objet:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                           
                            {{ $contact->objet}}
                          
                        </div>
                    </div>

                    <div class="row">
                        <label for="code" class="col-md-4 col-form-label text-md-end text-start"><strong>Message:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                           
                            {{ $contact->message}}
                          
                        </div>
                    </div>

            </div>
        </div>
    </div>    
</div>
    
@endsection