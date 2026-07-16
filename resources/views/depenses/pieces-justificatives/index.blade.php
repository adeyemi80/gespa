@extends('tableau.neutre')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Pièces justificatives</h1>
            <p class="text-muted mb-0">
                Dépense {{ $depense->numero_piece }} — {{ $depense->libelle }}
            </p>
        </div>
        <a href="{{ route('depenses.live') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour aux dépenses
        </a>
    </div>

    @if (session('succes'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('succes') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $erreur)
                    <li>{{ $erreur }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <span class="fw-semibold">Ajouter des pièces justificatives</span>
        </div>
        <div class="card-body">
            <form action="{{ route('pieces-justificatives.store', $depense) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-2 align-items-end">
                    <div class="col-md-9">
                        <input type="file" name="fichiers[]" multiple class="form-control" required>
                        <div class="form-text">PDF, JPG ou PNG, 5 Mo max par fichier.</div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-upload"></i> Téléverser
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fichier</th>
                        <th>Type</th>
                        <th>Taille</th>
                        <th>Ajouté le</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($depense->piecesJustificatives as $piece)
                        <tr>
                            <td><i class="bi bi-paperclip"></i> {{ $piece->nom_fichier }}</td>
                            <td class="text-muted small">{{ $piece->type_mime ?? '—' }}</td>
                            <td class="text-muted small">
                                {{ $piece->taille ? number_format($piece->taille / 1024, 0) . ' Ko' : '—' }}
                            </td>
                            <td class="text-muted small">{{ $piece->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('pieces-justificatives.apercu', $piece) }}"
                                   target="_blank" class="btn btn-sm btn-outline-secondary" title="Aperçu">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('pieces-justificatives.telecharger', $piece) }}"
                                   class="btn btn-sm btn-outline-primary" title="Télécharger">
                                    <i class="bi bi-download"></i>
                                </a>
                                <form action="{{ route('pieces-justificatives.destroy', $piece) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer cette pièce justificative ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Aucune pièce justificative pour cette dépense.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection