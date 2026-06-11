{{-- IMPORTANT : si tu gardes extends, ton layout doit être compatible avec Livewire --}}
@php
    // Si tu veux garder le même layout que avant
@endphp

<div> {{-- Conteneur Livewire requis --}}
    <div class="container py-4">

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">💰 Frais par élève</h5>
            </div>

            <div class="card-body">

                {{-- ✅ Message (géré via session ou propriété Livewire) --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- 🔍 Recherche avec Livewire --}}
                <div class="mb-4">
                    <div class="row g-3">

                        {{-- Année --}}
                        <div class="col-md-4">
                            <label class="form-label">Année</label>
                            <select wire:model.llive="annee_id" class="form-select">
                                <option value="">— Toutes —</option>
                                @foreach($annees as $annee)
                                    <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Classe --}}
                        <div class="col-md-4">
                            <label class="form-label">Classe</label>
                            <select wire:model.live="classe_id" class="form-select">
                                <option value="">— Choisir —</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Élève --}}
                        <div class="col-md-4">
                            <label class="form-label">Élève</label>
                            <select wire:model.live="eleve_id" class="form-select">
                                <option value="">— Tous —</option>
                                @foreach($eleves as $eleve) {{-- Tu devras exposer $eleves depuis Livewire --}}
                                    <option value="{{ $eleve->id }}">
                                        {{ $eleve->nom }} {{ $eleve->prenom ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="mt-3 text-end">
                        {{-- Pas besoin de bouton "rechercher" si tu veux filtrer en live --}}
                        <a href="{{ route('inscription-frais.index') }}"
                           class="btn btn-outline-secondary">🔄 Réinitialiser</a>
                    </div>
                </div>

                {{-- 📋 Tableau --}} 
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Élève</th>
                                <th>Classe</th>
                                <th>Frais</th>
                                <th>Montant</th>
                                <th>Payé</th>
                                <th>Reste</th>
                                <th>Statut</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($inscriptionFrais as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        {{ $item->inscription->eleve->nom ?? '' }}
                                        {{ $item->inscription->eleve->prenom ?? '' }}
                                    </td>
                                    <td>{{ $item->inscription->classe->nom ?? '—' }}</td>
                                    <td>{{ $item->frais->nom ?? '—' }}</td>
                                    <td>{{ number_format($item->montant_frais, 0, ',', ' ') }} F</td>
                                    <td>{{ number_format($item->montant_paye, 0, ',', ' ') }} F</td>
                                    <td>{{ number_format($item->reste, 0, ',', ' ') }} F</td>
                                    <td>
                                        @if($item->statut === 'soldé')
                                            <span class="badge bg-success">Soldé</span>
                                        @elseif($item->statut === 'partiellement_payé')
                                            <span class="badge bg-warning text-dark">Partiel</span>
                                        @else
                                            <span class="badge bg-danger">Non payé</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('inscription-frais.show', $item->id) }}"
                                           class="btn btn-sm btn-info text-white">👁️</a>
                                        <a href="{{ route('inscription-frais.edit', $item->id) }}"
                                           class="btn btn-sm btn-warning">✏️</a>
                                        <form action="{{ route('inscription-frais.destroy', $item->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Supprimer ce frais ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">
                                        Aucun frais trouvé
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Livewire --}}
                {{ $inscriptionFrais->links() }}

            </div>
        </div>
    </div>
</div>