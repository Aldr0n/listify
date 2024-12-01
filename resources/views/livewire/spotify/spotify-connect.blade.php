<div>
    @if($isConnected)
        <button wire:click="disconnect" class="px-4 py-1 text-sm text-white bg-red-500 rounded">
            Disconnect Spotify
        </button>
    @else
        <a href="{{ route('spotify.auth') }}" >
            <button wire:click="disconnect" class="px-4 py-1 text-sm text-white bg-green-500 rounded">
             Connect Spotify
            </button>
        </a>
    @endif
</div>