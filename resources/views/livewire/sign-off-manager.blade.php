<div class="flex flex-col items-center justify-center overflow-x-auto">
    <div class="relative inline-block bg-neutral-primary-soft shadow-xs rounded-base border border-default">
        <table class="text-sm text-center rtl:text-right text-body">
            <thead class="bg-neutral-secondary-soft border-b border-default">
                <tr>
                    <th class="px-6 py-3 font-medium">Task Name</th>
                    <th class="px-6 py-3 font-medium">Full Marks Due Date</th>
                    <th class="px-6 py-3 font-medium">Half Marks Due Date</th>
                    <th class="px-6 py-3 font-medium">Total Marks</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($labTasks as $labTask)
                    <tr class="odd:bg-neutral-primary even:bg-neutral-secondary-soft border-b border-default">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">{{ $labTask->task_name }}</td>
                        <td class="px-6 py-4">{{ $labTask->fm_due_date }}</td>
                        <td class="px-6 py-4">{{ $labTask->hm_due_date }}</td>
                        <td class="px-6 py-4">{{ $labTask->total_points }}</td>
                        <td class="px-6 py-4">
                            @if (isset($signOffs[$labTask->id]))
                                <button wire:click="removeSignOff({{ $labTask->id }})" class="bg-red-600">
                                    Remove
                                </button>
                            @else
                                <div>
                                    <button wire:click="signOff({{ $labTask->id }},'half')" class="">
                                        Half
                                    </button>

                                    <button wire:click="signOff({{ $labTask->id }},'full')">
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
