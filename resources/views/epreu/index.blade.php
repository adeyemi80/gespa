@extends('classes.layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">

            @if ($message = Session::get('success'))
                <div class="alert alert-success">{{ $message }}</div>
            @endif

            <div class="card shadow-lg">
                <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
                    <h4 class="mb-0">Liste des Épreuves</h4>
                    <a href="{{ route('epreuves.create') }}" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle"></i> Ajouter une épreuve
                    </a>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>#</th>
                                <th>Trimestre</th>
                                <th>Matière</th>
                                <th>Nature</th>
                                <th>Fichier</th>
                                <th>Classe</th>
                                <th>Année</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @forelse ($epreuves as $epreuve)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $epreuve->trimestre }}</td>
                                    <td>{{ $epreuve->matiere }}</td>
                                    <td>{{ $epreuve->nature }}</td>
                                    <td>
                                        {{ $epreuve->file }} 
                                        <a href="{{ asset('epreuves/' . $epreuve->file) }}" class="btn btn-link btn-sm" target="_blank">
                                            Télécharger
                                        </a>
                                    </td>
                                    <td>{{ $epreuve->classe->nom ?? 'N/A' }}</td>
                                    <td>{{ $epreuve->classe->annee->ann ?? 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('epreuves.show', $epreuve->id) }}" class="btn btn-warning btn-sm">
                                                <i class="bi bi-eye"></i> Voir
                                            </a>
                                            <form action="{{ route('epreuves.destroy', $epreuve->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette épreuve ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i> Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-danger">
                                        <strong>Aucune épreuve trouvée !</strong>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center">
                        {{ $epreuves->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
