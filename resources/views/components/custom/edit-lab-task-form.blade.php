<div>
    <x-tertiary-button x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-task-edit-{{ $labTask->id }}')">{{ __('Edit') }}</x-tertiary-button>

    <x-modal name="confirm-task-edit-{{ $labTask->id }}" :show="$errors->{'labTaskEdit' . $labTask->id}->isNotEmpty()" focusable>
        <!-- Tailwind CSS form template, modified from https://v1.tailwindcss.com/components/forms , form grid-->
        <form method="POST" action="{{ route('lab-task.update', [$module, $labTask]) }}" class="p-6">
            @method('PATCH')
            @csrf
            <h2 class="text-lg font-medium text-black">
                <p>Are you sure you want to edit "{{ $labTask->task_name }}"?
                </p>
            </h2>

            <p class="mt-1 text-xs text-red-600">
                {{ __('Changes made to this task will be final and you will not be able to automatically revert these changes.') }}
            </p>
            <div class="flex flex-wrap -mx-3 mb-6 mt-4 text-left">
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        for="grid-task-name">
                        Task Name
                    </label>
                    <input
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        id="grid-task-name" type="text" name="task_name"
                        value="{{ old('task_name', $labTask->task_name) }}">
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6 text-left">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        for="grid-description">
                        Task Description
                    </label>
                    <textarea
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        id="grid-description" type="text" name="description">{{ old('description', $labTask->description) }}</textarea>
                    <p class="text-gray-600 text-xs italic">The description has a limit of 500 characters</p>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-2 text-left">
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        for="grid-total-points">
                        Total Points
                    </label>
                    <input
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        id="grid-total-points" type="number" name="total_points" step="0.5" min="0"
                        max="10" value="{{ old('total_points', $labTask->total_points) }}">
                    <p class="text-gray-600 text-xs italic mt-2">Range of 0 and 10</p>
                </div>
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        for="grid-full-marks">
                        Full Marks Due
                    </label>
                    <input
                        class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        id="grid-full-marks" type="date" name="fm_due_date"
                        value="{{ old('fm_due_date', $labTask->fm_due_date->format('Y-m-d')) }}">
                </div>
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        for="grid-half-marks">
                        Half Marks Due
                    </label>
                    <input
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        id="grid-half-marks" type="date" name="hm_due_date"
                        value="{{ old('hm_due_date', $labTask->hm_due_date->format('Y-m-d')) }}">
                </div>
            </div>

            <x-input-error :messages="$errors->{'labTaskEdit' . $labTask->id}->get('task_name')" class="mt-2" />
            <x-input-error :messages="$errors->{'labTaskEdit' . $labTask->id}->get('total_points')" class="mt-2" />
            <x-input-error :messages="$errors->{'labTaskEdit' . $labTask->id}->get('fm_due_date')" class="mt-2" />
            <x-input-error :messages="$errors->{'labTaskEdit' . $labTask->id}->get('hm_due_date')" class="mt-2" />

            <div class="mt-6 flex gap-2 justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Close') }}
                </x-secondary-button>
                <button type="submit"
                    class="rounded-lg font-semibold text-xs text-white bg-green-500 hover:bg-green-400 px-4 py-2">
                    SAVE CHANGES </button>
            </div>
        </form>
    </x-modal>
</div>
