<div class="sidebar d-flex flex-column p-3">
    <h5 class="text-center mb-4">📚 LE DIRECTEUR – CS LE GLORIEUX</h5>

    <ul class="nav nav-pills flex-column gap-1">
       <li>
            <a href="/tableau/accueil" class="nav-link text-white">
                <i class="bi bi-speedometer2 me-2"></i> ACCUEIL
            </a>
        </li>
        <li>
            <a href="/tableau/annees" class="nav-link text-white">
                <i class="bi bi-speedometer2 me-2"></i> ANNEES SCOLAIRES
            </a>
        </li>
        <li>
            <a href="{{ route('tableau.paiement') }}" class="nav-link text-white">
                <i class="bi bi-book me-2"></i> PAIEMENTS
            </a>
        </li>
        <li>
            <a href="{{ route('paiements.index') }}" class="nav-link text-white">
                <i class="bi bi-wallet2 me-2"></i> Historique des paiements 
            </a>
        </li>
        <li>
            <a href="{{ route('paiements.historique') }}" class="nav-link text-white">
                <i class="bi bi-bar-chart-line me-2"></i> Paiements par | jour | Mois | Année
            </a>
        </li>
    </ul>
</div>
