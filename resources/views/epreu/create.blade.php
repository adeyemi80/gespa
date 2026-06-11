@extends('classes.layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Ajouter de Nouvelles Épreuves</h3>
                    <a href="{{ route('redirects') }}" class="btn btn-outline-light btn-sm">&larr; Retour</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form action="{{ route('epreuves.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="classe_id" class="form-label">Sélectionnez la Classe</label>
                            <select id="classe_id" name="classe_id" class="form-select @error('classe_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionnez --</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>{{ $classe->nom }}</option>
                                @endforeach
                            </select>
                            @error('classe_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="trimestre" class="form-label">Sélectionnez le Trimestre</label>
                            <select name="trimestre" id="trimestre" class="form-select @error('trimestre') is-invalid @enderror">
                                <option value="">-- Sélectionner --</option>
                                <option value="trimestre1">Trimestre 1</option>
                                <option value="trimestre2">Trimestre 2</option>
                                <option value="trimestre3">Trimestre 3</option>
                            </select>
                            @error('trimestre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="matiere" class="form-label">Sélectionnez la Matière</label>
                            <select name="matiere" id="matiere" class="form-select @error('matiere') is-invalid @enderror">
                                <option value="">-- Sélectionner --</option>
                                <option value="allemand">Allemand</option>
                                <option value="anglais">Anglais</option>
                                <option value="communication">Communication écrite</option>
                                <option value="conduite">Conduite</option>
                                <option value="eps">EPS</option>
                                <option value="espagnol">Espagnol</option>
                                <option value="français">Français</option>
                                <option value="histoire">Histoire et Géographie</option>
                                <option value="lecture">Lecture</option>
                                <option value="pct">PCT</option>
                                <option value="svt">SVT</option>
                                <option value="philosophie">Philosophie</option>
                            </select>
                            @error('matiere')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nature" class="form-label">Sélectionnez la Nature de l'Épreuve</label>
                            <select name="nature" id="nature" class="form-select @error('nature') is-invalid @enderror">
                                <option value="">-- Sélectionner --</option>
                                <option value="composition1">Composition 1</option>
                                <option value="composition2">Composition 2</option>
                                <option value="composition3">Composition 3</option>
                                <option value="devoir1">Devoir 1</option>
                                <option value="devoir2">Devoir 2</option>
                                <option value="devoir3">Devoir 3</option>
                                <option value="examen blanc 1">Examen Blanc 1</option>
                                <option value="examen blanc 2">Examen Blanc 2</option>
                                <option value="examen blanc 3">Examen Blanc 3</option>
                                <option value="examen blanc departemental">Examen Blanc Départemental</option>
                                <option value="examen blanc national">Examen Blanc National</option>
                            </select>
                            @error('nature')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="file" class="form-label">Sélectionnez le fichier</label>
                            <input type="file" id="file" name="file" class="form-control @error('file') is-invalid @enderror">
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Ajouter l'Épreuve</button>
                        </div>
                    </form>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div>
    </div>
</div>
@endsection
