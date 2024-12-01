<div class="flex flex-col gap-6">
    <div>
        <input 
            wire:model.live.debounce.500ms="search" 
            type="text" 
            placeholder="Search playlists..." 
            class="w-full px-4 py-2 border rounded-md"
        >
    </div>
    <div 
        @playlist-grid-update.window="$wire.$refresh()"
        class="grid grid-cols-2 gap-2 min-h-[592px] content-start"
    >
        @foreach($playlists as $playlist)
            <a href="{{ route('playlist.show', $playlist->id) }}" wire:key="playlist-{{ $playlist->id }}" class="flex flex-row items-center gap-6 p-4 bg-gray-100 rounded-md hover:bg-gray-200">
                <img src="{{ $playlist->thumbnail_url }}" alt="" class="object-cover w-full rounded-md max-w-[80px] aspect-square">
                <p>{{ $playlist->title }}</p>
            </a>
        @endforeach
    </div>
    <div wire:navigate.preserve-scroll>
        {{ $playlists->links() }}
    </div>
</div>