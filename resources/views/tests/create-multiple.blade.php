@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    <h3>Ajouter plusieurs Epreuves</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tests.multiple.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <table class="table table-bordered" id="testsTable">
            <thead><tr>
                <th>Titre</th><th>Date</th><th>Type</th><th>Année</th><th>Classe</th><th>Matière</th><th>Trimestre</th><th>Fichier</th><th></th>
            </tr></thead>
            <tbody>
                @include('tests.partials.row', ['index' => 0])
            </tbody>
        </table>

        <button type="button" id="addRow" class="btn btn-success mb-3">+ Ajouter une ligne</button>
        <div class="text-end"><button class="btn btn-primary">Enregistrer tous</button></div>
    </form>
</div>

<script>
let index = 1;

document.getElementById('addRow').addEventListener('click', function() {
    // charger partial via fetch (optionnel) ; pour simplicité on clone la première ligne
    const tbody = document.querySelector('#testsTable tbody');
    const firstRow = tbody.querySelector('tr');
    const clone = firstRow.cloneNode(true);

    // mettre à jour les noms et ids dans les inputs/selects
    clone.querySelectorAll('input, select').forEach(function(el){
        if (el.name) {
            el.name = el.name.replace(/\[\d+\]/, '['+index+']');
        }
        if (el.id) {
            // update ids qui suivent le pattern classe_0 matiere_0
            el.id = el.id.replace(/_(\d+)/, '_'+index);
            // reset values
            if (el.type === 'file') el.value = '';
            else if (el.tagName.toLowerCase() === 'select') el.selectedIndex = 0;
            else el.value = '';
        }
    });

    tbody.appendChild(clone);
    index++;
});

// suppression ligne
function removeRow(btn) {
    const tbody = document.querySelector('#testsTable tbody');
    if (tbody.querySelectorAll('tr').length === 1) {
        alert('Au moins une ligne requise.');
        return;
    }
    btn.closest('tr').remove();
}

// charger matieres via AJAX (onchange)
function chargerMatieres(select, idx) {
    const classeId = select.value;
    const matiereSelect = document.getElementById('matiere_' + idx);
    if (!classeId) {
        matiereSelect.innerHTML = '<option value="">-- Matière --</option>';
        return;
    }
    fetch('/classes/' + classeId + '/matieres')
        .then(r => r.json())
        .then(data => {
            matiereSelect.innerHTML = '<option value="">-- Matière --</option>';
            data.forEach(m => {
                const o = document.createElement('option');
                o.value = m.id; o.textContent = m.nom;
                matiereSelect.appendChild(o);
            });
        }).catch(()=> {
            matiereSelect.innerHTML = '<option value="">Erreur</option>';
        });
}

// client-side validation fichier: vérifie abbr matière & classe dans le nom
function verifierFichier(input, idx) {
    const f = input.files[0];
    if (!f) return;
    const filename = f.name.toLowerCase();

    const classeSel = document.getElementById('classe_' + idx);
    const matiereSel = document.getElementById('matiere_' + idx);

    const classeText = classeSel.options[classeSel.selectedIndex]?.text?.toLowerCase() || '';
    const matiereText = matiereSel.options[matiereSel.selectedIndex]?.text?.toLowerCase() || '';

    // mapping identique côté client (abréviations) — adapte si tu changes les maps
    const matMap = {
        'espagnol':'espa','mathématique':'math','mathématiques':'math','anglais':'ang','pct':'pct','physique-chimie-technologie':'pct',
        'svt':'svt','histoire-géographie':'hg','philosophie':'philo','lecture':'lec','communication écrite':'com','français':'fran','francais':'fran'
    };
    const clMap = {'6ème':'6eme','6eme':'6eme','5ème':'5eme','5eme':'5eme','4ème':'4eme','4eme':'4eme','3ème':'3eme','3eme':'3eme','2nde':'2nde','1ère':'1ere','1ere':'1ere','terminale':'tle','tle':'tle'};

    function normalise(s){
        return s ? s.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-z0-9]/gi, '_').toLowerCase() : '';
    }

    const matKey = normalise(matiereText);
    const clKey = normalise(classeText);

    const abMat = matMap[matKey] || '';
    const abCl = clMap[clKey] || '';

    if (abMat && !filename.includes(abMat)) {
        alert(`Le fichier ne contient pas l'abréviation matière attendue : ${abMat}`);
        input.value = '';
        return;
    }
    if (abCl && !filename.includes(abCl)) {
        alert(`Le fichier ne contient pas l'abréviation classe attendue : ${abCl}`);
        input.value = '';
    }
}
</script>
@endsection
