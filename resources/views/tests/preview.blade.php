@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    <h3 class="mb-4 text-primary fw-bold">
        📄 Prévisualisation des tests
    </h3>

    {{-- Messages de succès --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle text-success me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Fichiers ignorés --}}
    @if(session('skipped_errors') && is_array(session('skipped_errors')) && count(session('skipped_errors')) > 0)
        <div class="alert alert-warning alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
            <strong>⚠️ Fichiers ignorés :</strong>
            <ul class="mb-0">
                @foreach(session('skipped_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Prévisualisation sécurisée --}}
    @if(!isset($previews) || !is_array($previews) || empty($previews))
        <div class="alert alert-warning">
            Aucun fichier à prévisualiser.
        </div>
    @else
        <form action="{{ route('tests.importFinal') }}" method="POST" id="importForm">
            @csrf

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Fichier</th>
                            <th>Type</th>
                            <th>Classe(s)</th>
                            <th>Matière</th>
                            <th>Sélection</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($previews as $index => $preview)
                        <tr id="row-{{ $index }}">
                            <td>
                                <strong>{{ $preview['filename'] ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $preview['temp_path'] ?? '' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ isset($preview['type']) && $preview['type'] != 'Non détecté' ? 'info' : 'secondary' }}">
                                    {{ $preview['type'] ?? 'Non détecté' }}
                                </span>
                            </td>
                            <td>
                                {{-- Affiche plusieurs classes séparées par "+" --}}
                                {{ isset($preview['classes']) && is_array($preview['classes']) ? implode(' + ', $preview['classes']) : 'Non détectée' }}
                            </td>
                            <td>{{ $preview['matiere'] ?? 'Non détectée' }}</td>
                            <td class="text-center">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" name="kept_indexes[]" value="{{ $index }}" id="keep-{{ $index }}" checked>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-1 w-100" onclick="removeRow({{ $index }})">
                                    ❌ Exclure
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('tests.importForm') }}" class="btn btn-secondary">
                    ← Annuler / Nouvel import
                </a>

                <button type="submit" class="btn btn-success btn-lg" id="importBtn">
                    <span class="spinner-border spinner-border-sm d-none me-2" id="spinner"></span>
                    Importer {{ count($previews) }} fichier(s)
                </button>
            </div>
        </form>
    @endif
</div>

<script>
function removeRow(index) {
    const row = document.getElementById('row-' + index);
    const checkbox = document.getElementById('keep-' + index);

    if(row) row.style.opacity = '0.5';
    if(checkbox) {
        checkbox.checked = false;
        checkbox.disabled = true;
    }
    updateCounter();
}

function updateCounter() {
    const remaining = document.querySelectorAll('input[name="kept_indexes[]"]:checked').length;
    const btn = document.getElementById('importBtn');
    btn.innerHTML = `<span class="spinner-border spinner-border-sm d-none me-2" id="spinner"></span>Importer ${remaining} fichier(s)`;
    btn.disabled = remaining === 0;
}

document.addEventListener('change', function(e) {
    if(e.target.matches('input[name="kept_indexes[]"]')) updateCounter();
});

// Protection double soumission
document.getElementById('importForm')?.addEventListener('submit', function(e){
    const remaining = document.querySelectorAll('input[name="kept_indexes[]"]:checked').length;
    if(remaining === 0){
        e.preventDefault();
        alert('⚠️ Sélectionnez au moins un fichier à importer.');
        return;
    }
    const btn = document.getElementById('importBtn');
    const spinner = document.getElementById('spinner');
    btn.disabled = true;
    spinner.classList.remove('d-none');
});
</script>
@endsection
