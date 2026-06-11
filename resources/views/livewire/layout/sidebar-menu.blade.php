<nav class="bg-blue-600 text-white w-64 min-h-screen p-4 fixed left-0 top-0">
    <div class="mb-8 text-center">
        <h1 class="text-2xl font-bold">GESPA</h1>
        <p class="text-sm">Collège Le Glorieux</p>
    </div>

    <ul class="space-y-2">
        {{-- Tableau de bord --}}
        <li>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-3 rounded hover:bg-blue-700">
                <i class="fas fa-home"></i>
                <span>Tableau de bord</span>
            </a>
        </li>

        {{-- Élèves --}}
        <li>
            <button 
                wire:click="toggleMenu('eleves')" 
                class="w-full flex items-center justify-between p-3 rounded hover:bg-blue-700"
            >
                <span class="flex items-center gap-3">
                    <i class="fas fa-users"></i>
                    <span>Élèves</span>
                </span>
                <i class="fas fa-chevron-down"></i>
            </button>
            
            @if($this->showMenu['eleves'])
                <ul class="ml-6 mt-2 space-y-1">
                    <li><a href="{{ route('eleves.index') }}" class="block p-2 hover:bg-blue-500 rounded">Liste des élèves</a></li>
                    <li><a href="{{ route('eleves.create') }}" class="block p-2 hover:bg-blue-500 rounded">Ajouter un élève</a></li>
                    <li><a href="{{ route('inscriptions.index') }}" class="block p-2 hover:bg-blue-500 rounded">Inscriptions</a></li>
                </ul>
            @endif
        </li>

        {{-- Classes --}}
        <li>
            <button 
                wire:click="toggleMenu('classes')" 
                class="w-full flex items-center justify-between p-3 rounded hover:bg-blue-700"
            >
                <span class="flex items-center gap-3">
                    <i class="fas fa-school"></i>
                    <span>Classes</span>
                </span>
                <i class="fas fa-chevron-down"></i>
            </button>
            
            @if($this->showMenu['classes'])
                <ul class="ml-6 mt-2 space-y-1">
                    <li><a href="{{ route('classes.index') }}" class="block p-2 hover:bg-blue-500 rounded">Liste des classes</a></li>
                    <li><a href="{{ route('classes.matieres') }}" class="block p-2 hover:bg-blue-500 rounded">Matières par classe</a></li>
                </ul>
            @endif
        </li>

        {{-- Notes --}}
        <li>
            <button 
                wire:click="toggleMenu('notes')" 
                class="w-full flex items-center justify-between p-3 rounded hover:bg-blue-700"
            >
                <span class="flex items-center gap-3">
                    <i class="fas fa-edit"></i>
                    <span>Notes</span>
                </span>
                <i class="fas fa-chevron-down"></i>
            </button>
            
            @if($this->showMenu['notes'])
                <ul class="ml-6 mt-2 space-y-1">
                    <li><a href="{{ route('notes.index') }}" class="block p-2 hover:bg-blue-500 rounded">Saisir les notes</a></li>
                    <li><a href="{{ route('notes.create') }}" class="block p-2 hover:bg-blue-500 rounded">Voir les notes</a></li>
                    <li><a href="{{ route('notes.import') }}" class="block p-2 hover:bg-blue-500 rounded">Importer (Excel)</a></li>
                </ul>
            @endif
        </li>

        {{-- Bulletins --}}
        <li>
            <button 
                wire:click="toggleMenu('bulletins')" 
                class="w-full flex items-center justify-between p-3 rounded hover:bg-blue-700"
            >
                <span class="flex items-center gap-3">
                    <i class="fas fa-file-alt"></i>
                    <span>Bulletins</span>
                </span>
                <i class="fas fa-chevron-down"></i>
            </button>
            
            @if($this->showMenu['bulletins'])
                <ul class="ml-6 mt-2 space-y-1">
                    <li><a href="{{ route('bulletins.generer') }}" class="block p-2 hover:bg-blue-500 rounded">Générer les bulletins</a></li>
                    <li><a href="{{ route('bulletins.pdf.classe') }}" class="block p-2 hover:bg-blue-500 rounded">PDF classe entière</a></li>
                    <li><a href="{{ route('bulletins') }}" class="block p-2 hover:bg-blue-500 rounded">Bulletin individuel</a></li>
                </ul>
            @endif
        </li>

        {{-- Années scolaires --}}
        <li>
            <button 
                wire:click="toggleMenu('annees')" 
                class="w-full flex items-center justify-between p-3 rounded hover:bg-blue-700"
            >
                <span class="flex items-center gap-3">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Années scolaires</span>
                </span>
                <i class="fas fa-chevron-down"></i>
            </button>
            
            @if($this->showMenu['annees'])
                <ul class="ml-6 mt-2 space-y-1">
                    <li><a href="{{ route('annees.index') }}" class="block p-2 hover:bg-blue-500 rounded">Liste des années</a></li>
                    <li><a href="{{ route('annees') }}" class="block p-2 hover:bg-blue-500 rounded">Année en cours</a></li>
                </ul>
            @endif
        </li>

        {{-- Professeurs --}}
        <li>
            <button 
                wire:click="toggleMenu('professeurs')" 
                class="w-full flex items-center justify-between p-3 rounded hover:bg-blue-700"
            >
                <span class="flex items-center gap-3">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Professeurs</span>
                </span>
                <i class="fas fa-chevron-down"></i>
            </button>
            
            @if($this->showMenu['professeurs'])
                <ul class="ml-6 mt-2 space-y-1">
                    <li><a href="{{ route('parens.index') }}" class="block p-2 hover:bg-blue-500 rounded">Liste des profs</a></li>
                    <li><a href="{{ route('parens.create') }}" class="block p-2 hover:bg-blue-500 rounded">Ajouter un prof</a></li>
                </ul>
            @endif
        </li>

        {{-- Utilisateurs & Rôles --}}
        <li>
            <a href="{{ route('users.index') }}" class="flex items-center gap-3 p-3 rounded hover:bg-blue-700">
                <i class="fas fa-user-shield"></i>
                <span>Utilisateurs & Rôles</span>
            </a>
        </li>

        {{-- Paramètres --}}
        <li>
            <a href="{{ route('passages.index') }}" class="flex items-center gap-3 p-3 rounded hover:bg-blue-700">
                <i class="fas fa-cog"></i>
                <span>Paramètres</span>
            </a>
        </li>

        {{-- Déconnexion --}}
        <li class="mt-8 pt-4 border-t border-blue-500">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 p-3 rounded hover:bg-blue-700">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </li>
    </ul>
</nav>