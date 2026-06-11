@extends('tableau.neutre')

@section('title', 'Importation des photos')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- ✅ SUCCÈS --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- ⚠️ DOUBLONS --}}
            @if(session('warning_doublon') && session('doublons'))
                <div class="alert alert-warning">

                    <h5 class="mb-3">⚠️ Certains élèves ont déjà une photo :</h5>

                    <ul class="list-group mb-3">

                        @foreach(session('doublons') as $eleve)
                            <li class="list-group-item d-flex justify-content-between align-items-center">

                                <span>
                                    {{ $eleve->nom }} {{ $eleve->prenom }}
                                </span>

                                <span class="badge bg-secondary">
                                    {{ $eleve->matricule }}
                                </span>

                            </li>
                        @endforeach

                    </ul>

                    <p class="mb-3">
                        Voulez-vous remplacer ces photos ?
                    </p>

                    {{-- ✔ FORM FORCE (CORRIGÉ) --}}
                    <form action="{{ route('eleves.import.photos') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- 🔥 SIGNAL DE REMPLACEMENT --}}
                        <input type="hidden" name="replace" value="1">

                        <button type="submit" class="btn btn-danger">
                            ✔ Oui, remplacer les photos
                        </button>

                        <a href="{{ route('eleves.photos') }}" class="btn btn-secondary">
                            ❌ Annuler
                        </a>

                    </form>

                </div>
            @endif

            {{-- ❌ ERREURS --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 🧾 CARD --}}
            <div class="card shadow-lg border-0">

                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">📸 Importation des photos des élèves</h4>
                </div>

                <div class="card-body">

                    {{-- FORM --}}
                    <form action="{{ route('eleves.import.photos') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- INPUT FILE --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                Choisir les photos (plusieurs fichiers possibles)
                            </label>

                            <input type="file"
                                   name="photos[]"
                                   id="photos"
                                   multiple
                                   class="form-control @error('photos') is-invalid @enderror">

                            @error('photos')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- 👀 PREVIEW --}}
                        <div id="preview" class="d-flex flex-wrap gap-2 mb-3"></div>

                        {{-- BUTTON --}}
                        <div class="text-end">
                            <button type="submit" class="btn btn-success px-4">
                                📤 Importer les photos
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- JS PREVIEW SÉCURISÉ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('photos');
    const preview = document.getElementById('preview');

    if (!input || !preview) return;

    input.addEventListener('change', function (event) {

        preview.innerHTML = '';

        const files = event.target.files;

        if (!files || files.length === 0) return;

        Array.from(files).forEach(file => {

            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();

            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '413px';
                img.style.height = '531px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '8px';
                img.style.border = '1px solid #ddd';

                preview.appendChild(img);
            };

            reader.readAsDataURL(file);
        });

    });

});
</script>

@endsection