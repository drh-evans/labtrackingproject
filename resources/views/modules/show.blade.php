@extends('layouts.labapp')
@section('title')
    {{ $module->module_code }} - {{ $module->module_name }}
@endsection

@section('content')
    <div class="bg-gray-50 p-6 m-10 rounded-lg shadow-lg border border-gray-200">
        <a href="{{ route('modules.index') }}"> <x-tertiary-button>{{ __('return') }}</x-tertiary-button>
        </a>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10">
            <h1 class="text-2xl md:text-3xl font-semibold">
                {{ $module->module_code }}, {{ $module->module_name }}
            </h1>
            <h2 class="text-md md:text-xl text-gray-700"> Module Coordinator:
                @if ($module->leader)
                    {{ $module->leader->name }} ({{ $module->leader->email }})
                @else
                    Staff member not found.
                @endif
            </h2>
            <p class="mt-1 text-gray-600 max-w-2xl">
                Teaching Year: {{ $module->year_taught }}
            </p>

            <p class="text-md font-semibold mt-2"> Total Marks: {{ $totalEarnedMarks }} /
                {{ $totalModuleMarks }} ({{ $percentageEarned }}%)</p>

            <div class="flex items-center justify-center mt-2">
                @can('openQueue', $module)
                    @livewire('toggle-queue-button', ['module' => $module])
                @endcan
            </div>
        </div>
    </div>

    <div class="space-y-4 mt-5 mx-2 mb-10">
        <div class="flex flex-wrap gap-3 justify-center">
            @can('accessChatroom', $module)
                @include('components.custom.chatroom-button', ['module' => $module])
            @endcan

            @can('accessQueue', $module)
                @include('components.custom.queue-button', ['module' => $module])
            @endcan
        </div>
        <div class="flex flex-wrap gap-3 justify-center">
            @can('create', App\Models\LabTask::class)
                <a href="{{ route('lab-task.create', $module) }}"> <x-secondary-button> Create a Task </x-secondary-button></a>
            @endcan

            @can('access-enrolment', $module)
                <a href="{{ route('module.enrolment', $module) }}"> <x-secondary-button> Enrolment </x-secondary-button></a>
            @endcan

            @can('access-staff')
                <a href="{{ route('module.search', $module) }}"> <x-secondary-button> Staff </x-secondary-button> </a>
            @endcan
        </div>
        <div class="flex flex-wrap gap-3 justify-center">
            @can('delete', $module)
                <form action="{{ route('modules.destroy', $module) }}" method="POST" id="delete-module-{{ $module->id }}">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                        onclick="if(confirm('Are you sure you want to delete {{ $module->module_code }}, {{ $module->module_name }}?')) document.getElementById('delete-module-{{ $module->id }}').submit()"
                        class="inline-flex items-center px-2 py-1 bg-red-600 border border-gray-500 rounded-md font-semibold text-sm text-white uppercase shadow-sm hover:bg-gray-50 dark:hover:bg-red-500 disabled:opacity-25 transition ease-in-out duration-150">Delete</button>
                </form>
            @endcan

            @can('update', $module)
                @include('components.custom.edit-module-form', $module)
            @endcan
        </div>
    </div>

    <div class="flex flex-col items-center justify-center overflow-x-auto mb-10">
        @if ($labTasks->isNotEmpty())
            <div class="relative inline-block bg-neutral-primary-soft shadow-xs rounded-base border border-default">
                <table class="text-sm text-center rtl:text-right text-body">
                    <thead class="bg-neutral-secondary-soft border-b border-default">
                        <tr>
                            <th class="px-6 py-3 font-medium">Task Name</th>
                            <th class="px-6 py-3 font-medium">Status</th>
                            <th class="px-6 py-3 font-medium">Marks</th>
                            @can('access-enrolment', $module)
                                <th class="px-6 py-3 font-medium">Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    @foreach ($labTasks as $labTask)
                        <tr class="odd:bg-neutral-primary even:bg-neutral-secondary-soft border-b border-default">
                            <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">{{ $labTask->task_name }}
                                @include('components.custom.task-details', [
                                    'module' => $module,
                                    'labTask' => $labTask,
                                ])
                            </td>
                            <td class="px-6 py-4">
                                @if (isset($signOffs[$labTask->id]))
                                    <p>Complete</p>
                                @else
                                    <div>
                                        <!-- unicode for red cross -->
                                        <p>Incomplete</p>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if (isset($signOffs[$labTask->id]))
                                    <p>{{ $signOffs[$labTask->id] }} / {{ $labTask->total_points }}</p>
                                @else
                                    <div>
                                        <p>0.00 / {{ $labTask->total_points }}</p>
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="flex items-center justify-center gap-2">

                                    @can('delete', $labTask)
                                        <form action="{{ route('lab-task.destroy', [$module, $labTask]) }}" method="POST"
                                            id="delete-task-{{ $module->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="if(confirm('Are you sure you want to delete this task?')) document.getElementById('delete-task-{{ $module->id }}').submit()"
                                                class="inline-flex items-center px-2 py-1 bg-red-600 border border-gray-500 rounded-md font-semibold text-sm text-white uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-red-500 disabled:opacity-25 transition ease-in-out duration-150">
                                                Delete
                                            </button>
                                        </form>
                                    @endcan

                                    @can('update', $labTask)
                                        @include('components.custom.edit-lab-task-form', [
                                            'labTask' => $labTask,
                                            'module' => $module,
                                        ])
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-md text-center p-10">No lab tasks found...</p>
        @endif
    </div>
@endsection
