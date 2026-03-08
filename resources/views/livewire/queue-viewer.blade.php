<div class="flex flex-col items-center">

    <p>{{ $entries->count() }} {{ Str::plural('student', $entries) }} currently in the queue.</p>

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
