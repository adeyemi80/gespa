<div class="sidebar d-flex flex-column p-3">
    <h5 class="text-center mb-4">📚 LE CENSORAT – CS LE GLORIEUX</h5>

    <ul class="nav nav-pills flex-column gap-1">
       <li>
            <a href="/tableau/accueil" class="nav-link text-white">
                <i class="bi bi-speedometer2 me-2"></i> ACCUEIL
            </a>
        </li>
        <li>
            <a href="/tableau/emplois" class="nav-link text-white">
                <i class="bi bi-speedometer2 me-2"></i> EMPLOIS DU TEMPS
            </a>
        </li>
        <li>
            <a href="{{ route('tableau.planning') }}" class="nav-link text-white">
                <i class="bi bi-people me-2"></i> PLANING DES ACTIVITES
            </a>
        </li>
         <li>
            <a href="{{ route('tableau.bulletin') }}" class="nav-link text-white">
                <i class="bi bi-book me-2"></i> BULLETINS
            </a>
        </li>
        <li>
            <a href="{{ route('tableau.examen') }}" class="nav-link text-white">
                <i class="bi bi-book me-2"></i> EXAMENS BLANCS
            </a>
        </li>
        <li>
            <a href="{{ route('tableau.progtd') }}" class="nav-link text-white">
                <i class="bi bi-bar-chart-line me-2"></i> PROGRAMMES DES TDs
            </a>
        </li>
    </ul>
</div>
