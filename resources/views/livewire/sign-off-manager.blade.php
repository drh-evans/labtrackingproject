<div class="flex flex-col items-center justify-center overflow-x-auto">
    @if ($labTasks->isNotEmpty())

        <div class="relative inline-block bg-neutral-primary-soft shadow-xs rounded-base border border-default mt-6">
            <table class="text-sm text-center rtl:text-right text-body">
                <thead class="bg-neutral-secondary-soft border-b border-default">
                    <tr>
                        <th class="px-6 py-3 font-medium">Task Name</th>
                        <th class="px-6 py-3 font-medium">Full Marks Deadline</th>
                        <th class="px-6 py-3 font-medium">Half Marks Deadline</th>
                        <th class="px-6 py-3 font-medium">Total Marks</th>
                        <th class="px-6 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($labTasks as $labTask)
                        <tr class="odd:bg-neutral-primary even:bg-neutral-secondary-soft border-b border-default">
                            <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">{{ $labTask->task_name }}
                            </td>
                            <td class="px-6 py-4">{{ $labTask->fm_due_date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">{{ $labTask->hm_due_date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">{{ $labTask->total_points }}</td>
                            <td class="px-6 py-4">
                                @if (isset($signOffs[$labTask->id]))
                                    <button wire:click="removeSignOff({{ $labTask->id }})"
                                        class="inline-flex items-center px-4 py-2 mr-2 bg-white dark:bg-red-600 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                        Remove
                                    </button>
                                @else
                                    <div>
                                        <button wire:click="signOff({{ $labTask->id }},'half')"
                                            class="inline-flex items-center px-4 py-2 mr-2 bg-white dark:bg-orange-600 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                            Half
                                        </button>

                                        <button wire:click="signOff({{ $labTask->id }},'full')"
                                            class="inline-flex items-center px-4 py-2 mr-2 bg-white dark:bg-green-700 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                            Full
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>No lab tasks found... </p>
    @endif
</div>
