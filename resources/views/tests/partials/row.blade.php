<tr>
    <td><input type="text" name="tests[{{ $index }}][titre]" class="form-control" required></td>
    <td><input type="date" name="tests[{{ $index }}][date]" class="form-control" required></td>
    <td>
        <select name="tests[{{ $index }}][type]" class="form-select" required>
            <option value="">--Type--</option>
            @foreach($types as $type)
                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <select name="tests[0][annee_id]" id="annee_0" class="form-select" onchange="chargerClasses(this, 0)">
            <option value="">--Année--</option>
            @foreach($annees as $a)
                <option value="{{ $a->id }}">{{ $a->nom }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <select name="tests[{{ $index }}][classe_id]" id="classe_{{ $index }}" class="form-select" onchange="chargerMatieres(this, {{ $index }})" required>
            <option value="">--Classe--</option>
            @foreach($classes as $c)
                <option value="{{ $c->id }}">{{ $c->nom }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <select name="tests[{{ $index }}][matiere_id]" id="matiere_{{ $index }}" class="form-select" required>
            <option value="">--Matière--</option>
        </select>
    </td>
    <td>
        <select name="tests[{{ $index }}][trimestre_id]" class="form-select" required>
            <option value="">--Trimestre--</option>
            @foreach($trimestres as $t)
                <option value="{{ $t->id }}">{{ $t->nom }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="file" name="tests[{{ $index }}][fichier]" class="form-control" onchange="verifierFichier(this, {{ $index }})">
    </td>
    <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">✖</button></td>
</tr>
