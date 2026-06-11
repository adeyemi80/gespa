<div class="row mb-3">

    <div class="col-md-4">
        <select wire:model="anneeId" class="form-control">
            <option value="">Année</option>
            @foreach($annees as $annee)
                <option value="{{ $annee->id }}">{{ $annee->nom }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <select wire:model="classeId" class="form-control">
            <option value="">Classe</option>
            @foreach($classes as $classe)
                <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <select wire:model="trimestreId" class="form-control">
            <option value="">Trimestre</option>
            @foreach($trimestres as $trimestre)
                <option value="{{ $trimestre->id }}">{{ $trimestre->nom }}</option>
            @endforeach
        </select>
    </div>

</div>