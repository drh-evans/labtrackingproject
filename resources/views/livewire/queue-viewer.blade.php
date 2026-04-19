<div>
    <div class="bg-gray-50 p-6 m-10 rounded-lg shadow-lg border border-gray-200">
        <a href="{{ route('modules.show', $queue->module->id) }}">
            <x-tertiary-button>{{ __('return') }}</x-tertiary-button>
        </a>

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

            <p class="mt-4">The average wait is currently: <strong>{{ $averageWait }}</strong></p>

            @if ($joinedAt)
                <div wire:key="queue-timer-{{ $joinedAt }}" x-data="queueTimer({{ $joinedAt }})">
                    <p>You have currently been in the queue for: <strong x-text="timeString"></strong></p>
                </div>
            @endif
        </div>

        @can('join-queue')
            <div class="flex items-center justify-center">
                <livewire:join-queue-button :queue="$queue" />
            </div>
        @endcan
        @can('access-analytics', $queue->module)
            <div class="flex items-center justify-center">
                <a href="{{ route('queue.analytics', $queue->module->id) }}">
                    <x-tertiary-button>{{ __('analytics') }}</x-tertiary-button>
                </a>
            </div>
        @endcan
    </div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
        @if ($entries->isNotEmpty())
            <div class="bg-gray-50 p-6 m-10 rounded-lg shadow-md border border-gray-200 overflow-hidden">
                <table class="w-full text-center">
                    <tr class="border-b border-gray-200 bg-gray-100">
                        <th class="px-5 py-3">Position</th>
                        <th class="px-5 py-3">Name</th>
                        <th class="px-5 py-3">Assistance Type</th>

                        @can('accessAdmin', $queue)
                            <th class="px-5 py-4">Task</th>
                            <th class="px-5 py-3">Location</th>
                            <th class="px-5 py-3">Admin Actions</th>
                        @endcan
                    </tr>

                    @foreach ($entries as $entry)
                        <tr wire:key="entry-{{ $entry->id }}" class="border-b border-gray-100 px-5 py-4">
                            <td class="px-5 py-4">
                                <div
                                    class="w-7 h-7 rounded-lg flex items-center justify-center font-medium mx-auto shadow-sm {{ $entry->queue_position === 1 ? 'bg-green-400 text-gray-700' : 'bg-gray-500 text-white' }}">
                                    {{ $entry->queue_position }}</div>
                            </td>
                            <td class="px-5 py-4 font-medium">{{ $entry->student->name }}</td>
                            <td class="px-5 py-4">
                                <span
                                    class="font-medium px-2 py-1 rounded-lg shadow-sm {{ $entry->assistance_type === 'Assistance' ? 'bg-yellow-400 text-gray-700' : 'bg-blue-500 text-gray-200' }}">
                                    {{ $entry->assistance_type }}
                                </span>
                            </td>

                            @can('accessAdmin', $queue)
                                <td class="px-5 py-4">{{ $entry->task }}</td>
                                <td class="px-5 py-4">{{ $entry->location }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-row items-center justify-center gap-2">
                                        <livewire:remove-from-queue :queueEntry="$entry" :key="'remove' . $entry->id" />
                                        @include('components.custom.queue-details-modal', [
                                            'queue' => $queue,
                                            'entry' => $entry,
                                        ])
                                    </div>
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                </table>
            </div>
        @else
            <p class="text-gray-500 text-md text-center p-10">The queue is currently empty...</p>
        @endif
    </div>
</div>
