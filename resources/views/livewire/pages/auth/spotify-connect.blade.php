<div>
    @if($isConnected)
        <button wire:click="disconnect" class="px-4 py-2 text-white bg-red-500 rounded">
            Disconnect Spotify
        </button>
    @else
        <a href="{{ route('spotify.auth') }}" class="px-4 py-2 text-white bg-green-500 rounded">
            Connect Spotify
        </a>
    @endif
</div>