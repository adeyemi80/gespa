@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">

    <div class="card shadow-sm">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <span><i class="bi bi-eye"></i> Détail de la séance #{{ $tdSeance->id }}</span>
            <div class="d-flex gap-2">
                <a href="{{ route('td-seances.edit', $tdSeance) }}" class="btn btn-light btn-sm">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
                <a href="{{ route('td-seances.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3 text-muted">Identifiant</dt>
                <dd class="col-sm-9"># {{ $tdSeance->id }}</dd>

                <dt class="col-sm-3 text-muted">Année</dt>
                <dd class="col-sm-9">
                    {{ $tdSeance->annee->libelle ?? $tdSeance->annee->nom ?? '—' }}
                </dd>

                <dt class="col-sm-3 text-muted">Classe</dt>
                <dd class="col-sm-9">{{ $tdSeance->classe->niveau ?? '—' }}</dd>

                <dt class="col-sm-3 text-muted">Date</dt>
                <dd class="col-sm-9">{{ $tdSeance->date->translatedFormat('l d F Y') }}</dd>

                <dt class="col-sm-3 text-muted">Thème</dt>
                <dd class="col-sm-9">
                    {{ $tdSeance->libelle ?? '—' }}
                </dd>

                <dt class="col-sm-3 text-muted">Créée le</dt>
                <dd class="col-sm-9">{{ $tdSeance->created_at->format('d/m/Y à H:i') }}</dd>

                <dt class="col-sm-3 text-muted">Modifiée le</dt>
                <dd class="col-sm-9">{{ $tdSeance->updated_at->format('d/m/Y à H:i') }}</dd>
            </dl>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <form action="{{ route('td-seances.destroy', $tdSeance) }}" method="POST"
                  onsubmit="return confirm('Supprimer définitivement cette séance ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-trash"></i> Supprimer
                </button>
            </form>
            <a href="{{ route('td-seances.edit', $tdSeance) }}" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil"></i> Modifier
            </a>
        </div>
    </div>
</div>
@endsection