@extends('classes.layout')

@section('content')
<div class="container py-5" style="background-color: #f8f9fa; min-height: 100vh;">

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">📥 Importation des Notes</h5>
        </div>
        <div class="card-body">

            {{-- Messages Flash --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Alerte Attention --}}
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:">
                    <use xlink:href="#exclamation-triangle-fill"/>
                </svg>
                <div>
                    <strong>Attention&nbsp;!</strong> Avant de télécharger le modèle Excel, sélectionnez d'abord la <em>classe</em>.
                </div>
            </div>

            {{-- Icône SVG définie (Bootstrap) --}}
            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.964 0L.165 13.233c-.457.778.091
                        1.767.982 1.767h13.706c.89 0 1.438-.99.982-1.767L8.982 1.566zM8 5c.535 0
                        .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1
                        5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                </symbol>
            </svg>

            {{-- 📄 Bouton modèle vierge --}}
            <a href="{{ route('notes.import.template') }}" class="btn btn-outline-primary mb-3">
                📥 Télécharger le modèle Excel vierge
            </a>

            {{-- 📄 Formulaire pour modèle par classe --}}
            <form method="GET" action="{{ route('notes.template.par_classe') }}" class="row align-items-end mb-4 g-3">
                <div class="col-md-6">
                    <label for="classe_id" class="form-label fw-bold">📚 Classe</label>
                    <select id="classe" name="classe_id" class="form-select">
                        <option value="">-- Sélectionner une classe --</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 text-end">
                    <button type="submit" class="btn btn-outline-info w-100">
                        📄 Télécharger modèle par classe
                    </button>
                </div>
            </form>

            {{-- 📤 Formulaire principal d'importation --}}
            <form action="{{ route('notes.import.preview') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf

                <div class="col-md-6">
                    <label for="matiere_id" class="form-label fw-bold">🧪 Matière</label>
                    <select id="matiere" name="matiere_id" class="form-select" required>
                        <option value="">-- Sélectionner une matière --</option>
                        {{-- Matières dynamiques selon la classe (via JS si applicable) --}}
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="trimestre_id" class="form-label fw-bold">📆 Trimestre</label>
                    <select name="trimestre_id" id="trimestre_id" class="form-select" required>
                        @foreach($trimestres as $trimestre)
                            <option value="{{ $trimestre->id }}">{{ $trimestre->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="annee_id" class="form-label fw-bold">📅 Année scolaire</label>
                    <select name="annee_id" id="annee_id" class="form-select" required>
                        @foreach($annees as $annee)
                            <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="fichier" class="form-label fw-bold">📎 Fichier Excel (.xlsx)</label>
                    <input type="file" name="fichier" id="fichier" class="form-control" accept=".xlsx" required>
                </div>

                <div class="col-12 text-end mt-2">
                    <button type="submit" class="btn btn-success w-100">
                        📊 Prévisualiser les notes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
