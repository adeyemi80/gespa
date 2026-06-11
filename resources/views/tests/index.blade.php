@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    {{-- ✅ Message de succès --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ✅ En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="bi bi-card-list"></i> LA BANQUE D'ÉPREUVES</h4>
        <a href="{{ route('tests.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Ajouter une Épreuve
        </a>
    </div>

    {{-- ✅ Formulaire de filtre --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <form method="GET" action="{{ route('tests.index') }}">
                <div class="row g-2">

                    <div class="col-md-3">
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="form-control shadow-sm"
                               placeholder="🔍 Titre, matière ou classe">
                    </div>

                    <div class="col-md-2">
                        <select name="annee_id" id="annee_id" class="form-select shadow-sm">
                            <option value="">-- Année --</option>
                            @foreach($annees as $annee)
                                <option value="{{ $annee->id }}"
                                    {{ request('annee_id') == $annee->id ? 'selected' : '' }}>
                                    {{ $annee->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="classe_id" id="classe_id" class="form-select shadow-sm">
                            <option value="">-- Classe --</option>
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}"
                                    data-annee-id="{{ $classe->annee_id }}"
                                    {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                                    {{ $classe->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="matiere_id" id="matiere_id" class="form-select">
    <option value="">-- Matière --</option>
    @foreach($matieres as $matiere)
        <option value="{{ $matiere->id }}">
            {{ $matiere->nom }}
        </option>
    @endforeach
</select>
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button class="btn btn-primary w-100">
                            <i class="bi bi-search"></i>
                        </button>
                        @if(request()->anyFilled(['search','annee_id','classe_id','matiere_id']))
                            <a href="{{ route('tests.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        @endif
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- ✅ Tableau (uniquement après filtre) --}}
    <div class="card shadow rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-primary text-white">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Titre</th>
                            <th>Matière</th>
                            <th>Classe</th>
                            <th>Année</th>
                            <th>Type</th>
                            <th>Trimestre</th>
                            <th>Fichier</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                    @if($tests instanceof \Illuminate\Pagination\LengthAwarePaginator)

                        @forelse($tests as $test)
                        <tr>
                            <td>{{ $test->id }}</td>
                            <td>{{ optional($test->date)->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ $test->titre }}</td>
                            <td>{{ $test->matiere->nom ?? '-' }}</td>
                            <td>{{ $test->classe->nom ?? '-' }}</td>
                            <td>{{ $test->annee->nom ?? '-' }}</td>
                            <td>{{ ucfirst($test->type) }}</td>
                            <td>{{ $test->trimestre->nom ?? '-' }}</td>
                            <td>
                                @if($test->fichier)
                                    <a href="{{ asset('storage/'.$test->fichier) }}" target="_blank">
                                        <i class="bi bi-download"></i>
                                    </a>
                                @else -
                                @endif
                            </td>
                            <td class="text-center text-nowrap">
                                <a href="{{ route('tests.show',$test) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('tests.edit',$test) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-3">
                                Aucune épreuve trouvée.
                            </td>
                        </tr>
                        @endforelse

                    @else
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                🔎 Veuillez appliquer un filtre pour afficher les épreuves.
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($tests instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="p-3">
                    {{ $tests->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

{{-- Bootstrap Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

{{-- JS filtrage dynamique --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    const annee   = document.getElementById('annee_id');
    const classe  = document.getElementById('classe_id');
    const matiere = document.getElementById('matiere_id');

    function filtrerClasses() {
        const anneeId = annee.value;

        classe.querySelectorAll('option[data-annee]').forEach(opt => {
            opt.hidden = anneeId && opt.dataset.annee !== anneeId;
        });

        if (anneeId && classe.selectedOptions[0]?.hidden) {
            classe.value = '';
        }
    }

    function filtrerMatieres() {
        const classeId = classe.value;

        matiere.querySelectorAll('option[data-classe]').forEach(opt => {
            opt.hidden = classeId && opt.dataset.classe !== classeId;
        });

        if (classeId && matiere.selectedOptions[0]?.hidden) {
            matiere.value = '';
        }
    }

    // 🔁 Événements
    annee.addEventListener('change', () => {
        filtrerClasses();
        classe.value = '';
        matiere.value = '';
    });

    classe.addEventListener('change', () => {
        filtrerMatieres();
        matiere.value = '';
    });

    // ✅ Initialisation (TRÈS IMPORTANT)
    filtrerClasses();
    filtrerMatieres();
});
</script>
@endsection