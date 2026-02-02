<div>
    <x-custom.details-button x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-task-details-{{ $labTask->id }}')">
        {{ _('Details') }}

    </x-custom.details-button>


    <x-modal name="confirm-task-details-{{ $labTask->id }}" focusable>
        <h2 class="mt-2 text-xl font-medium text-gray-900 dark:text-gray-100">
            {{ $labTask->task_name }}
        </h2>

        <p class="mt-1 text-lg text-gray-600 dark:text-gray-400">
            {{ $labTask->description }} <br>
        </p>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400"> Total Points: {{ $labTask->total_points }} <br>
            Full Marks Due Date: {{ $labTask->fm_due_date }} <br>
            Half Marks Due Date: {{ $labTask->hm_due_date }} <br></p>

        <div class="mt-4 mr-4 mb-4 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Close') }}
            </x-secondary-button>
        </div>
    </x-modal>
</div>
