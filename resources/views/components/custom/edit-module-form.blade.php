<div>
    <x-tertiary-button x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-module-edit')">{{ __('Edit Module') }}</x-tertiary-button>

    <x-modal name="confirm-module-edit" :show="$errors->{'moduleEdit' . $module->id}->isNotEmpty()" focusable>
        <form method="post" action="{{ route('modules.update', $module) }}" enctype="multipart/form-data" class="p-6">
            @method('PATCH')
            @csrf

            <h2 class="text-lg font-medium text-gray-900">
                <p>Are you sure you want to edit {{ $module->module_code }}, {{ $module->module_name }}?
                </p>
            </h2>

            <p class="mt-1 text-sm text-gray-700">
                {{ __('Changes made to this module will be final and you will not be able to automatically revert these changes.') }}
            </p>

            <div class="flex flex-wrap -mx-3 mb-6 mt-2">
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        for="grid-module-name">
                        Module Name
                    </label>
                    <input
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        id="grid-module-name" type="text" name="module_name" placeholder="Input the module name..."
                        value="{{ old('module_name', $module->module_name) }}">
                    <x-input-error :messages="$errors->{'moduleEdit' . $module->id}->get('module_name')" class="mt-2" />

                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        for="grid-module-code">
                        Module Code
                    </label>
                    <input
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        id="grid-module-code" type="text" name="module_code" placeholder="Input the module code..."
                        value="{{ old('module_code', $module->module_code) }}">
                    <x-input-error :messages="$errors->{'moduleEdit' . $module->id}->get('module_code')" class="mt-2" />

                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-2">
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        for="grid-module-coordinator">
                        Module Leader
                    </label>
                    <select
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        id="grid-module-coordinator" name="module_leader_id">
                        <option value="">Select a lecturer...</option>
                        @foreach ($lecturers as $lecturer)
                            <option value="{{ $lecturer->id }}">{{ $lecturer->name }} ({{ $lecturer->email }})</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->{'moduleEdit' . $module->id}->get('module_leader_id')" class="mt-2" />

                </div>
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        for="grid-year-group">
                        Year Group
                    </label>
                    <select
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        id="grid-year-group" name="year_group">
                        <option value="">Select year group</option>
                        @for ($yearGroup = 0; $yearGroup < 5; $yearGroup++)
                            <option value="{{ $yearGroup }}">{{ $yearGroup }}</option>
                        @endfor
                    </select>
                    <x-input-error :messages="$errors->{'moduleEdit' . $module->id}->get('year_group')" class="mt-2" />

                </div>
                @php
                    $currentYear = now()->year;
                @endphp
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        for="grid-year-taught">
                        Year Taught
                    </label>
                    <select
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        id="grid-year-taught" name="year_taught">
                        <option value="">Select year taught</option>
                        @for ($year = $currentYear - 1; $year < $currentYear + 5; $year++)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                    <x-input-error :messages="$errors->{'moduleEdit' . $module->id}->get('year_taught')" class="mt-2" />
                </div>
            </div>

            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        for="grid-bg-image">
                        Background Image
                    </label>
                    <input
                        class="appearance-none block w-full text-gray-700  rounded py-1 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        id="grid-bg-image" type="file" name="image" value="{{ old('image') }}">
                    <x-input-error :messages="$errors->{'moduleEdit' . $module->id}->get('image')" class="mt-2" />
                    <p class="text-gray-600 text-xs italic">Upload a PNG, JPG, or JPEG</p>
                </div>
            </div>

            <div class="flex flex-row justify-center gap-4">
                <x-primary-button type="submit">SAVE</x-primary-button>
                <a href="{{ route('modules.show', $module) }}">
                    <x-tertiary-button>CANCEL</x-tertiary-button>
                </a>
            </div>
        </form>
    </x-modal>
</div>
