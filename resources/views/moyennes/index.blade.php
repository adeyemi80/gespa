@extends('tableau.neutre')

@section('title', 'Moyennes et Rangs des Élèves')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    <h3 class="mb-4 text-primary fw-bold text-center">Moyennes et Rangs des Élèves</h3>

    {{-- Sélection de l'année scolaire --}}
   <form method="GET" class="mb-4 d-flex align-items-center gap-3 justify-content-center">
    <label for="annee_id" class="form-label mb-0 fw-semibold">Sélectionner une année scolaire :</label>
    <select name="annee_id" id="annee_id" class="form-select w-auto" onchange="this.form.submit()">
        @php
            // Trier les années pour mettre l'année en cours en premier
            $sortedAnnees = $annees->sortByDesc(function($annee) use ($annee_id) {
                // On met l'année sélectionnée (ou l'année en cours) en premier
                return ($annee->id == $annee_id) ? 1 : 0;
            });
        @endphp

        @foreach($sortedAnnees as $annee)
            <option value="{{ $annee->id }}" @if($annee->id == $annee_id) selected @endif>
                {{ $annee->nom ?? $annee->id }}
            </option>
        @endforeach
    </select>
    <noscript>
        <button type="submit" class="btn btn-primary">Filtrer</button>
    </noscript>
</form>

    {{-- Bouton de recalcul --}}
    <form action="{{ route('moyennes.recalculer') }}" method="POST" class="mb-3 text-center">
        @csrf
        <input type="hidden" name="annee_id" value="{{ $annee_id }}">
        <button type="submit" class="btn btn-warning fw-bold">
            🔄 Calculer toutes les moyennes et rangs
        </button>
    </form>

    {{-- Message de confirmation --}}
    @if(session('success'))
        <div class="alert alert-success text-center fw-bold">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tableau des moyennes --}}
   {{-- <div class="table-responsive shadow-sm rounded">
        <table class="table table-striped table-bordered align-middle text-center mb-0">
            <thead class="table-primary text-center align-middle">
                <tr>
                    <th class="text-start">Nom</th>
                    <th class="text-start">Prénom</th>
                    <th>Classe</th>
                    @for($t = 1; $t <= 3; $t++)
                        <th>Moy. Trim {{ $t }}</th>
                        <th>Moy. Scientifique {{ $t }}</th>
                        <th>Moy. Littéraire {{ $t }}</th>
                        <th>Rang Trim {{ $t }}</th>
                    @endfor
                    <th class="fw-bold">Moyenne Annuelle</th>
                    <th class="fw-bold">Rang Annuel</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inscriptions as $inscription)
                    <tr>
                       
                        <td class="text-start">{{ optional($inscription->eleve)->nom ?? 'nom inconnu' }}</td>
                        <td class="text-start">{{ optional($inscription->eleve)->prenom ?? 'prenom inconnu' }}</td>
                        <td>{{ optional($inscription->classe)->nom ?? 'nom inconnu' }}</td>

                       
                        @php
                            $moyennes = $inscription->moyennes ?? collect();
                            $totalTrim = 0;
                            $countTrim = 0;
                        @endphp
                        @for($t = 1; $t <= 3; $t++)
                            @php
                                $moy = $moyennes->firstWhere('trimestre_id', $t);
                                $moyTrim = $moy->moyenne_trimestrielle ?? '-';
                                $moySci = $moy->moyenne_scientifique ?? '-';
                                $moyLit = $moy->moyenne_litteraire ?? '-';
                                $rangTrim = $moy->rang_trimestre ?? '-';

                                // Calcul pour moyenne annuelle
                                if($moyTrim !== '-') {
                                    $totalTrim += $moyTrim;
                                    $countTrim++;
                                }
                            @endphp
                            <td><span class="badge bg-info text-dark">{{ $moyTrim }}</span></td>
                            <td><span class="badge bg-success text-white">{{ $moySci }}</span></td>
                            <td><span class="badge bg-warning text-dark">{{ $moyLit }}</span></td>
                            <td><span class="badge bg-secondary text-white">{{ $rangTrim }}</span></td>
                        @endfor

                       
                        @php
                            $moyAnnValeur = $countTrim > 0 ? round($totalTrim / $countTrim, 2) : '-';
                            // Récupération du rang annuel si disponible
                            $rangAnnValeur = $moyennes->first()?->rang_annuel ?? '-';
                        @endphp
                        <td><span class="badge bg-info text-dark">{{ $moyAnnValeur }}</span></td>
                        <td><span class="badge bg-secondary text-white">{{ $rangAnnValeur }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="20" class="text-center text-muted fst-italic">
                            Aucune inscription trouvée pour cette année.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>--}}
</div>
@endsection
