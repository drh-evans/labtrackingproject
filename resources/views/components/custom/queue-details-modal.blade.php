<div>
    <x-tertiary-button x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'queue-details-{{ $queue->id }}')">
        {{ __('details') }}
    </x-tertiary-button>

    <x-modal name="queue-details-{{ $queue->id }}" focusable>
        <h2 class="mt-2 text-xl font-medium text-black">
            {{ $entry->task }}
        </h2>

        <p class="text-md text-black break-words whitespace-normal p-6">
            {{ $entry->information ?? 'No information provided by the user.' }}
        </p>

        <div class="mt-4
            mr-4 mb-4 flex justify-end">
            <x-tertiary-button x-on:click="$dispatch('close')">
                {{ __('close') }}
            </x-tertiary-button>
        </div>
    </x-modal>
</div>
