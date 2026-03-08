<div>
    @if ($inQueue)
        <button wire:key="leave-button" wire:click="leaveQueue" class="px-4 py-2 bg-red-500 text-white rounded">
            Leave
        </button>
    @else
        <button wire:key="join-button" wire:click="joinQueue" class="px-4 py-2 bg-green-500 text-white rounded">
            Join
        </button>
    @endif
</div>
