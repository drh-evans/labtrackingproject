<div>
    <x-custom.edit-button x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-task-edit-{{ $labTask->id }}')">{{ __('Edit') }}</x-customedit-button>

        <x-modal name="confirm-task-edit-{{ $labTask->id }}" :show="$errors->{'labTaskEdit' . $labTask->id}->isNotEmpty()" focusable>
            <form method="post" action="{{ route('lab-task.update', [$module, $labTask]) }}" class="p-6">
                @method('PATCH')
                @csrf

                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    <p>Are you sure you want to edit '{{ $labTask->task_name }}'?
                    </p>
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Changes made to this task will be final and you will not be able to automatically revert these changes.') }}
                </p>

                <div class="flex flex-col items-start justify-end gap-2 mt-6">
                    <x-input-label for="task_name" value="{{ __('Task Name') }}" class="sr-only" />

                    <x-text-input id="task_name" name="task_name" type="text" class="mt-1 block w-3/4"
                        value="{{ $labTask->task_name }}" />

                    <x-input-label for="description" value="{{ __('Description') }}" class="sr-only" />

                    <x-text-input id="description" name="description" type="text" class="mt-1 block w-3/4"
                        value="{{ $labTask->description }}" />

                    <input type="number" name="total_points" step="0.5" min="0" max="10"
                        value="{{ $labTask->total_points }}"
                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <input type="date" name="fm_due_date" value="{{ $labTask->fm_due_date }}"
                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <input type="date" name="hm_due_date" value="{{ $labTask->hm_due_date }}"
                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">

                    <x-input-error :messages="$errors->{'labTaskEdit' . $labTask->id}->get('task_name')" class="mt-2" />
                    <x-input-error :messages="$errors->{'labTaskEdit' . $labTask->id}->get('total_points')" class="mt-2" />
                    <x-input-error :messages="$errors->{'labTaskEdit' . $labTask->id}->get('fm_due_date')" class="mt-2" />
                    <x-input-error :messages="$errors->{'labTaskEdit' . $labTask->id}->get('hm_due_date')" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">

                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Close') }}
                    </x-secondary-button>

                    <x-primary-button class="ms-3">
                        {{ __('Save Changes') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>
</div>
