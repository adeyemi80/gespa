<div>
    <button 
        wire:click="generatePdf" 
        wire:loading.attr="disabled"
        class="btn btn-primary"
        style="background-color: #1dd4e9; border: none; padding: 8px 16px; color: white; border-radius: 4px; cursor: pointer;"
    >
        <span wire:loading.remove wire:target="generatePdf">
            <i class="fas fa-file-pdf"></i> Générer le PDF des bulletins
        </span>
        <span wire:loading wire:target="generatePdf">
            <i class="fas fa-spinner fa-spin"></i> Génération en cours...
        </span>
    </button>
</div>