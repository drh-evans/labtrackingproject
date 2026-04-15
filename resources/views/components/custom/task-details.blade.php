<div>
    <x-custom.details-button x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-task-details-{{ $labTask->id }}')">
        {{ _('Details') }}

    </x-custom.details-button>


    <x-modal name="confirm-task-details-{{ $labTask->id }}" focusable>
        <h2 class="mt-2 text-xl font-medium text-black">
            {{ $labTask->task_name }}
        </h2>

        <p class="text-md text-black break-words whitespace-normal p-6">
            {{ $labTask->description }}
        </p>
        <p class="mt-1 text-sm text-black"> Total Marks: {{ $labTask->total_points }} <br>
            Full Marks Deadline: {{ $labTask->fm_due_date->format('d/m/Y') }} <br>
            Half Marks Deadline: {{ $labTask->hm_due_date->format('d/m/Y') }} <br></p>

        <div class="mt-4
            mr-4 mb-4 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Close') }}
            </x-secondary-button>
        </div>
    </x-modal>
</div>
