@extends('layouts.labapp')
@section('title')
    {{ $module->module_code }} - {{ $module->module_name }}
@endsection

@section('content')
    <div class="flex flex-row items-center justify-center gap-4 mt-5 ml-4 mb-10">
        @can('create', App\Models\LabTask::class)
            <a href="{{ route('lab-task.create', $module) }}"> <x-secondary-button> Create a Task </x-secondary-button></a>
        @endcan

        @can('access-enrolment', $module)
            <a href="{{ route('module.enrolment', $module) }}"> <x-secondary-button> Enrolment </x-secondary-button></a>
        @endcan

        @can('access-staff')
            <a href="{{ route('module.search', $module) }}"> <x-secondary-button> Staff </x-secondary-button> </a>
        @endcan

        @can('update', $module)
            @include('components.custom.edit-module-form', $module)
        @endcan

        @can('delete', $module)
            <form action="{{ route('modules.destroy', $module) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-red-600 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">Delete
                    Module</button>
            </form>
        @endcan
    </div>


    <div class="flex flex-col items-center justify-center">
        <div
            class="relative inline-block overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
            <table class="text-sm text-center rtl:text-right text-body">
                <thead class="bg-neutral-secondary-soft border-b border-default">
                    <tr>
                        <th class="px-6 py-3 font-medium">Task Name</th>
                        <th class="px-6 py-3 font-medium">Full Marks Due Date</th>
                        <th class="px-6 py-3 font-medium">Half Marks Due Date</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium">Marks</th>
                    </tr>
                </thead>
                @foreach ($labTasks as $labTask)
                    <tr class="odd:bg-neutral-primary even:bg-neutral-secondary-soft border-b border-default">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">{{ $labTask->task_name }}
                        </td>
                        <td class="px-6 py-4">{{ $labTask->fm_due_date }}</td>
                        <td class="px-6 py-4">{{ $labTask->hm_due_date }}</td>
                        <td class="px-6 py-4">
                            @if (isset($signOffs[$labTask->id]))
                                <!-- unicode for green checkmark -->
                                <p>&#9989;</p>
                            @else
                                <div>
                                    <!-- unicode for red cross -->
                                    <p>&#10060;</p>
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

                        @can('update', $labTask)
                            <td> @include('components.custom.edit-lab-task-form', [
                                'labTask' => $labTask,
                                'module' => $module,
                            ])
                            </td>
                        @endcan

                        @can('delete', $labTask)
                            <td>
                                <form action="{{ route('lab-task.destroy', [$module, $labTask]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 ml-2 mr-2 bg-white dark:bg-red-600 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        @endcan
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
