@extends('tableau.neutre')

@section('title', 'Dashboard Parent')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-4">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>👨‍👩‍👧‍👦 INFORMATIONS AUX PARENTS</h2>
        <a href="{{ route('parens.dashboard') }}" class="btn btn-primary">
            🔄 Actualiser
        </a>
    </div>

    {{-- 🔔 Notifications --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h5>🔔 Notifications</h5>
                    <h2>{{ $notificationsCount }}</h2>
                    <small>non lues</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h5>📚 Enfants</h5>
                    <h2>{{ $inscriptions->count() }}</h2>
                    <small>inscrits</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h5>📨 Messages</h5>
                    <h2>{{ $messages->count() }}</h2>
                    <small>récents</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Avertissement si aucun enfant --}}
    @if($inscriptions->isEmpty())
        <div class="alert alert-warning text-center py-5">
            <i class="bi bi-exclamation-triangle fs-1 mb-3 d-block"></i>
            👶 Aucun enfant inscrit trouvé pour ce compte parent.<br>
            <small class="text-muted">Contactez l'administration</small>
        </div>
    @else

        {{-- Sélecteur de trimestre --}}
        <div class="mb-4">
            <form method="GET" class="d-flex align-items-center gap-3">
                <label for="trimestre" class="form-label mb-0 fw-semibold">Sélectionner un trimestre :</label>
                <select name="trimestre" id="trimestre" class="form-select w-auto" onchange="this.form.submit()">
                    @foreach([1,2,3] as $t)
                        <option value="{{ $t }}" {{ $trimestre == $t ? 'selected' : '' }}>
                            Trimestre {{ $t }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- Notes par enfant et par matière --}}
        @foreach($inscriptions as $inscription)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5>📊 Notes de {{ $inscription->eleve->nom }} {{ $inscription->eleve->prenom }} - {{ $inscription->classe->nom ?? 'N/A' }}</h5>
                    <span class="badge bg-secondary">Trimestre {{ $trimestre }}</span>
                </div>
                <div class="card-body">
                    @php
                        $eleveNotes = $notes->get($inscription->id, collect())->filter(function($note) use($trimestre) {
                            return $note->trimestre_id == $trimestre; // utilisation de trimestre_id
                        });
                    @endphp

                    @if($eleveNotes->isEmpty())
                        <p class="text-muted">Pas de notes disponibles pour ce trimestre.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Matière</th>
                                        <th>Interrogation1</th>
                                         <th>Interrogation2</th>
                                          <th>Interrogation3</th>
                                        <th>Devoir 1</th>
                                        <th>Devoir 2</th>
                                        <th>Moyenne</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($eleveNotes as $note)
                                        <tr>
                                            <td>{{ $note->matiere->nom }}</td>
                                            <td>{{ $note->interrogation1 ?? '-' }}</td>
                                             <td>{{ $note->interrogation2 ?? '-' }}</td>
                                              <td>{{ $note->interrogation3 ?? '-' }}</td>
                                            <td>{{ $note->devoir1 ?? '-' }}</td>
                                            <td>{{ $note->devoir2 ?? '-' }}</td>
                                            <td>
                                                {{ number_format(
                                                    (($note->interro ?? 0) + ($note->devoir1 ?? 0) + ($note->devoir2 ?? 0))/3, 2
                                                ) }}/20
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        {{-- Comportements --}}
        @if($conduites->isNotEmpty())
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5>📋 Comportements récents</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Élève</th>
                                <th>Type</th>
                                <th>Niveau</th>
                                <th>Observation</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($conduites as $c)
                                <tr>
                                    <td>{{ $c->inscription->eleve->nom }} {{ $c->inscription->eleve->prenom }}</td>
                                    <td><span class="badge bg-info">{{ ucfirst($c->type) }}</span></td>
                                    <td>
                                        @if($c->niveau == 'excellent')
                                            <span class="badge bg-success">⭐ Excellent</span>
                                        @elseif($c->niveau == 'correct')
                                            <span class="badge bg-warning">✅ Correct</span>
                                        @else
                                            <span class="badge bg-danger">⚠️ À améliorer</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($c->observation, 40) }}</td>
                                    <td>{{ $c->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- Messages --}}
        @if($messages->isNotEmpty())
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5>💬 Messages récents</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Élève</th>
                                <th>Objet</th>
                                <th>Message</th>
                                <th>Type</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($messages as $msg)
                                <tr @if(!$msg->lu) class="table-warning" @endif>
                                    <td>{{ $msg->eleve->nom }} {{ $msg->eleve->prenom }}</td> {{-- correction ici --}}
                                    <td><strong>{{ $msg->objet }}</strong></td>
                                    <td>{{ Str::limit($msg->message, 50) }}</td>
                                    <td>
                                        <span class="badge {{ $msg->type == 'important' ? 'bg-danger' : 'bg-secondary' }}">
                                            {{ ucfirst($msg->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $msg->created_at->format('d/m H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    @endif
</div>
@endsection
