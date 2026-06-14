<div class="sidebar d-flex flex-column p-3">
    <h5 class="text-center mb-4">📚 LA SECRETAIRE – CS LE GLORIEUX</h5>

    <ul class="nav nav-pills flex-column gap-1">
         <li>
            <a href="/tableau/accueil" class="nav-link text-white">
                <i class="bi bi-speedometer2 me-2"></i> ACCUEIL
            </a>
        </li>
        <li>
            <a href="/tableau/inscription" class="nav-link text-white">
                <i class="bi bi-speedometer2 me-2"></i> INSCRIPTION
            </a>
        </li>
         <li><a href="{{ route('show.import') }}" class="nav-link text-white">👨‍🎓 IMPORTATION</a></li>
        <li>
            <a href="{{ route('tableau.show')}}" class="nav-link text-white">
                <i class="bi bi-people me-2"></i> PAIEMENTS
            </a>
        </li>
        {{--<li>
            <a href="{{ route('td.dirige') }}" class="nav-link text-white">
                <i class="bi bi-book me-2"></i> LES TRAVAUX DIRIGES
            </a>
        </li>
         <li>
            <a href="{{ route('tableau.examen') }}" class="nav-link text-white">
                <i class="bi bi-book me-2"></i> EXAMENS BLANCS
            </a>
        </li>--}}
        <li>
            <a href="{{ route('tableau.pedagogie') }}" class="nav-link text-white">
                <i class="bi bi-wallet2 me-2"></i> PEDAGOGIE
            </a>
        </li>
        {{--<li>
            <a href="{{ route('paiements.historique') }}" class="nav-link text-white">
                <i class="bi bi-bar-chart-line me-2"></i> Paiements par | jour | Mois | Année
            </a>
        </li>--}}
    </ul>
</div>
