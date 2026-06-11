@extends('tableau.neutre')
@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    <h3 class="mb-4 text-primary fw-bold">
        🔎 Prévisualisation des notes
        <span class="badge bg-success ms-2">{{ count(array_filter($preview ?? [], fn($n) => ($n['valid'] ?? true))) }} notes VALIDES</span>
        @if(isset($invalid_notes_count) && $invalid_notes_count > 0)
            <span class="badge bg-danger ms-1">{{ $invalid_notes_count }} notes INVAlIDES</span>
        @endif
    </h3>

    {{-- Messages flash --}}
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(empty($preview ?? []))
        <div class="alert alert-warning">
            <h5>⚠️ Aucune note trouvée</h5>
            <p>Vérifiez le format Excel et les données.</p>
        </div>
        <div class="text-end">
            <a href="{{ route('notes.import.index') }}" class="btn btn-secondary">← Réessayer</a>
        </div>
    @else
        <form action="{{ route('notes.import.store') }}" method="POST">
            @csrf
            
            {{-- Inputs cachés --}}
            @isset($inputs)
                @foreach($inputs as $key => $value)
                    @if(is_scalar($value))
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
            @endisset

            {{-- Légende --}}
            <div class="alert alert-info border-0 bg-info-subtle">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="bi bi-info-circle-fill"></i>
                    </div>
                    <div class="col">
                        <small>
                            <span class="badge bg-success me-1">✓</span> Valide (0-20) 
                            <span class="badge bg-danger me-1">✗</span> Invalide (hors 0-20)
                            <br><strong>Seules les notes VALIDES seront importées</strong>
                        </small>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-gradient d-flex justify-content-between align-items-center">
                    <div class="text-white fw-bold">
                        📘 {{ $preview[0]['matiere'] ?? 'Notes' }}
                    </div>
                    <div>
                        <span class="badge bg-light text-dark me-2">{{ count($preview) }} total</span>
                        <span class="badge bg-success">{{ count(array_filter($preview ?? [], fn($n) => ($n['valid'] ?? true))) }} valides</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="22%">Élève</th>
                                    <th width="15%">Type</th>
                                    <th width="12%" class="text-center">Note</th>
                                    <th width="16%">Statut</th>
                                    <th width="15%">Inscription</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($preview as $i => $item)
                                    @php
                                        $isValid = ($item['valid'] ?? true);
                                        $noteClass = $isValid ? 'text-success bg-success-subtle' : 'text-danger bg-danger-subtle';
                                        $icon = $isValid ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
                                        $statusText = $isValid ? 'Valide' : 'Invalide (hors 0-20)';
                                    @endphp
                                    <tr class="align-middle {{ !$isValid ? 'table-danger' : '' }}">
                                        <td class="text-center fw-bold">{{ $i+1 }}</td>
                                        <td>
                                            <small class="text-muted d-block">{{ $item['matricule'] ?? '' }}</small>
                                            <strong>{{ $item['eleve'] ?? 'N/A' }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $item['type'] ?? '')) }}</span>
                                        </td>
                                        <td class="text-center fw-bold fs-5 {{ $noteClass }}">
                                            {{ number_format($item['note'] ?? 0, 1) }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $noteClass }} p-2">
                                                <i class="{{ $icon }} me-1"></i>
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $item['inscription_id'] ?? '' }}</span>
                                        </td>
                                    </tr>
                                    
                                    {{-- Champs cachés : UNIQUEMENT si valide --}}
                                    @if($isValid)
                                        @php $safeNote = $item['note'] ?? ''; @endphp
                                        <input type="hidden" name="data[{{ $i }}][inscription_id]" value="{{ $item['inscription_id'] ?? '' }}">
                                        <input type="hidden" name="data[{{ $i }}][{{ $item['type'] ?? '' }}]" value="{{ is_numeric($safeNote) ? $safeNote : '' }}">
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Boutons --}}
            <div class="d-flex justify-content-between align-items-center mt-4 p-3 bg-light rounded border">
                <div class="text-muted small">
                    <i class="bi bi-info-circle"></i> 
                    {{ count(array_filter($preview ?? [], fn($n) => ($n['valid'] ?? true))) }} notes valides / 
                    {{ count($preview) }} totales à importer
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('notes.import.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-success btn-lg px-4 {{ empty(array_filter($preview ?? [], fn($n) => ($n['valid'] ?? true))) ? 'disabled' : '' }}" 
                            {{ empty(array_filter($preview ?? [], fn($n) => ($n['valid'] ?? true))) ? 'disabled' : '' }}>
                        <i class="bi bi-check-lg"></i> 
                        Importer {{ count(array_filter($preview ?? [], fn($n) => ($n['valid'] ?? true))) }} notes valides
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection
