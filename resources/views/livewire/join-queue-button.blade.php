<div>
    <div>
        @if ($inQueue)
            <button wire:key="leave-button" wire:click="leaveQueue"
                class="px-3 py-2 font-medium bg-red-500 hover:bg-red-600 text-sm uppercase font-semibold text-white shadow-md rounded-lg">
                Leave
            </button>
        @else
            <button wire:key="join-button" x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'join-queue-{{ $queue->id }}')"
                class="px-3 py-2 bg-green-500 hover:bg-green-600 text-white text-sm uppercase font-semibold shadow-md rounded-lg">
                Join
            </button>
        @endif
    </div>
</div>
