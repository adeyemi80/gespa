@foreach($fiches as $fiche)
    <h3 class="text-center mt-4">{{ $fiche['classe']->nom }} – {{ $fiche['matiere']->nom }}</h3>
    @include('fiches.table', ['eleves' => $fiche['eleves']])
    <div class="page-break"></div> {{-- Sépare les fiches pour PDF --}}
@endforeach

<style>
.page-break { page-break-after: always; }
</style>
