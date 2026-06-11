<div class="min-h-screen bg-gray-50 py-8 px-4">
    <div class="max-w-3xl mx-auto">

        {{-- ── Titre ── --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Annulation de passage</h1>
            <p class="text-sm text-gray-500 mt-1">Suppression d'inscriptions par classe et par année</p>
        </div>

        {{-- ── Message succès ── --}}
        @if ($successMessage)
            <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm font-medium">{{ $successMessage }}</span>
                <button wire:click="$set('successMessage', null)" class="ml-auto text-green-600 hover:text-green-800">✕</button>
            </div>
        @endif

        {{-- ── Message erreur ── --}}
        @if ($errorMessage)
            <div class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm font-medium">{{ $errorMessage }}</span>
                <button wire:click="$set('errorMessage', null)" class="ml-auto text-red-600 hover:text-red-800">✕</button>
            </div>
        @endif

        {{-- ══════════════════════════════════════
             Indicateur d'étapes
        ═══════════════════════════════════════ --}}
        <div class="mb-8">
            <div class="flex items-center justify-between relative">
                {{-- Ligne de connexion --}}
                <div class="absolute top-4 left-0 right-0 h-0.5 bg-gray-200 z-0"></div>
                <div class="absolute top-4 left-0 h-0.5 bg-red-500 z-0 transition-all duration-500"
                     style="width: {{ ($etape - 1) * 33.33 }}%"></div>

                @foreach ([
                    1 => 'Année',
                    2 => 'Classe',
                    3 => 'Élèves',
                    4 => 'Confirmation',
                ] as $num => $label)
                    <div class="flex flex-col items-center z-10">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300
                            {{ $etape > $num ? 'bg-red-600 border-red-600 text-white' : ($etape == $num ? 'bg-white border-red-500 text-red-600' : 'bg-white border-gray-300 text-gray-400') }}">
                            @if($etape > $num)
                                ✓
                            @else
                                {{ $num }}
                            @endif
                        </div>
                        <span class="mt-1 text-xs font-medium
                            {{ $etape >= $num ? 'text-red-600' : 'text-gray-400' }}">
                            {{ $label }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ══════════════════════════════════════
             CARTE PRINCIPALE
        ═══════════════════════════════════════ --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- ── ÉTAPE 1 : Année d'accueil ── --}}
            @if ($etape === 1 || $etape > 1)
            <div class="p-6 {{ $etape > 1 ? 'border-b border-gray-100' : '' }}">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <span class="inline-flex items-center gap-2">
                        <span class="w-5 h-5 bg-red-100 text-red-600 rounded-full text-xs flex items-center justify-center font-bold">1</span>
                        Année d'accueil
                    </span>
                </label>

                @if ($etape === 1)
                    <select wire:model="anneeId"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white">
                        <option value="">-- Sélectionner une année --</option>
                        @foreach ($annees as $annee)
                            <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                        @endforeach
                    </select>
                @else
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-800 bg-red-50 px-3 py-1.5 rounded-lg border border-red-100">
                            📅 {{ $anneeLibelle }}
                        </span>
                        <button wire:click="reinitialiser" class="text-xs text-gray-400 hover:text-red-500 underline">Modifier</button>
                    </div>
                @endif
            </div>
            @endif

            {{-- ── ÉTAPE 2 : Cycle / Classe ── --}}
            @if ($etape >= 2)
            <div class="p-6 {{ $etape > 2 ? 'border-b border-gray-100 bg-gray-50' : '' }}">
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    <span class="inline-flex items-center gap-2">
                        <span class="w-5 h-5 bg-red-100 text-red-600 rounded-full text-xs flex items-center justify-center font-bold">2</span>
                        Cycle et Classe
                    </span>
                </label>

                @if ($etape === 2)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Cycle --}}
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Cycle</label>
                            <select wire:model="cycleId"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 bg-white">
                                <option value="">-- Sélectionner --</option>
                                @foreach ($cycles as $cycle)
                                    <option value="{{ $cycle->id }}">{{ $cycle->libelle }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Classe --}}
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Classe</label>
                            <select wire:model="classeId"
                                    @if(!$cycleId) disabled @endif
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 bg-white disabled:bg-gray-100 disabled:cursor-not-allowed">
                                <option value="">-- Sélectionner --</option>
                                @foreach ($classes as $classe)
                                    <option value="{{ $classe->id }}">{{ $classe->libelle }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @else
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-800 bg-red-50 px-3 py-1.5 rounded-lg border border-red-100">
                            🏫 {{ $classeLibelle }}
                        </span>
                    </div>
                @endif
            </div>
            @endif

            {{-- ── ÉTAPE 3 : Liste des élèves ── --}}
            @if ($etape >= 3 && !$showConfirmation)
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <label class="text-sm font-semibold text-gray-700">
                        <span class="inline-flex items-center gap-2">
                            <span class="w-5 h-5 bg-red-100 text-red-600 rounded-full text-xs flex items-center justify-center font-bold">3</span>
                            Élèves inscrits
                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full">{{ $eleves->count() }} élève(s)</span>
                        </span>
                    </label>
                    @if (count($elevesSelectionnes) > 0)
                        <span class="text-xs text-red-600 font-medium">
                            {{ count($elevesSelectionnes) }} sélectionné(s)
                        </span>
                    @endif
                </div>

                @if ($eleves->isEmpty())
                    <div class="text-center py-8 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="text-sm">Aucun élève inscrit dans cette classe pour cette année.</p>
                    </div>
                @else
                    {{-- Sélectionner tous --}}
                    <div class="flex items-center gap-3 mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <input type="checkbox"
                               wire:model="tousSelectionnes"
                               id="tous"
                               class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <label for="tous" class="text-sm font-semibold text-gray-700 cursor-pointer select-none">
                            Sélectionner tous les élèves
                        </label>
                    </div>

                    {{-- Liste élèves --}}
                    <div class="divide-y divide-gray-100 border border-gray-200 rounded-lg overflow-hidden max-h-72 overflow-y-auto">
                        @foreach ($eleves as $eleve)
                            <label for="eleve_{{ $eleve->id }}"
                                   class="flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-red-50 transition-colors
                                          {{ in_array((string)$eleve->id, $elevesSelectionnes) ? 'bg-red-50' : 'bg-white' }}">
                                <input type="checkbox"
                                       wire:model="elevesSelectionnes"
                                       id="eleve_{{ $eleve->id }}"
                                       value="{{ $eleve->id }}"
                                       class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    {{-- Avatar initiales --}}
                                    <div class="w-8 h-8 rounded-full bg-red-100 text-red-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        {{ strtoupper(substr($eleve->nom, 0, 1)) }}{{ strtoupper(substr($eleve->prenom ?? '', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate">
                                            {{ $eleve->nom }} {{ $eleve->prenom }}
                                        </p>
                                        @if($eleve->matricule)
                                            <p class="text-xs text-gray-400">Mat. {{ $eleve->matricule }}</p>
                                        @endif
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    {{-- Bouton valider --}}
                    <div class="mt-5 flex justify-end">
                        <button wire:click="passerEtape4"
                                @if(empty($elevesSelectionnes)) disabled @endif
                                class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Valider l'annulation
                            @if(count($elevesSelectionnes) > 0)
                                ({{ count($elevesSelectionnes) }})
                            @endif
                        </button>
                    </div>
                @endif
            </div>
            @endif

            {{-- ══════════════════════════════════════
                 ÉTAPE 4 : Confirmation (modal interne)
            ═══════════════════════════════════════ --}}
            @if ($showConfirmation)
            <div class="p-6">
                <div class="border-2 border-red-200 rounded-xl bg-red-50 p-6">
                    {{-- Icône avertissement --}}
                    <div class="flex justify-center mb-4">
                        <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                    </div>

                    <h3 class="text-center text-base font-bold text-red-800 mb-1">Attention !</h3>
                    <p class="text-center text-sm text-red-700 font-medium mb-4">
                        Ces inscriptions seront <span class="underline font-bold">définitivement supprimées</span>.
                        Cette action est irréversible.
                    </p>

                    {{-- Récapitulatif --}}
                    <div class="bg-white rounded-lg border border-red-200 p-4 mb-5">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Récapitulatif</p>
                        <div class="space-y-1 text-sm text-gray-700">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Année :</span>
                                <span class="font-medium">{{ $anneeLibelle }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Classe :</span>
                                <span class="font-medium">{{ $classeLibelle }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Élèves concernés :</span>
                                <span class="font-bold text-red-700">{{ count($elevesSelectionnes) }}</span>
                            </div>
                        </div>

                        {{-- Liste noms --}}
                        <div class="mt-3 pt-3 border-t border-gray-100 max-h-32 overflow-y-auto">
                            @foreach ($elevesSelectionnesDetails as $eleve)
                                <p class="text-xs text-gray-600 py-0.5">• {{ $eleve->nom }} {{ $eleve->prenom }}</p>
                            @endforeach
                        </div>
                    </div>

                    {{-- Boutons OUI / NON --}}
                    <div class="flex gap-3">
                        <button wire:click="annulerConfirmation"
                                class="flex-1 bg-white hover:bg-gray-50 border-2 border-gray-300 text-gray-700 text-sm font-bold py-2.5 rounded-lg transition-colors">
                            NON — Retour
                        </button>
                        <button wire:click="confirmerAnnulation"
                                wire:loading.attr="disabled"
                                class="flex-1 bg-red-600 hover:bg-red-700 text-white text-sm font-bold py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="confirmerAnnulation">OUI — Supprimer</span>
                            <span wire:loading wire:target="confirmerAnnulation">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                Suppression...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            @endif

        </div>{{-- fin carte --}}

        {{-- Lien réinitialiser --}}
        @if ($etape > 1 && !$showConfirmation)
        <div class="mt-4 text-center">
            <button wire:click="reinitialiser" class="text-xs text-gray-400 hover:text-gray-600 underline">
                ↺ Recommencer depuis le début
            </button>
        </div>
        @endif

    </div>
</div>