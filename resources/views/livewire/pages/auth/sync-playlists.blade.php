<div>
    <button wire:click="syncUserPlaylists" class="px-4 py-2 text-white rounded bg-slate-500">
        Sync user playlists
    </button>

    @if($isPolling)
        <div wire:poll.1s="checkSyncStatus" class="mt-4">
            @if($status)
                <p>
                    Imported {{ $status['processed_jobs'] }}/{{ $status['total_jobs'] }} Playlists 
                    ({{ $status['progress'] }}%)
                    @if($status['failed_jobs'] > 0)
                        <span class="text-red-500">
                            ({{ $status['failed_jobs'] }} failed)
                        </span>
                    @endif
                </p>
            @else
                <p>Starting import...</p>
            @endif
        </div>
    @endif
</div>
