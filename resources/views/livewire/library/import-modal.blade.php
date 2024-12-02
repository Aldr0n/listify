<div>
    <!-- Button trigger -->
    <button wire:click="openModal" class="px-4 py-2 text-sm font-normal text-black border rounded-md border-slate-300 hover:bg-gray-100">
        Import external playlist
    </button>

    <!-- Modal -->
    <div
        x-data="{ open: @entangle('isOpen') }"
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true"
    >
        <div class="flex items-end justify-center min-h-screen p-4 text-center sm:block sm:p-0">
            <!-- Modal overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-200 bg-opacity-75"></div>

            <!-- Modal content -->
            <div class="inline-block w-[600px] absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 overflow-hidden p-4 text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl">
                <div class="bg-white">
                    <div class="">
                        <!-- Your modal content goes here -->
                        <div class="text-center sm:text-left">
                            <p >
                                <input 
                                    wire:model.live.debounce.250ms="checkImportUrl" 
                                    type="text" 
                                    placeholder="Enter playlist url ..." 
                                    class="w-full px-4 py-2 font-normal border rounded-md"
                                >
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="justify-between sm:flex sm:flex-row-reverse">
                    <button type="button" @click="open = false" class="w-32 px-2 py-0.5 mt-3 font-light text-sm text-white bg-slate-500 border border-transparent rounded-md shadow-sm hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-transparent">
                        Import
                    </button>
                    <button type="button" @click="open = false" class="w-32 px-2 py-0.5 mt-3 font-light text-sm text-gray-400 border border-gray-300 rounded-md shadow-sm hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Close
                    </button>
                    <!-- Additional buttons -->
                </div>
            </div>
        </div>
    </div>
</div>