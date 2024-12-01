<div>
    <button 
        wire:click="syncUserPlaylists" 
        class="flex flex-row gap-1 px-4 py-1 text-sm text-white rounded {{ $isPolling ? 'bg-slate-400 cursor-not-allowed' : 'bg-slate-500' }}" 
        wire:loading.attr="disabled"
        {{ $isPolling ? 'disabled' : '' }}
    >
        {{ $isPolling ? 'Syncing...' : 'Sync user playlists' }}
        @if($isPolling)
        <span wire:poll.1s="checkSyncStatus" class="flex flex-col">
            @if($status)
                {{ $status['processed_jobs'] }}/{{ $status['total_jobs'] }} ({{ $status['progress'] }}%)
            @endif
        </span>
        @endif
    </button>

    {{-- @if($isPolling)
        <span wire:poll.1s="checkSyncStatus" class="flex flex-col">
            @if($status)
                <span class="text-sm">
                    Imported {{ $status['processed_jobs'] }}/{{ $status['total_jobs'] }} Playlists 
                    ({{ $status['progress'] }}%)
                    @if($status['failed_jobs'] > 0)
                        <span class="text-red-500">
                            ({{ $status['failed_jobs'] }} failed)
                        </span>
                    @endif
                </span>
            @endif
        </span>
    @endif --}}
</div>
