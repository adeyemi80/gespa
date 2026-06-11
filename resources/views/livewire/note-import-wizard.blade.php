<div>
    {{-- PROGRESS BAR --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        @foreach ([1 => 'Paramètres', 2 => 'Téléversement', 3 => 'Aperçu', 4 => 'Terminé'] as $num => $label)
            <div class="d-flex align-items-center gap-1">
                <span class="badge rounded-pill {{ $step >= $num ? 'bg-primary' : 'bg-secondary' }}">
                    {{ $num }}
                </span>
                <small class="{{ $step >= $num ? 'text-primary fw-bold' : 'text-muted' }}">
                    {{ $label }}
                </small>
            </div>
            @if($num < 4)
                <div class="flex-grow-1 border-top {{ $step > $num ? 'border-primary' : 'border-secondary' }}"
                     style="max-width:40px"></div>
            @endif
        @endforeach
    </div>

    {{-- MESSAGES --}}
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- STEPS --}}
    @if($step === 1) @include('livewire.partials.niw-step1', ['cycles' => $cycles, 'annees' => $annees]) @endif
    @if($step === 2) @include('livewire.partials.niw-step2') @endif
    @if($step === 3) @include('livewire.partials.niw-step3') @endif
    @if($step === 4) @include('livewire.partials.niw-step4') @endif
</div>