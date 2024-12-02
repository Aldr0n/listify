<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center justify-start gap-4">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Playlist Search') }}
            </h2>
            <livewire:library.playlist-import />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <livewire:library.playlist-search />
        </div>
    </div>
</x-app-layout>
