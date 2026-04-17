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
                                <a href="{{ route('module.signoff', [$module, $student]) }}">
                                    <x-tertiary-button>{{ __('select') }}</x-tertiary-button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4"> {{ $students->links() }}
        </div>
    @else
        <p class="text-gray-500 text-md text-center p-10">No users found using this search... try again.</p>
    @endif
</div>
