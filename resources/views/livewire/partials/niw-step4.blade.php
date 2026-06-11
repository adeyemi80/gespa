<div class="card shadow-sm border-success">
    <div class="card-body text-center py-5">
        <i class="bi bi-check-circle-fill text-success" style="font-size:3rem"></i>
        <h4 class="mt-3 text-success">Import terminé !</h4>
        <p class="text-muted mb-4">
            <strong>{{ $imported_count }}</strong> note(s) importée(s)
            @if($ignored_count > 0)
                &nbsp;|&nbsp;
                <span class="text-warning">
                    <strong>{{ $ignored_count }}</strong> ignorée(s) (hors 0-20)
                </span>
            @endif
        </p>
        <button class="btn btn-primary" wire:click="recommencer">
            <i class="bi bi-arrow-repeat me-1"></i> Nouvel import
        </button>
    </div>
</div>