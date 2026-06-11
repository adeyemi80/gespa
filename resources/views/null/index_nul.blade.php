@extends('classes.layout')

@section('content')
<div class="container py-5" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-primary fw-bold mb-0">
            📋 Liste des Bulletins
        </h4>
        <a href="{{ route('bulletins.create') }}" class="btn btn-success shadow-sm">
            ➕ Nouveau Bulletin
        </a>
    </div>

    {{-- ✅ Message de succès --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    {{-- 📄 Tableau des bulletins --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-success text-center">
                        <tr>
                            <th>#</th>
                            <th>👨‍🎓 Élève</th>
                            <th>📅 Année</th>
                            <th>📘 Trimestre</th>
                            <th>📊 Moyenne</th>
                            <th>💬 Appréciation</th>
                            <th>⚙️ Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bulletins as $bulletin)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $bulletin->eleve->nom }} {{ $bulletin->eleve->prenom }}</td>
                                <td>{{ $bulletin->annee->nom }}</td>
                                <td>{{ $bulletin->trimestre->nom }}</td>
                                <td class="fw-bold text-primary">{{ number_format($bulletin->moyenne, 2) }}</td>
                                <td>{{ $bulletin->appreciation }}</td>
                                <td>
                                   <a href="{{ route('bulletins.index') }}" class="btn btn-primary">📋 Tous les bulletins</a>
                                    <a href="{{ route('bulletins.classe', ['classe_id' => $classe->id, 'annee_id' => $annee->id]) }}" 
   class="btn btn-primary btn-sm">
   🔍 Voir les bulletins
</a>
                                    <a href="{{ route('bulletins.show', $bulletin->id) }}" class="btn btn-sm btn-outline-info me-1">
                                        🔍 Voir
                                    </a>
                                    <a href="{{ route('bulletins.edit', $bulletin->id) }}" class="btn btn-sm btn-outline-warning">
                                        ✏️ Modifier
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    🚫 Aucun bulletin trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Fermeture automatique de l’alerte --}}
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





