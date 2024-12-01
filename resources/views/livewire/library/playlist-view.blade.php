<div class="overflow-x-auto">
    <img src="{{ $playlist->thumbnail_url }}" alt="" class="object-cover w-full rounded-md max-w-[80px] aspect-square">
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
                    <td class="px-4 py-2 font-mono text-gray-500">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2"><a class="hover:underline" href="{{ "https://open.spotify.com/track/" . basename($track->href) }}" target="_blank">{{ $track->name }}</a></td>
                    <td class="px-4 py-2"><a class="hover:underline" href="{{ "https://open.spotify.com/album/" . $track->album['id'] }}" target="_blank">{{ $track->album['name'] }}</a></td>
                    <td class="px-4 py-2">{{ $track->duration }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

