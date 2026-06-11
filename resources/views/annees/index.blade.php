@extends('tableau.neutre') {{-- ou layouts.app selon ton layout principal --}}

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Message flash de succès --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show text-center" role="alert" style="z-index: 1050;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            {{-- En-tête --}}
            <h2 class="mb-4 text-primary">📅 Liste des Années Scolaires</h2>

            {{-- Bouton d'ajout --}}
            <a href="{{ route('annees.create') }}" class="btn btn-success mb-3">
                ➕ Ajouter une Année
            </a>

            {{-- Tableau des années --}}
            <div class="table-responsive">
                <table class="table table-bordered align-middle bg-white">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width: 5%;">N°</th>
                            <th>Année</th>
                            <th>Date de Début</th>
                            <th>Date de Fin</th>
                            <th>En cours</th>
                            <th style="width: 22%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($annees as $annee)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $annee->nom }}</td>
                            <td>{{ $annee->debut }}</td>
                            <td>{{ $annee->fin }}</td>
                            <td class="text-center">
                                @if($annee->en_cours)
                                    <span class="badge bg-success">Oui</span>
                                @else
                                    <span class="badge bg-secondary">Non</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('annees.show', $annee->id) }}" class="btn btn-info btn-sm me-1" title="Voir">
                                    👁️
                                </a>
                                <a href="{{ route('annees.edit', $annee->id) }}" class="btn btn-warning btn-sm me-1" title="Modifier">
                                    ✏️
                                </a>
                                <form action="{{ route('annees.destroy', $annee->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Voulez-vous supprimer cette année ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                        🗑️
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-danger fw-bold">Aucune année enregistrée.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                {{-- Affichage simple sans pagination --}}
            </div>
        </div>
    </div>
</div>

{{-- Script pour faire disparaître l'alerte --}}
<script>
    setTimeout(function () {
        const alert = document.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 4000);
</script>
@endsection
