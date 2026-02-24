<div>
    @if ($module->queue->is_open)
        <button wire:click="toggleQueue"
            class="inline-flex items-center px-4 py-2 mr-2 bg-white dark:bg-red-600 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
            Close Queue
        </button>
    @else
        <button wire:click="toggleQueue"
            class="inline-flex items-center px-4 py-2 mr-2 bg-white dark:bg-green-600 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
            Open Queue
        </button>
    @endif
</div>
