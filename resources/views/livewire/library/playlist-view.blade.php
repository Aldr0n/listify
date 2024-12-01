<div class="overflow-x-auto">
    <div class="mb-4">
        <input 
            wire:model.live.debounce.300ms="search" 
            type="text" 
            placeholder="Search tracks..." 
            class="w-full px-4 py-2 border rounded-md"
        >
    </div>
    
    <table class="min-w-full table-auto">
        <thead>
            <tr class="border-b">
                <th class="w-16 px-4 py-2 text-left">#</th>
                <th class="px-4 py-2 text-left">Name</th>
                <th class="px-4 py-2 text-left">Album</th>
                <th class="px-4 py-2 text-left">Duration</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tracks as $track)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2 font-mono text-gray-500">{{ $track->track_number }}</td>
                    <td class="px-4 py-2"><a class="hover:underline" href="{{ "https://open.spotify.com/track/" . basename($track->href) }}" target="_blank">{{ $track->name }}</a></td>
                    <td class="px-4 py-2"><a class="hover:underline" href="{{ "https://open.spotify.com/album/" . $track->album['id'] }}" target="_blank">{{ $track->album['name'] }}</a></td>
                    <td class="px-4 py-2">{{ $track->duration }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

