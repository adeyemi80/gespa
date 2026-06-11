@extends('classes.layout')

@section('content')
<div class="container py-5" style="background-color: #f8f9fa; min-height: 100vh;">
    <h4 class="text-primary mb-4">🔍 Prévisualisation des Notes Importées</h4>

    {{-- Messages flash --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ❌ {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    {{-- 📄 Lignes valides --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span>✔️ Lignes valides (prêtes à enregistrer)</span>
        </div>
        <div class="card-body p-0">
            @if(count($lignes_valides) > 0)
                <form action="{{ route('notes.import.store') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0 text-center bg-white">
                            <thead class="table-success">
                                <tr>
                                    <th>Matricule</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Interrogation</th>
                                    <th>Devoir 1</th>
                                    <th>Devoir 2</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lignes_valides as $index => $note)
                                    <tr>
                                        <td>{{ $note['matricule'] }}</td>
                                        <td>{{ $note['nom'] }}</td>
                                        <td>{{ $note['prenom'] }}</td>
                                        <td>{{ $note['moyenne_interro'] }}</td>
                                        <td>{{ $note['devoir1'] }}</td>
                                        <td>{{ $note['devoir2'] }}</td>
                                    </tr>

                                    {{-- Champs cachés --}}
                                    @foreach(['matricule','nom','prenom','moyenne_interro','devoir1','devoir2'] as $champ)
                                        <input type="hidden" name="notes[{{ $index }}][{{ $champ }}]" value="{{ $note[$champ] }}">
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Enregistrer les lignes valides
                        </button>
                    </div>
                </form>
            @else
                <div class="alert alert-warning m-3">⚠️ Aucune ligne valide à enregistrer.</div>
            @endif
        </div>
    </div>

    {{-- ❌ Lignes invalides --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <span>❌ Lignes invalides (à corriger)</span>
        </div>
        <div class="card-body p-0">
            @if(count($lignes_invalides) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0 text-center bg-white">
                        <thead class="table-danger">
                            <tr>
                                <th>Matricule</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Erreur(s)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lignes_invalides as $note)
                                <tr>
                                    <td>{{ $note['matricule'] ?? 'N/A' }}</td>
                                    <td>{{ $note['nom'] ?? 'N/A' }}</td>
                                    <td>{{ $note['prenom'] ?? 'N/A' }}</td>
                                    <td class="text-start">
                                        @foreach($note['errors'] as $error)
                                            <div>• {{ $error }}</div>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3 text-muted">
                    ⚠️ Veuillez corriger les erreurs ci-dessus avant de réimporter ces lignes.
                </div>
            @else
                <div class="alert alert-info m-3">ℹ️ Aucune ligne invalide détectée.</div>
            @endif
        </div>
    </div>

    {{-- 🔙 Bouton retour --}}
    <div class="mt-3 text-start">
        <a href="{{ route('notes.import.form') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle"></i> Retour au formulaire d'importation
        </a>
    </div>
</div>

{{-- Script de fermeture alerte --}}
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
