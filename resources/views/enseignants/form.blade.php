<table class="table table-borderless shadow-sm bg-white rounded">
    <tr>
        <td>
            <div class="form-floating mb-3">
                <input type="text" name="matricule" id="matricule" 
                    class="form-control @error('matricule') is-invalid @enderror" 
                    placeholder="Matricule" 
                    value="{{ old('matricule', $enseignant->matricule ?? '') }}" required>
                <label for="matricule"><i class="bi bi-person-badge-fill me-1"></i> Matricule</label>
                @error('matricule')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </td>
        <td>
            <div class="form-floating mb-3">
                <input type="text" name="nom" id="nom" 
                    class="form-control @error('nom') is-invalid @enderror" 
                    placeholder="Nom" 
                    value="{{ old('nom', $enseignant->nom ?? '') }}" required>
                <label for="nom"><i class="bi bi-person-fill me-1"></i> Nom</label>
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </td>
    </tr>

    <tr>
        <td>
            <div class="form-floating mb-3">
                <input type="text" name="prenom" id="prenom" 
                    class="form-control @error('prenom') is-invalid @enderror" 
                    placeholder="Prénom" 
                    value="{{ old('prenom', $enseignant->prenom ?? '') }}" required>
                <label for="prenom"><i class="bi bi-person-lines-fill me-1"></i> Prénom</label>
                @error('prenom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </td>
        <td>
            <div class="form-floating mb-3">
                <input type="date" name="date_naissance" id="date_naissance" 
                    class="form-control @error('date_naissance') is-invalid @enderror" 
                    placeholder="Date de naissance" 
                    value="{{ old('date_naissance', $enseignant->date_naissance ?? '') }}">
                <label for="date_naissance"><i class="bi bi-calendar-event-fill me-1"></i> Date de naissance</label>
                @error('date_naissance')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </td>
    </tr>

    <tr>
        <td>
            <div class="form-floating mb-3">
                <select name="sexe" id="sexe" class="form-select @error('sexe') is-invalid @enderror" required>
                    <option value="">-- Choisir --</option>
                    <option value="M" {{ old('sexe', $enseignant->sexe ?? '') == 'M' ? 'selected' : '' }}>Masculin</option>
                    <option value="F" {{ old('sexe', $enseignant->sexe ?? '') == 'F' ? 'selected' : '' }}>Féminin</option>
                </select>
                <label for="sexe"><i class="bi bi-gender-ambiguous me-1"></i> Sexe</label>
                @error('sexe')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </td>
        <td>
            <div class="form-floating mb-3">
                <input type="text" name="adresse" id="adresse" 
                    class="form-control @error('adresse') is-invalid @enderror" 
                    placeholder="Adresse" 
                    value="{{ old('adresse', $enseignant->adresse ?? '') }}">
                <label for="adresse"><i class="bi bi-geo-alt-fill me-1"></i> Adresse</label>
                @error('adresse')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </td>
    </tr>

    <tr>
        <td>
            <div class="form-floating mb-3">
                <input type="text" name="telephone" id="telephone" 
                    class="form-control @error('telephone') is-invalid @enderror" 
                    placeholder="Téléphone" 
                    value="{{ old('telephone', $enseignant->telephone ?? '') }}">
                <label for="telephone"><i class="bi bi-telephone-fill me-1"></i> Téléphone</label>
                @error('telephone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </td>
        <td>
            <div class="form-floating mb-3">
                <input type="email" name="email" id="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    placeholder="Email" 
                    value="{{ old('email', $enseignant->email ?? '') }}" required>
                <label for="email"><i class="bi bi-envelope-at-fill me-1"></i> Email</label>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </td>
    </tr>
</table>
