<div class="flex flex-col gap-3">
    <div>
        <input 
            wire:model.live.debounce.500ms="search" 
            type="text" 
            placeholder="Search online..." 
            class="w-full px-4 py-2 border-none rounded-md shadow"
        >
        @error('search') <span class="error">{{ $message }}</span> @enderror
    </div>
    <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
        <div 
            @playlist-grid-update.window="$wire.$refresh()"
            class="grid grid-cols-2 gap-2 min-h-[200px] content-start"
        >
            @if(!empty($this->results))
                @foreach($this->results as $playlist)
                    <div  class="relative flex flex-row items-center gap-4 p-4 overflow-hidden bg-gray-100 rounded-md hover:bg-gray-200 group">
                        <div 
                            wire:click.stop="startImport('{{ $playlist->id }}')"
                            wire:loading.class="opacity-50"
                            wire:target="importPlaylist('{{ $playlist->id }}')"
                            class="absolute top-0 right-0 p-2.5 opacity-0 transition-opacity duration-250 group-hover:opacity-50 cursor-pointer"
                        >
                            <svg class="w-[1rem] aspect-square" fill="#000000" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M533.333 560v160H240l.008 453.33 209.066 213.34H1470.93l209.06-213.34L1680 720h-293.33V560h352c55.96 0 101.33 45.368 101.33 101.333v511.997h16c35.35 0 64 28.66 64 64V1856c0 35.35-28.65 64-64 64H64c-35.346 0-64-28.65-64-64v-618.67c0-35.34 28.654-64 64-64h16V661.333C80 605.368 125.369 560 181.333 560h352ZM1040 0v958.86l183.43-183.429 113.14 113.138L960 1265.14 583.431 888.569l113.138-113.138L880 958.86V0h160Z"></path> </g></svg>
                        </div>
                        <img src="{{ $playlist->thumbnail_url }}" alt="" class="object-cover rounded-md max-w-[80px] aspect-square">
                        <div class="flex flex-col flex-1 gap-1">
                            <p>
                                <a href="{{ $playlist->external_urls["spotify"] }}" target="_blank">
                                    {{ $playlist->name }}
                                </a>
                            </p>
                            <p class="text-sm text-gray-500">
                                <span class="hover:underline">
                                    <a href="{{ $playlist->owner["external_urls"]["spotify"] }}" target="_blank">{{ $playlist->owner["display_name"] }}</a>
                                </span> 
                                • 
                                <span>
                                    {{ $playlist->tracks["total"] }} Tracks
                                </span>
                            </p>
                        </div>
                    </div>
                @endforeach
            @endif
            @if(empty($this->results))
                <svg class="absolute w-32 top-[33%] left-[46%] aspect-square opacity-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M21.842 21.134l-6.843-6.843a7.317 7.317 0 1 0-.708.708l6.843 6.843a.5.5 0 1 0 .708-.708zM9.5 15.8a6.3 6.3 0 1 1 6.3-6.3 6.307 6.307 0 0 1-6.3 6.3z"></path><path fill="none" d="M0 0h24v24H0z"></path></g></svg>
            @endif
        </div>
        <div wire:navigate.preserve-scroll>
            {{-- {{ $playlists->links(data: ['scrollTo' => false]) }} --}}
        </div>
    </div>
</div>
