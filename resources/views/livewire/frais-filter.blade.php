<div class="min-h-screen bg-slate-50 p-6 font-sans">

    {{-- ══════════════════════════════════════════
         EN-TÊTE
    ══════════════════════════════════════════ --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Suivi des Frais Scolaires</h1>
        <p class="text-sm text-slate-500 mt-1">Filtrez par année, classe et élève pour consulter le détail des frais.</p>
    </div>

    {{-- ══════════════════════════════════════════
         FILTRES EN CASCADE
    ══════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- Année --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                    Année scolaire
                </label>
                <div class="relative">
                    <select
                        wire:model.live="annee_id"
                        class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 pr-9 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                        <option value="">— Choisir une année —</option>
                        @foreach($annees as $annee)
                            <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Classe --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                    Classe
                </label>
                <div class="relative">
                    <select
                        wire:model.live="classe_id"
                        @disabled(!$annee_id)
                        class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 pr-9 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                        <option value="">— Choisir une classe —</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Élève --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                    Élève
                </label>
                <div class="relative">
                    <select
                        wire:model.live="eleve_id"
                        @disabled(!$classe_id)
                        class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 pr-9 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                        <option value="">— Choisir un élève —</option>
                        @foreach($eleves as $eleve)
                            <option value="{{ $eleve->id }}">{{ $eleve->nom }} {{ $eleve->prenom }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Indicateur de chargement --}}
        <div wire:loading class="flex items-center gap-2 mt-3 text-indigo-600 text-xs">
            <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
            </svg>
            Chargement…
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         CARTES TOTAUX (visibles si résultats)
    ══════════════════════════════════════════ --}}
    @if(count($frais) > 0)
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total dû</p>
            <p class="text-2xl font-bold text-slate-800">
                {{ number_format($totaux['montant_total'], 0, ',', ' ') }}
                <span class="text-sm font-normal text-slate-400">FCFA</span>
            </p>
        </div>

        <div class="bg-emerald-50 rounded-2xl border border-emerald-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-1">Déjà payé</p>
            <p class="text-2xl font-bold text-emerald-700">
                {{ number_format($totaux['montant_paye'], 0, ',', ' ') }}
                <span class="text-sm font-normal text-emerald-400">FCFA</span>
            </p>
            @if($totaux['montant_total'] > 0)
                <div class="mt-2 h-1.5 bg-emerald-200 rounded-full overflow-hidden">
                    <div
                        class="h-full bg-emerald-500 rounded-full transition-all duration-500"
                        style="width: {{ min(100, round($totaux['montant_paye'] / $totaux['montant_total'] * 100)) }}%"
                    ></div>
                </div>
                <p class="text-xs text-emerald-500 mt-1">
                    {{ round($totaux['montant_paye'] / $totaux['montant_total'] * 100) }}% réglé
                </p>
            @endif
        </div>

        <div class="bg-rose-50 rounded-2xl border border-rose-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-rose-600 uppercase tracking-wider mb-1">Reste à payer</p>
            <p class="text-2xl font-bold text-rose-700">
                {{ number_format($totaux['reste'], 0, ',', ' ') }}
                <span class="text-sm font-normal text-rose-400">FCFA</span>
            </p>
        </div>

    </div>

    {{-- ══════════════════════════════════════════
         TABLEAU DÉTAIL
    ══════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-slate-700">Détail des frais</h2>
            <span class="text-xs bg-slate-100 text-slate-500 px-2.5 py-1 rounded-full">
                {{ count($frais) }} ligne{{ count($frais) > 1 ? 's' : '' }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="px-5 py-3 text-left font-semibold">Frais</th>
                        <th class="px-5 py-3 text-right font-semibold">Montant dû</th>
                        <th class="px-5 py-3 text-right font-semibold">Payé</th>
                        <th class="px-5 py-3 text-right font-semibold">Reste</th>
                        <th class="px-5 py-3 text-center font-semibold">Statut</th>
                        <th class="px-5 py-3 text-left font-semibold w-36">Progression</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($frais as $item)
                    @php
                        $pct = $item->montant_frais > 0
                            ? min(100, round($item->montant_paye / $item->montant_frais * 100))
                            : 100;

                        $statutConfig = match($item->statut) {
                            'soldé'              => ['bg-emerald-100 text-emerald-700', 'Soldé'],
                            'partiellement_payé' => ['bg-amber-100 text-amber-700',   'Partiel'],
                            'non_payé'           => ['bg-rose-100 text-rose-700',      'Non payé'],
                            default              => ['bg-slate-100 text-slate-600',    $item->statut],
                        };

                        $barColor = match($item->statut) {
                            'soldé'              => 'bg-emerald-500',
                            'partiellement_payé' => 'bg-amber-400',
                            default              => 'bg-rose-400',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-slate-800 capitalize">{{ $item->nom_frais }}</p>
                            @if($item->est_arriere)
                                <span class="inline-flex items-center gap-1 text-xs text-orange-600 mt-0.5">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Arriéré
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right font-mono text-slate-700">
                            {{ number_format($item->montant_frais, 0, ',', ' ') }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-mono text-emerald-700">
                            {{ number_format($item->montant_paye, 0, ',', ' ') }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-mono text-rose-700">
                            {{ number_format($item->reste, 0, ',', ' ') }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statutConfig[0] }}">
                                {{ $statutConfig[1] }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div
                                        class="h-full {{ $barColor }} rounded-full transition-all duration-500"
                                        style="width: {{ $pct }}%"
                                    ></div>
                                </div>
                                <span class="text-xs text-slate-400 w-8 text-right">{{ $pct }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

                {{-- Ligne de total --}}
                <tfoot>
                    <tr class="bg-slate-50 font-semibold text-slate-700 border-t border-slate-200">
                        <td class="px-5 py-3.5 text-xs uppercase tracking-wider text-slate-500">TOTAL</td>
                        <td class="px-5 py-3.5 text-right font-mono">
                            {{ number_format($totaux['montant_total'], 0, ',', ' ') }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-mono text-emerald-700">
                            {{ number_format($totaux['montant_paye'], 0, ',', ' ') }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-mono text-rose-700">
                            {{ number_format($totaux['reste'], 0, ',', ' ') }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @elseif($eleve_id)
    {{-- Aucun frais trouvé --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-slate-500 font-medium">Aucun frais enregistré pour cet élève.</p>
        <p class="text-slate-400 text-sm mt-1">Vérifiez l'inscription ou l'affectation des frais pour cette année.</p>
    </div>

    @elseif(!$eleve_id && $annee_id)
    {{-- État intermédiaire --}}
    <div class="bg-white rounded-2xl border border-dashed border-slate-200 p-12 text-center">
        <p class="text-slate-400 text-sm">Sélectionnez {{ !$classe_id ? 'une classe puis ' : '' }}un élève pour afficher ses frais.</p>
    </div>

    @else
    {{-- État initial --}}
    <div class="bg-white rounded-2xl border border-dashed border-slate-200 p-12 text-center">
        <svg class="w-10 h-10 text-slate-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
        </svg>
        <p class="text-slate-400 text-sm">Commencez par sélectionner une année scolaire.</p>
    </div>
    @endif

</div>