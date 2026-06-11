@extends('classes.layout')

@section('content')

<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>

<div class="container py-5" style="background-color: #f8f9fa;">

    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white d-flex justify-content-between">

            <h5 class="mb-0">
                📄 Détails du Frais
            </h5>

            <a href="{{ route('frais.edit', $frais->id) }}"
               class="btn btn-light btn-sm">

                ✏️ Modifier

            </a>

        </div>

        <div class="card-body">

            {{-- NOM + MONTANT --}}
            <div class="row mb-3">

                <div class="col-md-6">

                    <strong>
                        Nom du frais :
                    </strong>

                    <div>
                        {{ $frais->nom }}
                    </div>

                </div>

                <div class="col-md-6">

                    <strong>
                        Montant total :
                    </strong>

                    <div>

                        @php
                            $montant = $frais->anneeClasseFrais
                                ->first()?->montant;
                        @endphp

                        {{ number_format($montant ?? 0, 0, ',', ' ') }} FCFA

                    </div>

                </div>

            </div>

            {{-- CLASSE + ANNEE --}}
            <div class="row mb-3">

                {{-- CLASSES --}}
                <div class="col-md-6">

                    <strong>
                        Classe :
                    </strong>

                    <div>

                        @forelse($frais->anneeClasseFrais as $pivot)

                            @if($pivot->classe)

                                <span class="badge bg-secondary">

                                    {{ $pivot->classe->nom }}

                                </span>

                            @endif

                        @empty

                            <span class="text-muted">
                                —
                            </span>

                        @endforelse

                    </div>

                </div>

                {{-- ANNEES --}}
                <div class="col-md-6">

                    <strong>
                        Année scolaire :
                    </strong>

                    <div>

                        @forelse($frais->anneeClasseFrais as $pivot)

                            @if($pivot->annee)

                                <span class="badge bg-info">

                                    {{ $pivot->annee->nom }}

                                </span>

                            @endif

                        @empty

                            <span class="text-muted">
                                —
                            </span>

                        @endforelse

                    </div>

                </div>

            </div>

            {{-- DESCRIPTION --}}
            <div class="mb-3">

                <strong>
                    Description :
                </strong>

                <div>

                    {{ $frais->description ?? '—' }}

                </div>

            </div>

            <hr>

            {{-- ECHEANCES --}}
            <h6 class="text-primary">

                📆 Échéances

            </h6>

            <table class="table table-bordered table-sm">

                <thead class="table-light">

                    <tr>

                        <th>#</th>
                        <th>Libellé</th>
                        <th>Montant (FCFA)</th>
                        <th>Date limite</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($frais->echeances as $index => $echeance)

                        <tr>

                            <td>
                                {{ $index + 1 }}
                            </td>

                            <td>
                                {{ $echeance->nom }}
                            </td>

                            <td>

                                {{ number_format($echeance->montant, 0, ',', ' ') }}

                            </td>

                            <td>

                                {{ $echeance->date_limite
                                    ? \Carbon\Carbon::parse($echeance->date_limite)->format('d/m/Y')
                                    : '—' }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4"
                                class="text-center text-muted">

                                Aucune échéance définie

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

            {{-- RETOUR --}}
            <div class="text-end">

                <a href="{{ route('frais.index') }}"
                   class="btn btn-secondary">

                    ⬅️ Retour

                </a>

            </div>

        </div>

    </div>

</div>

@endsection