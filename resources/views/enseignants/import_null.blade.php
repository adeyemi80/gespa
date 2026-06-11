@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">📥 Importation des Enseignants</h5>
        </div>

        <div class="card-body">
            {{-- Message de succès ou d'erreur --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Formulaire d'import --}}
            <form action="{{ route('enseignants.import') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf

                <div class="col-md-12">
                    <label for="fichier" class="form-label fw-bold">📎 Fichier Excel</label>
                    <input type="file" name="fichier" id="fichier" class="form-control" accept=".xlsx,.csv" required>
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success">
                        📤 Importer
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Instructions pour l'importation --}}
    <div class="alert alert-info mt-4 shadow-sm">
        <h5 class="text-primary mb-3">
            <i class="fa fa-info-circle me-2"></i>Instructions pour le fichier d'importation
        </h5>
        <ul>
            <li>Le fichier doit être au format <strong>Excel (.xlsx)</strong> ou <strong>CSV</strong>.</li>
            <li>La première ligne doit contenir les en-têtes des colonnes.</li>
            <li>Colonnes attendues (dans cet ordre) :</li>
        </ul>
        <div class="bg-light border rounded p-3 mb-3">
            <code><strong>nom | prenom | email | telephone | specialite | sexe | date_naissance | adresse</strong></code>
        </div>
        <ul>
            <li>Champs obligatoires : <strong>nom, prenom, email, telephone, sexe</strong>.</li>
            <li>Le champ <strong>sexe</strong> doit contenir <code>M</code> ou <code>F</code>.</li>
        </ul>
    </div>
</div>
@endsection
