@extends('classes.layout')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="row justify-content-center mt-3">
    <div class="col-md-12">

        @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                {{ $message }}
            </div>
        @endif

        <div class="card">
            <center> <div class="card-header"> <h3> LISTE DES INSCRIS </h3></div></center>
            <div class="card-body">
                <a href="{{ route('files.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Ajouter de Nouvelle épreuve</a>
                <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">N°</th>
                        <th scope="col">Trimestre</th>
                        <th scope="col">MATIÈRE</th>
                        <!--<th scope="col">Date de Naissance</th>
                        <th scope="col">Lieu de Naissance</th>
                        <th scope="col">Nationalité</th>
                        <th scope="col">Téléphone</th>-->
                        <th scope="col">Nature </th>
                        <th scope="col">Fichier</th>
                        <th scope="col">Classe</th>
                        <!--<th scope="col">ID Année</th>
                        <th scope="col">ID Classe</th>
                        <th scope="col">Actions</th>-->
                      </tr>
                    </thead>
                    <tbody>
                        @forelse ($sujets as $sujet)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $sujet->trimestre }}</td>
                            <td>{{ $sujet->matiere }}</td>
                            <td>{{ $sujet->nature }}</td>
                            <td>{{ $sujet->file }} <a class="" href= "sujets/{{$sujet->file}}" > Télécharger le fichier </a></td>
                            <td>{{ $sujet->classe->nom }}</td>
                           
                            <!--<td>
                                <form action="{{ route('files.destroy', $sujet->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    <a href="{{ route('files.show', $sujet->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-eye"></i> VOIR</a>

                                    <a href="{{ route('files.edit', $sujet->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> MODIFIER</a>   

                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-Vous Supprimer la Classe?');"><i class="bi bi-trash"></i> SUPPRIMER</button>
                                </form>
                            </td>-->
                        </tr>
                        @empty
                            <td colspan="6">
                                <span class="text-danger">
                                    <strong>PAS D'INSCRIS!</strong>
                                </span>
                            </td>
                        @endforelse
                    </tbody>
                  </table>
                  
                  {{ $sujets->links() }}

            </div>
        </div>
    </div>    
</div>
    
@endsection