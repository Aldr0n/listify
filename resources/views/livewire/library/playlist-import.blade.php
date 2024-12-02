<div>
    @if($isImporting)
        <div wire:poll.1s="updateImportStatus" class="text-sm">
            <div class="text-sm">
                {{ $importStatus }}
            </div>
        </div>
    @endif
</div>
