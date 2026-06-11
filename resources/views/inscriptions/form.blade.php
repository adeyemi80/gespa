<div class="mb-3">
    <label for="eleve_id" class="form-label">Élève</label>
    <select name="eleve_id" class="form-select" required>
        <option value="">-- Choisir --</option>
        @foreach($eleves as $eleve)
            <option value="{{ $eleve->id }}" @selected(old('eleve_id', $inscription->eleve_id ?? '') == $eleve->id)>
                {{ $eleve->nom }} {{ $eleve->prenom }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="classe_id" class="form-label">Classe</label>
    <select name="classe_id" class="form-select" required>
        <option value="">-- Choisir --</option>
        @foreach($classes as $classe)
            <option value="{{ $classe->id }}" @selected(old('classe_id', $inscription->classe_id ?? '') == $classe->id)>
                {{ $classe->nom }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="annee_id" class="form-label">Année scolaire</label>
    <select name="annee_id" class="form-select" required>
        <option value="">-- Choisir --</option>
        @foreach($annees as $annee)
            <option value="{{ $annee->id }}" @selected(old('annee_id', $inscription->annee_id ?? '') == $annee->id)>
                {{ $annee->nom }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="date_inscription" class="form-label">Date d'inscription</label>
    <input type="date" name="date_inscription" class="form-control"
        value="{{ old('date_inscription', $inscription->date_inscription ?? now()->toDateString()) }}" required>
</div>
