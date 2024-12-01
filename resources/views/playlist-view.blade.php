<x-app-layout>
    <x-slot name="header">
        <div class="flex gap-4">
            <img src="{{ $playlist->thumbnail_url }}" alt="{{ $playlist->title }}" class="w-24 aspect-square ">
            <div class="flex flex-col justify-start h-full gap-2">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ $playlist->title }}
                </h2>
                <p class="text-sm text-gray-500">
                    {{ $playlist->description }}
                </p>
            </div>
        </div>
    </x-slot>
    <div class="absolute -left-20 w-[120%] -m-10 h-[130%] max-h-full bg-repeat bg-[length:130%] blur-3xl opacity-35" style="background-image: url({{ $playlist->thumbnail_url }})"></div>
    <div class="relative z-20 py-12">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                <div class="">
                    <livewire:library.playlist-view :playlist="$playlist" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
