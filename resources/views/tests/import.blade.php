@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-light p-3 rounded shadow-sm">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">
                <i class="fas fa-home me-1"></i>Accueil
            </a></li>
            <li class="breadcrumb-item active" aria-current="page">📊 Importation des tests</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-primary fw-bold mb-0">
                    <i class="fas fa-upload me-2"></i>📊 Importation des épreuves
                </h2>
                <span class="badge bg-success fs-6 px-3 py-2">
                    <i class="fas fa-file-upload me-1"></i>Multi-fichiers
                </span>
            </div>
        </div>
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow border-0 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle text-success me-3 fs-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">✅ Succès !</h5>
                    {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow border-0 mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Erreur de validation :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li><small>• {{ $error }}</small></li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Fichiers ignorés --}}
    @if(session('skipped_errors') && is_array(session('skipped_errors')) && count(session('skipped_errors')) > 0)
        <div class="alert alert-warning alert-dismissible fade show shadow border-0 mb-4" role="alert">
            <div class="d-flex align-items-start">
                <i class="fas fa-exclamation-triangle text-warning me-3 mt-1 fs-2"></i>
                <div>
                    <h5 class="alert-heading mb-2">⚠️ {{ count(session('skipped_errors')) }} fichier(s) ignoré(s)</h5>
                    <ul class="mb-0">
                        @foreach(array_slice(session('skipped_errors'), 0, 5) as $error)
                            <li class="mb-1"><small class="text-wrap">{{ $error }}</small></li>
                        @endforeach
                        @if(count(session('skipped_errors')) > 5)
                            <li class="mt-2"><small>… et {{ count(session('skipped_errors')) - 5 }} de plus</small></li>
                        @endif
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Formulaire principal --}}
    <div class="card shadow-lg border-0">
        <div class="card-header bg-gradient-primary text-black py-4">
            <h4 class="card-title mb-0 fw-bold">
                <i class="fas fa-cog me-2"></i>Configuration de l'import
            </h4>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('tests.preview') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label for="annee_id" class="form-label fw-semibold">
                            <i class="fas fa-calendar-alt text-primary me-2"></i>Année scolaire *
                        </label>
                        <select name="annee_id" id="annee_id" class="form-select form-select-lg @error('annee_id') is-invalid @enderror" required>
                            <option value="">📋 Choisir une année</option>
                            @foreach($annees as $annee)
                                <option value="{{ $annee->id }}" 
                                    {{ old('annee_id', $anneeActive->id ?? '') == $annee->id ? 'selected' : '' }}>
                                    {{ $annee->nom }}
                                    @if($annee->en_cours)
                                        <span class="badge bg-success ms-2">Actuelle</span>
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('annee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="trimestre_id" class="form-label fw-semibold">
                            <i class="fas fa-layer-group text-primary me-2"></i>Trimestre *
                        </label>
                        <select name="trimestre_id" id="trimestre_id" class="form-select form-select-lg @error('trimestre_id') is-invalid @enderror" required>
                            <option value="">📚 Choisir un trimestre</option>
                        </select>
                        @error('trimestre_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="date_test" class="form-label fw-semibold">
                            <i class="fas fa-calendar-day text-primary me-2"></i>Date du test *
                        </label>
                        <input type="date" name="date_test" id="date_test" 
                               class="form-control form-control-lg @error('date_test') is-invalid @enderror" required
                               value="{{ old('date_test', date('Y-m-d')) }}">
                        @error('date_test')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-8">
                        <label for="titre" class="form-label fw-semibold">
                            <i class="fas fa-heading text-primary me-2"></i>Titre du test *
                        </label>
                        <input type="text" name="titre" id="titre" 
                               class="form-control form-control-lg @error('titre') is-invalid @enderror" 
                               placeholder="Ex: Premier Devoir du premier Trimestre" required
                               value="{{ old('titre') }}">
                        @error('titre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="tests_files" class="form-label fw-semibold">
                        <i class="fas fa-cloud-upload-alt text-primary me-2"></i>Fichiers à importer *
                    </label>
                    <input type="file" name="tests_files[]" id="tests_files" 
                           class="form-control form-control-lg @error('tests_files') is-invalid @enderror" 
                           multiple accept=".pdf,.doc,.docx,.odt" required>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Formats : PDF, DOC, DOCX, ODT (Max 50MB/fichier)
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-files-medical me-1"></i>
                                Sélection multiple autorisée (jusqu'à 20 fichiers)
                            </small>
                        </div>
                    </div>
                    @error('tests_files')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-md-end gap-3 pt-3 border-top">
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow-lg" id="previewBtn">
                        <i class="fas fa-eye me-2"></i>
                        <span class="spinner-border spinner-border-sm d-none me-2" id="previewSpinner"></span>
                        Prévisualiser
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Aperçu fichiers sélectionnés --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-list me-2"></i>Fichiers sélectionnés
                    </h6>
                </div>
                <div class="card-body" id="filePreview">
                    <small class="text-muted text-center py-4">Aucun fichier sélectionné</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- REMPLACEZ UNIQUEMENT le script à la fin --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données trimestres (protégé contre conflits)
    window.gespaAnnees = @json($annees);
    window.gespaAnneeActiveId = '{{ $anneeActive->id ?? "" }}';
    
    // Précharger trimestre actif
    if (window.gespaAnneeActiveId) {
        loadTrimestresGespa(window.gespaAnneeActiveId);
    }

    // Écouteur année → trimestres (namespace unique)
    const anneeSelect = document.getElementById('annee_id');
    if (anneeSelect) {
        anneeSelect.addEventListener('change', function() {
            loadTrimestresGespa(this.value);
        });
    }

    // Prévisualisation fichiers (protégée)
    const fileInput = document.getElementById('tests_files');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            updateFilePreviewGespa(e.target.files);
        });
    }

    // Spinner soumission
    const form = document.getElementById('importForm');
    if (form) {
        form.addEventListener('submit', function() {
            const btn = document.getElementById('previewBtn');
            const spinner = document.getElementById('previewSpinner');
            if (btn && spinner) {
                btn.disabled = true;
                spinner.classList.remove('d-none');
            }
        });
    }
});

// ✅ FONCTIONS avec NAMESPACE UNIQUE (anti-conflit)
function loadTrimestresGespa(anneeId) {
    const trimestreSelect = document.getElementById('trimestre_id');
    if (!trimestreSelect) return;
    
    // Reset
    trimestreSelect.innerHTML = '<option value="">📚 Choisir un trimestre</option>';
    
    if (!anneeId || !window.gespaAnnees) return;
    
    const annee = window.gespaAnnees.find(a => a.id == anneeId);
    if (!annee || !annee.trimestres) return;
    
    // Ajout options
    annee.trimestres.forEach(tri => {
        const opt = document.createElement('option');
        opt.value = tri.id;
        opt.textContent = tri.nom;
        trimestreSelect.appendChild(opt);
    });
}

function updateFilePreviewGespa(files) {
    const preview = document.getElementById('filePreview');
    if (!preview) return;
    
    if (!files || !files.length) {
        preview.innerHTML = '<small class="text-muted text-center py-4">Aucun fichier sélectionné</small>';
        return;
    }

    let html = `<div class="d-flex flex-wrap gap-2 pt-2">`;
    Array.from(files).slice(0, 10).forEach((file, i) => {
        html += `
            <span class="badge bg-primary fs-6 px-3 py-2" title="${file.name}">
                📄 ${file.name.length > 15 ? file.name.substring(0,12)+'...' : file.name}
                <br><small>${formatFileSizeGespa(file.size)}</small>
            </span>
        `;
    });
    
    if (files.length > 10) {
        html += `<span class="badge bg-secondary fs-6 px-3 py-2">… +${files.length - 10} fichiers</span>`;
    }
    
    html += `</div>`;
    preview.innerHTML = html;
}

function formatFileSizeGespa(bytes) {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
}
</script>

@endsection
