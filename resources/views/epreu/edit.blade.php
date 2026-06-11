@extends('classes.layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            @if ($message = Session::get('success'))
                <div class="alert alert-success">{{ $message }}</div>
            @endif

            <div class="card shadow-lg">
                <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
                    <h5 class="mb-0">Modifier l'Épreuve</h5>
                    <a href="{{ route('epreuves.index') }}" class="btn btn-outline-light btn-sm">&larr; Retour</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('epreuves.update', $epreuve->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")

                        <div class="mb-3">
                            <label for="classe_id" class="form-label">Classe</label>
                            <select id="classe_id" name="classe_id" class="form-select @error('classe_id') is-invalid @enderror">
                                <option value="">-- Sélectionnez --</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}" {{ old('classe_id', $epreuve->classe_id) == $classe->id ? 'selected' : '' }}>
                                        {{ $classe->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('classe_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="trimestre" class="form-label">Trimestre</label>
                            <input type="text" id="trimestre" name="trimestre" class="form-control @error('trimestre') is-invalid @enderror" value="{{ old('trimestre', $epreuve->trimestre) }}">
                            @error('trimestre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="matiere" class="form-label">Matière</label>
                            <input type="text" id="matiere" name="matiere" class="form-control @error('matiere') is-invalid @enderror" value="{{ old('matiere', $epreuve->matiere) }}">
                            @error('matiere')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nature" class="form-label">Nature de l'Épreuve</label>
                            <input type="text" id="nature" name="nature" class="form-control @error('nature') is-invalid @enderror" value="{{ old('nature', $epreuve->nature) }}">
                            @error('nature')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="file" class="form-label">Fichier</label>
                            <input type="file" id="file" name="file" class="form-control @error('file') is-invalid @enderror">
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Modifier l'Épreuve</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
