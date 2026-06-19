@extends('tableau.neutre')

@section('title', 'Liste des élèves')

@section('content')

<button
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
    class="btn btn-secondary mb-3">
    ⬅️ Retour
</button>

<div class="container py-4" style="background-color: #f8f9fa; min-height: 100vh;">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-center w-75 mx-auto" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow rounded">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">👨‍🎓 Liste des élèves</h5>
            <a href="{{ route('eleves.create') }}" class="btn btn-light btn-sm">
                ➕ Ajouter un élève
            </a>
        </div>

        <div class="card-body">

            {{-- FORMULAIRE DE FILTRAGE --}}
            <form method="GET" action="{{ route('eleves.index') }}" class="row g-3 align-items-end mb-4">

                <div class="col-md-3">
                    <label class="form-label fw-bold">Cycle</label>
                    <select id="cycle" name="cycle_id" class="form-select">
                        <option value="">-- Tous les cycles --</option>
                        @foreach($cycles as $cycle)
                            <option value="{{ $cycle->id }}" {{ request('cycle_id') == $cycle->id ? 'selected' : '' }}>
                                {{ $cycle->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Classe</label>
                    <select id="classe" name="classe_id" class="form-select">
                        <option value="">-- Toutes les classes --</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}" {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Année Scolaire</label>
                    <select name="annee_id" class="form-select">
                        <option value="">-- Toutes les années --</option>
                        @foreach($annees as $annee)
                            <option value="{{ $annee->id }}" {{ request('annee_id') == $annee->id ? 'selected' : '' }}>
                                {{ $annee->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">🔍 Rechercher</button>
                    <a href="{{ route('eleves.index') }}" class="btn btn-secondary">🔄 Réinitialiser</a>
                </div>

            </form>

            {{-- ===== COMPTEUR DE RÉSULTATS ===== --}}
            @if($eleves->count() > 0)
            <div class="row g-3 mb-4">

                {{-- Total affiché --}}
                <div class="col-6 col-md-3">
                    <div class="rounded-3 p-3 text-center text-white shadow-sm"
                         style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                        <div style="font-size: 2rem; font-weight: 800;">{{ $eleves->total() }}</div>
                        <div class="small fw-semibold">
                            👨‍🎓 Élève{{ $eleves->total() > 1 ? 's' : '' }} trouvé{{ $eleves->total() > 1 ? 's' : '' }}
                        </div>
                    </div>
                </div>

                {{-- Filtre année --}}
                @if(request('annee_id'))
                <div class="col-6 col-md-3">
                    <div class="rounded-3 p-3 text-center text-white shadow-sm"
                         style="background: linear-gradient(135deg, #0ea5e9, #3b82f6);">
                        <div style="font-size: 2rem; font-weight: 800;">{{ $eleves->total() }}</div>
                        <div class="small fw-semibold">
                            📅 {{ $annees->find(request('annee_id'))?->nom ?? 'Année' }}
                        </div>
                    </div>
                </div>
                @endif

                {{-- Filtre classe --}}
                @if(request('classe_id'))
                <div class="col-6 col-md-3">
                    <div class="rounded-3 p-3 text-center text-white shadow-sm"
                         style="background: linear-gradient(135deg, #10b981, #059669);">
                        <div style="font-size: 2rem; font-weight: 800;">{{ $eleves->total() }}</div>
                        <div class="small fw-semibold">
                            🏫 {{ $classes->find(request('classe_id'))?->nom ?? 'Classe' }}
                        </div>
                    </div>
                </div>
                @endif

                {{-- Filtre cycle --}}
                @if(request('cycle_id'))
                <div class="col-6 col-md-3">
                    <div class="rounded-3 p-3 text-center text-white shadow-sm"
                         style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <div style="font-size: 2rem; font-weight: 800;">{{ $eleves->total() }}</div>
                        <div class="small fw-semibold">
                            🔄 {{ $cycles->find(request('cycle_id'))?->nom ?? 'Cycle' }}
                        </div>
                    </div>
                </div>
                @endif

                {{-- Garçons / Filles --}}
                @php
                    $garcons = $eleves->getCollection()->filter(fn($i) => strtolower($i->eleve?->sexe) === 'm' || strtolower($i->eleve?->sexe) === 'masculin')->count();
                    $filles  = $eleves->getCollection()->filter(fn($i) => strtolower($i->eleve?->sexe) === 'f' || strtolower($i->eleve?->sexe) === 'féminin' || strtolower($i->eleve?->sexe) === 'feminin')->count();
                @endphp

                <div class="col-6 col-md-3">
                    <div class="rounded-3 p-3 text-center text-white shadow-sm"
                         style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                        <div style="font-size: 2rem; font-weight: 800;">{{ $garcons }}</div>
                        <div class="small fw-semibold">👦 Garçons</div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="rounded-3 p-3 text-center text-white shadow-sm"
                         style="background: linear-gradient(135deg, #ec4899, #db2777);">
                        <div style="font-size: 2rem; font-weight: 800;">{{ $filles }}</div>
                        <div class="small fw-semibold">👧 Filles</div>
                    </div>
                </div>

                {{-- Page courante --}}
                <div class="col-6 col-md-3">
                    <div class="rounded-3 p-3 text-center shadow-sm"
                         style="background: rgba(99,102,241,0.1); border: 2px solid rgba(99,102,241,0.3);">
                        <div style="font-size: 2rem; font-weight: 800; color: #6366f1;">
                            {{ $eleves->firstItem() }}–{{ $eleves->lastItem() }}
                        </div>
                        <div class="small fw-semibold text-muted">
                            📄 Page {{ $eleves->currentPage() }}/{{ $eleves->lastPage() }}
                        </div>
                    </div>
                </div>

            </div>
            @endif
            {{-- ===== FIN COMPTEUR ===== --}}

            {{-- TABLEAU --}}
            <div class="table-responsive">

                @if($eleves->count() > 0)

                    <table class="table table-bordered table-hover align-middle bg-white text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>Matricule</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Classe</th>
                                <th>Année</th>
                                <th>Date de naissance</th>
                                <th>Sexe</th>
                                <th>Nationalité</th>
                                <th>Statut</th>
                                <th>Lieu de naissance</th>
                                <th>Photo</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eleves as $inscription)
                                @php $eleve = $inscription->eleve; @endphp
                                <tr>
                                    <td>{{ $eleve->matricule ?? '—' }}</td>
                                    <td>{{ $eleve->nom ?? '—' }}</td>
                                    <td>{{ $eleve->prenom ?? '—' }}</td>
                                    <td>{{ $inscription->classe?->nom ?? '—' }}</td>
                                    <td>{{ $inscription->annee?->nom ?? '—' }}</td>
                                    <td>{{ $eleve->date_naissance ?? '—' }}</td>
                                    <td>{{ $eleve->sexe ?? '—' }}</td>
                                    <td>{{ $eleve->nationalite ?? '—' }}</td>
                                    <td>{{ $eleve->statut ?? '—' }}</td>
                                    <td>{{ $eleve->lieu_naissance ?? '—' }}</td>
                                    <td>
                                        @if($eleve->photo)
                                            <img src="{{ asset('storage/' . $eleve->photo) }}"
                                                 width="50" class="rounded-circle">
                                        @else
                                            <span class="text-muted">Aucune</span>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('eleves.show', $eleve) }}"
                                               class="btn btn-outline-info btn-sm" title="Voir">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <a href="{{ route('eleves.edit', $eleve) }}"
                                               class="btn btn-outline-warning btn-sm" title="Modifier">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('eleves.destroy', $eleve) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Supprimer cet élève ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm" title="Supprimer">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $eleves->appends(request()->query())->links() }}
                    </div>

                @else
                    <p class="text-center text-muted py-4">Aucun élève trouvé.</p>
                @endif

            </div>
        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) new bootstrap.Alert(alert).close();
    }, 4000);
</script>

<script>
    const savedCycleId  = "{{ request('cycle_id') }}";
    const savedClasseId = "{{ request('classe_id') }}";
    const cycleSelect   = document.getElementById('cycle');
    const classeSelect  = document.getElementById('classe');

    function loadClasses(cycleId, preselectId = null) {
        if (!cycleId) {
            classeSelect.innerHTML = '<option value="">-- Toutes les classes --</option>';
            @foreach($classes as $classe)
                classeSelect.innerHTML += `<option value="{{ $classe->id }}">{{ $classe->nom }}</option>`;
            @endforeach
            return;
        }
        classeSelect.innerHTML = '<option value="">Chargement...</option>';
        fetch(`/cycles/${cycleId}/classes`)
            .then(r => r.json())
            .then(data => {
                classeSelect.innerHTML = '<option value="">-- Toutes les classes --</option>';
                data.forEach(c => {
                    const selected = preselectId == c.id ? 'selected' : '';
                    classeSelect.innerHTML += `<option value="${c.id}" ${selected}>${c.nom}</option>`;
                });
            });
    }

    cycleSelect.addEventListener('change', function () { loadClasses(this.value); });
    if (savedCycleId) { loadClasses(savedCycleId, savedClasseId); }
</script>

@endsection