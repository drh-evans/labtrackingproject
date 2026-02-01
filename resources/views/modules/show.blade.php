@extends('layouts.labapp')
@section('title')
    {{ $module->module_code }} - {{ $module->module_name }}
@endsection

@section('content')
    @can('create', App\Models\LabTask::class)
        <a href="{{ route('lab-task.create', $module) }}">Create a Lab Task</a>
    @endcan

    @can('access-enrolment', $module)
        <a href="{{ route('module.enrolment', $module) }}"> Enrol Users</a>
    @endcan

    @can('access-staff')
        <a href="{{ route('module.search', $module) }}"> Staff </a>
    @endcan

    @can('update', $module)
        @include('components.custom.edit-module-form', $module)
    @endcan

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
                <tbody>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
