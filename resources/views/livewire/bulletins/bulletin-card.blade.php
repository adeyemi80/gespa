<div class="card mb-3">
    @php
        $b = $this->bulletin;
    @endphp

    @if($b)
        <div class="card-body">
            <h5 class="card-title">
                {{ $b->inscription->eleve->nom ?? '' }}
                {{ $b->inscription->eleve->prenom ?? '' }}
            </h5>

            <p class="mb-1">Classe : {{ $b->inscription->classe->nom ?? '-' }}</p>
            <p class="mb-1">Trimestre : {{ $b->trimestre->nom ?? '-' }}</p>
            <p class="mb-1">Moyenne : {{ $b->moyenne_trimestrielle ?? 0 }}</p>
            <p class="mb-1">Rang : {{ $b->rang_trimestre ?? '-' }}</p>
        </div>
    @else
        <div class="card-body">
            <p class="mb-0">Bulletin introuvable.</p>
        </div>
    @endif
</div>