<div class="sidebar d-flex flex-column p-3">
    <h5 class="text-center mb-4">📚 L'ADMINISTRATEUR – CS LE GLORIEUX</h5>

    <ul class="nav nav-pills flex-column gap-1">
         <li>
            <a href="/tableau/accueil" class="nav-link text-white">
                <i class="bi bi-speedometer2 me-2"></i> ACCUEIL
            </a>
        </li>
        <li><a href="/shows/show" class="nav-link text-white">📊 Année Scolaire & Classes</a></li>
        <li><a href="{{ route('show.import') }}" class="nav-link text-white">👨‍🎓 IMPORTATION</a></li>
        <li><a href="{{ route('tableau.paiement') }}" class="nav-link text-white">💰 LES PAIEMENTS </a></li>
        <li><a href="{{ route('tableau.utilisateur') }}" class="nav-link text-white">GESTION DES UTILISATEURS</a></li>
    </ul>
</div>
