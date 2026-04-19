<div class="flex flex-col items-center justify-center overflow-x-auto">
    <div class="flex- flex-row mb-6">
        <input type="text" wire:model.live.debounce.250ms="userSearch" placeholder="Enter a user..."
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300  rounded-md shadow-sm">

        <select wire:model.live="roleFilter"
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
            @can('access-admin')
                <option value="">All Roles</option>
                <option value="Lecturer">Lecturer</option>
            @endcan
            <option value="Teaching Assistant">Teaching Assistant</option>
            <option value="Student">Student</option>
        </select>
    </div>

    @if ($users->isNotEmpty())
        <div class="relative inline-block bg-neutral-primary-soft shadow-xs rounded-base border border-default">
            <table class="text-sm text-center rtl:text-right text-body">
                <thead class="bg-neutral-secondary-soft border-b border-default">
                    <tr>
                        <th class="px-6 py-3 font-medium">Email</th>
                        <th class="px-6 py-3 font-medium">Name</th>
                        <th class="px-6 py-3 font-medium">Role</th>
                        <th class="px-6 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                @foreach ($users as $user)
                    <tr wire:key="user-{{ $user->id }}"
                        class="odd:bg-neutral-primary even:bg-neutral-secondary-soft border-b border-default">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">{{ $user->email }}</td>
                        <td class="px-6 py-4">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->roles->first()->name }}</td>
                        <td class="px-6 py-4">
                            <div class="flex flex-row gap-2">
                                <button wire:click="deleteUser({{ $user->id }})"
                                    wire:confirm="Are you sure you want to delete {{ $user->email }}?"
                                    class="inline-flex items-center px-2 py-1 bg-red-600 border border-gray-500 rounded-md font-semibold text-sm text-white uppercase shadow-sm hover:bg-gray-50 dark:hover:bg-red-500 disabled:opacity-25 transition ease-in-out duration-150">
                                    Delete</button>
                                <div>
                                    <x-tertiary-button x-data=""
                                        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-edit-{{ $user->id }}')">{{ __('Edit') }}</x-tertiary-button>
                                </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="mt-4"> {{ $users->links() }}
        </div>
    @else
        <p>No results found, review your search.</p>
    @endif
</div>
