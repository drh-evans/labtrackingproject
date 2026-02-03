<div class="flex flex-col items-center justify-center overflow-x-auto">
    <form>
        @csrf
        <input type="text" wire:model.live.debounce.250ms="nameSearch" placeholder="Enter an email..."
            class="mb-6 mt-6 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
    </form>

    @if ($students->isNotEmpty())
        <div class="relative inline-block bg-neutral-primary-soft shadow-xs rounded-base border border-default">
            <table class="text-sm text-center rtl:text-right text-body">
                <thead class="bg-neutral-secondary-soft border-b border-default">
                    <tr>
                        <th class="px-6 py-3 font-medium">Email</th>
                        <th class="px-6 py-3 font-medium">Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr wire:key="user-{{ $student->id }}"
                            class="odd:bg-neutral-primary even:bg-neutral-secondary-soft border-b border-default">
                            <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                                {{ $student->email }}</td>
                            <td class="px-6 py-4">{{ $student->name }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('module.signoff', [$module, $student]) }}"
                                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                    Select </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4"> {{ $students->links() }}
            </div>
        </div>
    @else
        <p>No users found using this search... try again.</p>
    @endif
</div>
