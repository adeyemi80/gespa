@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="mb-3 text-primary">Sélection des participants</h2>
{{-- ✅ Message de succès --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

            <form action="{{ route('td.enregistrer-participants') }}" method="POST">
                @csrf

                <input type="hidden" name="classe_id"
                       value="{{ $inscriptions->first()->classe_id ?? '' }}">

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Élève</th>
                            <th>Participe au TD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inscriptions as $inscription)
                            <tr>
                                <td>
                                    {{ $inscription->eleve->nom }}
                                    {{ $inscription->eleve->prenom }}
                                </td>
                                <td class="text-center">
                                    <input type="checkbox"
                                           name="participants[]"
                                           value="{{ $inscription->id }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="btn btn-success mt-3">
                    Valider les participants
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
