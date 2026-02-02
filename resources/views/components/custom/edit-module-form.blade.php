<div>
    <x-custom.edit-button x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-module-edit')">{{ __('Edit Module') }}</x-customedit-button>

        <x-modal name="confirm-module-edit" :show="$errors->any()" focusable>
            <form method="post" action="{{ route('modules.update', $module) }}" class="p-6">
                @method('PATCH')
                @csrf

                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    <p>Are you sure you want to edit {{ $module->module_code }}, {{ $module->module_name }}?
                    </p>
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Changes made to this module will be final and you will not be able to automatically revert these changes.') }}
                </p>

                <div class="mt-6">
                    <x-input-label for="module_code" value="{{ __('Module Code') }}" class="sr-only" />

                    <x-text-input id="module_code" name="module_code" type="text" class="mt-1 block w-3/4"
                        value="{{ $module->module_code }}" />

                    <x-input-label for="module_name" value="{{ __('Module Name') }}" class="sr-only" />

                    <x-text-input id="module_name" name="module_name" type="text" class="mt-1 block w-3/4"
                        value="{{ $module->module_name }}" />

                    <input type="file" class="mt-2 text-white" name="image">

                    @include('components.custom.error')
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
