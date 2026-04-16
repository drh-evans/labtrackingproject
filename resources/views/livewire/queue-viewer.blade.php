<div>
    <div class="bg-gray-50 p-6 m-10 rounded-lg shadow-lg border border-gray-200">
        @include('components.custom.back-button')

        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10">
            <h1 class="text-2xl md:text-3xl font-semibold">
                Queue for {{ $queue->module->module_code }}, {{ $queue->module->module_name }}
            </h1>
            <h2 class="text-md md:text-xl text-gray-700"> Module Coordinator:
                @if ($queue->module->leader)
                    {{ $queue->module->leader->name }} ({{ $queue->module->leader->email }})
                @else
                    Staff member not found.
                @endif
            </h2>
            <p class="mt-1 text-red-600 max-w-2xl">
                Once you leave a queue, if you rejoin you will be placed at the back of the queue.
            </p>

            <p class="mt-4"><strong>{{ $entries->count() }}</strong> {{ Str::plural('student', $entries) }} currently
                in the queue.</p>
        </div>

        <livewire:join-queue-button :queue="$queue" />

    </div>

    <div class="flex flex-col items-center">
        @if ($entries->isNotEmpty())
            <table>
                <tr>
                    <th>Position</th>
                    <th>Name</th>
                    @can('accessAdmin', $queue)
                        <th>Assistance Type</th>
                        <th>Task</th>
                    @endcan
                </tr>

                @foreach ($entries as $entry)
                    <tr wire:key="entry-{{ $entry->id }}">
                        <td>{{ $entry->queue_position }}</td>
                        <td>{{ $entry->student->name }}</td>

                        @can('accessAdmin', $queue)
                            <td>{{ $entry->assistance_type }}</td>
                            <td>Task 1</td>
                            <td>
                                <livewire:remove-from-queue :queueEntry="$entry" :key="'remove' . $entry->id" />
                            </td>
                        @endcan
                    </tr>
                @endforeach
            </table>
        @else
            <p>The queue is currently empty...</p>
        @endif
    </div>
</div>
