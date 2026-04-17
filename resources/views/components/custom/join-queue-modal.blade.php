<div>
    <x-modal name="join-queue-{{ $queue->id }}" :show="$errors->{'joinQueue' . $queue->id}->isNotEmpty()" focusable>
        <form method="POST" action="{{ route('queue.join', $module) }}" class="p-6">
            @csrf
            <div class="flex flex-wrap -mx-3 mb-6 mt-4 text-left">
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        Lab Task
                    </label>
                    <select name="task"
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                        <option value="">Select a task</option>
                        @foreach ($labTasks as $task)
                            <option value="{{ $task->task_name }}">{{ $task->task_name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->{'joinQueue' . $queue->id}->get('task')" class="mt-2" />
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6 text-left">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"> Further Details
                        (Optional)
                    </label>
                    <textarea name="information" placeholder="Describe an issue you're facing e.g. out of bounds error"
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">{{ old('information') }}</textarea>
                    <p class="text-gray-600 text-xs italic">Maximum of 500 characters</p>
                    <x-input-error :messages="$errors->{'joinQueue' . $queue->id}->get('information')" class="mt-2" />
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-2 text-left">
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        Assistance Type
                    </label>
                    <select name="assistanceType"
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                        <option value="">Select an option</option>
                        <option value="Assistance">Assistance</option>
                        <option value="Sign-off">Sign-off</option>
                    </select>
                    <x-input-error :messages="$errors->{'joinQueue' . $queue->id}->get('assistanceType')" class="mt-2" />
                </div>
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        for="grid-full-marks">
                        Location
                    </label>
                    <input name="location" type="text" placeholder="E.g. CoFo 104, A19"
                        class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        value="{{ old('location') }}">
                    <p class="text-gray-600 text-xs italic">Room and seat number (on desk)</p>
                    <x-input-error :messages="$errors->{'joinQueue' . $queue->id}->get('location')" class="mt-2" />

                </div>
            </div>

            <div class="mt-6 flex gap-2 justify-end">
                <x-tertiary-button x-on:click="$dispatch('close')">
                    {{ __('Close') }}
                </x-tertiary-button>
                <button type="submit"
                    class="rounded-lg font-semibold text-sm uppercase text-white bg-green-500 hover:bg-green-400 px-4 py-2">
                    join </button>
            </div>
        </form>
    </x-modal>
</div>
