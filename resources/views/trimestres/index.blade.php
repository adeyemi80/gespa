@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5" style="background-color: #f8f9fa; min-height: 100vh;">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-success mb-0">📆 Liste des Trimestres</h4>
        <a class="btn btn-success" href="{{ route('trimestres.create') }}">
            <i class="bi bi-plus-circle"></i> Ajouter un trimestre
        </a>
    </div>

    {{-- Message de succès --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            ✅ {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    {{-- Carte et tableau --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0 bg-white text-center">
                    <thead class="table-success">
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Période</th>
                            <th>Ordre</th>
                            <th>Année(s)</th>
                            <th width="180px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($trimestres as $key => $trimestre)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $trimestre->nom }}</td>
                                <td>{{ $trimestre->periode ?? '-' }}</td>
                                <td>{{ $trimestre->ordre }}</td>
                                <td>
                                    @if($trimestre->annees->isNotEmpty())
                                        {{ $trimestre->annees->pluck('nom')->join(', ') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('trimestres.show', $trimestre->id) }}" class="btn btn-outline-info btn-sm" title="Voir">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('trimestres.edit', $trimestre->id) }}" class="btn btn-outline-primary btn-sm" title="Modifier">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('trimestres.destroy', $trimestre->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Supprimer">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">Aucun trimestre enregistré.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination si applicable --}}
            @if(method_exists($trimestres, 'links'))
                <div class="p-3">
                    {{ $trimestres->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Fermeture automatique de l'alerte --}}
<script>
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 4000);
</script>
@endsection
