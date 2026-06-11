<div class="mb-3">
    <label for="inscription_id" class="form-label">Élève / Classe / Année</label>
    <select name="inscription_id" id="inscription_id" class="form-select" required>
        <option value="">-- Sélectionner --</option>
        @foreach($inscriptions as $inscription)
            <option value="{{ $inscription->id }}"
                {{ (old('inscription_id', $note->inscription_id ?? '') == $inscription->id) ? 'selected' : '' }}>
                {{ $inscription->eleve->nom }} {{ $inscription->eleve->prenom }} |
                {{ $inscription->classe->nom ?? '-' }} |
                {{ $inscription->annee->nom ?? '-' }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="matiere_id" class="form-label">Matière</label>
    <select name="matiere_id" id="matiere_id" class="form-select" required>
        <option value="">-- Sélectionner --</option>
        @foreach($matieres as $matiere)
            <option value="{{ $matiere->id }}"
                {{ (old('matiere_id', $note->matiere_id ?? '') == $matiere->id) ? 'selected' : '' }}>
                {{ $matiere->nom }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="trimestre_id" class="form-label">Trimestre</label>
    <select name="trimestre_id" id="trimestre_id" class="form-select" required>
        <option value="">-- Sélectionner --</option>
        @foreach($trimestres as $t)
            <option value="{{ $t->id }}"
                {{ (old('trimestre_id', $note->trimestre_id ?? '') == $t->id) ? 'selected' : '' }}>
                {{ $t->nom }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="annee_id" class="form-label">Année scolaire</label>
    <select name="annee_id" id="annee_id" class="form-select" required>
        <option value="">-- Sélectionner --</option>
        @foreach($annees as $a)
            <option value="{{ $a->id }}"
                {{ (old('annee_id', $note->annee_id ?? '') == $a->id) ? 'selected' : '' }}>
                {{ $a->nom }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="moyenne_interro" class="form-label">Moyenne d'Interrogation</label>
    <input type="number" name="moyenne_interro" id="moyenne_interro" step="0.01" min="0" max="20" 
           class="form-control" value="{{ old('moyenne_interro', $note->moyenne_interro ?? '') }}">
</div>

<div class="mb-3">
    <label for="devoir1" class="form-label">Devoir 1</label>
    <input type="number" name="devoir1" id="devoir1" step="0.01" min="0" max="20" 
           class="form-control" value="{{ old('devoir1', $note->devoir1 ?? '') }}">
</div>

<div class="mb-3">
    <label for="devoir2" class="form-label">Devoir 2</label>
    <input type="number" name="devoir2" id="devoir2" step="0.01" min="0" max="20" 
           class="form-control" value="{{ old('devoir2', $note->devoir2 ?? '') }}">
</div>
