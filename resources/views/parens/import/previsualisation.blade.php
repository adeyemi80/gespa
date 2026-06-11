@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    {{-- Titre --}}
    <div class="d-flex align-items-center mb-4">
        <div class="me-3">
            <span class="badge bg-warning p-3 rounded-circle">
                <i class="bi bi-eye fs-4 text-white"></i>
            </span>
        </div>
        <div>
            <h3 class="mb-0 fw-bold">Prévisualisation des parents</h3>
            <small class="text-muted">Corrigez les erreurs avant validation</small>
        </div>
    </div>

    {{-- Statistiques --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center bg-success bg-opacity-10">
                <div class="card-body">
                    <h5 class="card-title text-success">{{ count($valides) }}</h5>
                    <small class="text-muted">Valides</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-danger bg-opacity-10">
                <div class="card-body">
                    <h5 class="card-title text-danger">{{ count($erreurs) }}</h5>
                    <small class="text-muted">Erreurs</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> 
                Corrigez les champs en rouge puis cliquez "Valider l'import"
            </div>
        </div>
    </div>

    {{-- Erreurs détectées --}}
    @if(!empty($erreurs))
        <div class="alert alert-danger shadow-sm mb-4">
            <h6 class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle"></i> 
                {{ count($erreurs) }} ligne(s) avec erreur(s)
            </h6>
        </div>
    @endif

    {{-- Formulaire pour valider l'import --}}
    <form action="{{ route('parens.import.validate') }}" method="POST">
        @csrf

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light sticky-top">
                    <tr>
                        <th><i class="bi bi-list-ol"></i> Ligne</th>
                        <th><i class="bi bi-hash"></i> Matricule</th>
                        <th><i class="bi bi-person"></i> Nom parent</th>
                        <th><i class="bi bi-person-badge"></i> Prénom parent</th>
                        <th><i class="bi bi-telephone"></i> Téléphone</th>
                        <th><i class="bi bi-geo-alt"></i> Adresse</th>
                        <th><i class="bi bi-exclamation-diamond"></i> Erreurs</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Lignes VALIDES (vert) --}}
                    @forelse($valides as $i => $ligne)
                    <tr class="table-success">
                        <td>{{ $i + 2 }}</td>
                        <td>
                            <strong>{{ $ligne['matricule'] ?? 'N/A' }}</strong>
                            <input type="hidden" name="valides[{{ $i }}][matricule]" 
                                   value="{{ $ligne['matricule'] ?? '' }}">
                        </td>
                        <td>
                            <input type="text" 
                                   name="valides[{{ $i }}][nom_parent]" 
                                   value="{{ $ligne['nom_parent'] ?? '' }}"
                                   class="form-control form-control-sm" required>
                        </td>
                        <td>
                            <input type="text" 
                                   name="valides[{{ $i }}][prenom_parent]" 
                                   value="{{ $ligne['prenom_parent'] ?? '' }}"
                                   class="form-control form-control-sm">
                        </td>
                        <td>
                            <input type="tel" 
                                   name="valides[{{ $i }}][telephone_parent]" 
                                   value="{{ $ligne['telephone_parent'] ?? '' }}"
                                   class="form-control form-control-sm" required>
                        </td>
                        <td>
                            <input type="text" 
                                   name="valides[{{ $i }}][adresse_parent]" 
                                   value="{{ $ligne['adresse_parent'] ?? '' }}"
                                   class="form-control form-control-sm">
                        </td>
                        <td>
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle"></i> Valide
                            </span>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">Aucune ligne valide</td></tr>
                    @endforelse

                    {{-- Lignes AVEC ERREURS (rouge) --}}
                    @forelse($erreurs as $i => $ligne)
                    <tr class="table-danger">
                        <td>{{ $ligne['ligne'] ?? ($i + 2) }}</td>
                        <td>
                            <strong>{{ $ligne['matricule'] ?? 'N/A' }}</strong>
                            <input type="hidden" name="erreurs[{{ $i }}][matricule]" 
                                   value="{{ $ligne['matricule'] ?? '' }}">
                            @if($ligne['matricule'] ?? false)
                                <?php $eleve = \App\Models\Eleve::where('matricule', $ligne['matricule'])->first(); ?>
                                @if($eleve)
                                    <br><small class="text-muted">{{ $eleve->nom }} {{ $eleve->prenom }}</small>
                                @endif
                            @endif
                        </td>
                        <td>
                            <input type="text" 
                                   name="erreurs[{{ $i }}][nom_parent]" 
                                   value="{{ $ligne['nom_parent'] ?? '' }}"
                                   class="form-control form-control-sm border-danger" required>
                        </td>
                        <td>
                            <input type="text" 
                                   name="erreurs[{{ $i }}][prenom_parent]" 
                                   value="{{ $ligne['prenom_parent'] ?? '' }}"
                                   class="form-control form-control-sm">
                        </td>
                        <td>
                            <input type="tel" 
                                   name="erreurs[{{ $i }}][telephone_parent]" 
                                   value="{{ $ligne['telephone_parent'] ?? '' }}"
                                   class="form-control form-control-sm border-danger" required>
                        </td>
                        <td>
                            <input type="text" 
                                   name="erreurs[{{ $i }}][adresse_parent]" 
                                   value="{{ $ligne['adresse_parent'] ?? '' }}"
                                   class="form-control form-control-sm">
                        </td>
                        <td>
                            <div class="alert alert-danger p-2 mb-0 small">
                                @foreach($ligne['messages'] ?? [] as $msg)
                                    <div>{{ $msg }}</div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-success">Aucune erreur !</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Boutons --}}
        <div class="d-flex justify-content-between align-items-center mt-4 p-3 bg-light rounded">
            <div>
                <span class="badge bg-info fs-6">
                    {{ count($valides) }} valide(s) • {{ count($erreurs) }} erreur(s)
                </span>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg" {{ empty($valides) ? 'disabled' : '' }}>
                    <i class="bi bi-check-circle me-1"></i> 
                    Valider l'import ({{ count($valides) }})
                </button>
                <a href="{{ route('parens.import.form') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-arrow-left me-1"></i> Nouveau fichier
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
