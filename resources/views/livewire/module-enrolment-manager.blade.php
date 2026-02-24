<div class="flex flex-col items-center justify-center overflow-x-auto">
    <div class="flex- flex-row mb-6">
        <input type="text" wire:model.live.debounce.250ms="nameSearch" placeholder="Enter an email..."
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">

        <select wire:model.live="roleFilter"
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
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
                    </tr>
                </thead>
                @foreach ($users as $user)
                    <tr wire:key="user-{{ $user->id }}"
                        class="odd:bg-neutral-primary even:bg-neutral-secondary-soft border-b border-default">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">{{ $user->email }}</td>
                        <td class="px-6 py-4">{{ $user->name }}</td>
                        <td class="px-6 py-4">
                            @if (in_array($user->id, $enroledUsers))
                                <button wire:key="unenrol-button" wire:click="unenrol({{ $user->id }})"
                                    class="p-2 rounded-lg bg-red-700 hover:bg-red-500 text-white">
                                    Unenrol
                                </button>
                            @else
                                <button wire:key="enrol-button" wire:click="enrol({{ $user->id }})"
                                    class="p-2 rounded-lg bg-green-700 hover:bg-green-500 text-white">
                                    Enrol
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
            <div class="mt-4"> {{ $users->links() }}
            </div>
        </div>
    @else
        <p>No results found, review your search.</p>
    @endif
</div>
