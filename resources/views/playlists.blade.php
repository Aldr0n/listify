<x-app-layout>
    <x-slot name="header">
        <h2 class="flex flex-row items-center justify-between gap-4 text-xl font-semibold leading-tight text-gray-800">
            {{ __('My Playlists') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <livewire:library.playlist-list />
        </div>
    </div>
</x-app-layout>
