<x-modal name="confirm-user-edit-{{ $user->id }}" :show="$errors->{'userEdit' . $user->id}->isNotEmpty()" focusable>
    <!-- Tailwind CSS form template, modified from https://v1.tailwindcss.com/components/forms , form grid-->
    <form method="POST" action="{{ route('user.update', [$user]) }}" class="p-6">
        @method('PATCH')
        @csrf
        <h2 class="text-lg font-medium text-black">
            <p>Are you sure you want to edit {{ $user->name }} ({{ $user->email }})?
            </p>
        </h2>

        <p class="mt-1 text-xs text-red-600">
            {{ __('Changes made to this user will be final and you will not be able to automatically revert these changes.') }}
        </p>
        <div class="flex flex-wrap -mx-3 mb-6 mt-4 text-left">
            <div class="w-full md:w-1/2 px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-user-name">
                    Name
                </label>
                <input
                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                    id="grid-user-name" type="text" name="name" value="{{ old('name', $user->name) }}">
                <x-input-error :messages="$errors->{'userEdit' . $user->id}->get('name')" class="mt-2" />
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6 text-left">
            <div class="w-full px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-email">
                    Email
                </label>
                <input
                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                    id="grid-email" type="text" name="email" value="{{ old('email', $user->email) }}">
                <x-input-error :messages="$errors->{'userEdit' . $user->id}->get('email')" class="mt-2" />
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6 text-left">
            <div class="w-full px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    Role
                </label>
                <select name="role"
                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                    <option value="">Select a role</option>
                    <option value="1">Student</option>
                    <option value="2">Teaching Assistant</option>
                    <option value="3">Lecturer</option>
                </select>
                <x-input-error :messages="$errors->{'userEdit' . $user->id}->get('role')" class="mt-2" />
            </div>
        </div>

        <div class="mt-6 flex gap-2 justify-end">
            <x-primary-button type="submit">
                {{ __('Save Changes') }}
            </x-primary-button>

            <x-tertiary-button x-on:click="$dispatch('close')">
                {{ __('Close') }}
            </x-tertiary-button>
        </div>
    </form>
</x-modal>
</div>
