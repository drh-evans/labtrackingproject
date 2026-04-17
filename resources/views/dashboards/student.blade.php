@extends('layouts.labapp')
@section('title', 'Welcome')

@section('content')
    <div class="max-w-8xl mx-auto px-6 lg:px-8 mt-6 ">
        <div class="bg-gray-50 p-6  rounded-lg shadow-lg border border-gray-200">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10">

                <h1 class="text-2xl md:text-3xl font-semibold">
                    Welcome to lab tracker {{ auth()->user()->name }}!
                </h1>
                <h2 class="text-md md:text-xl text-gray-700"> Role: Student
                </h2>
                <p class="mt-1 text-sm text-red-600 max-w-2xl"> If your role is incorrect, please contact
                    systemadmin@labtracker.com</p>

                <p class="mt-2 text-gray-600 max-w-2xl">
                    Your home for lab sessions at Swansea University, whether its getting help or staying on track with your
                    work.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mx-10 mt-6">
            <div class="bg-gray-50 p-6  rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-xs text-gray-700 uppercase"> Enrolled in
                </h2>
                <a href={{ route('modules.index') }}
                    class="text-lg mt-2 text-gray-600 hover:text-black max-w-2xl font-semibold">
                    {{ $modules->count() }} modules </a>
            </div>

            <div class="bg-gray-50 p-6  rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-xs text-gray-700 uppercase"> Tasks Completed
                </h2>
                <p class="text-lg mt-2 text-gray-600 hover:text-black max-w-2xl font-semibold">
                    {{ $percentageTasksCompleted }}% </p>
            </div>

            <div class="bg-gray-50 p-6  rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-xs text-gray-700 uppercase"> Average Grade
                </h2>
                <p class="text-lg mt-2 text-gray-600 hover:text-black max-w-2xl font-semibold">
                    {{ $averageMarks }}%
                    ({{ $averageMarks >= 70
                        ? 'First Class'
                        : ($averageMarks >= 60
                            ? '2.1'
                            : ($averageMarks >= 50
                                ? '2.2'
                                : ($averageMarks >= 40
                                    ? 'Pass'
                                    : 'Fail'))) }})
                </p>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-xs text-gray-700 uppercase"> Current Position
                </h2>
                @if ($currentEntry)
                    <a href="{{ route('module.queue', $currentEntry->queue->module) }}"
                        class="text-lg mt-2 text-gray-600 hover:text-black max-w-2xl font-semibold">
                        {{ $currentEntry->queue_position }} &#8227; {{ $currentEntry->queue->module->module_code }}
                    </a>
                @else
                    <p class="text-lg mt-2 text-gray-600 hover:text-black max-w-2xl font-semibold">
                        Not in a queue
                    </p>
                @endif
            </div>
        </div>

        @if ($openModules->isNotEmpty())
            <div class="bg-gray-50 p-6 rounded-lg shadow-lg border border-gray-200 mx-10 mt-6">
                <h2 class="text-xs text-gray-700 uppercase"> Active Lab Sessions
                </h2>
                <div class="flex flex-wrap gap-2">
                    @foreach ($openModules as $module)
                        <a href={{ route('modules.show', $module) }}
                            class="bg-green-400 hover:bg-green-500 text-gray-700 text-md mt-2 font-semibold px-3 py-2 rounded-lg">
                            {{ $module->module_code }} &#8227; {{ $module->module_name }}
                        </a> <br>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mx-10 mt-6 mb-10">
            <div class="bg-gray-50 p-6 rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-xs text-gray-700 uppercase"> Recent Modules
                </h2>
                @forelse($recentModules as $module)
                    <div class="bg-slate-200 hover:bg-slate-300 mt-4 px-6 py-2 rounded-lg shadow-lg">
                        <a href={{ route('modules.show', $module) }} class="text-md mt-2 font-semibold">
                            {{ $module->module_code }} &#8227; {{ $module->module_name }}
                        </a> <br>
                        <p class="text-sm text-black">{{ $module->year_taught }}</p>
                    </div>
                @empty
                    <p class="font-semibold text-gray-600 hover:text-black max-w-2xl text-center mt-32">No modules found!
                    </p>
                @endforelse
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-xs text-gray-700 uppercase"> Upcoming Deadlines
                </h2>
                @forelse($tasks as $task)
                    <div class="bg-slate-200 hover:bg-slate-300 mt-4 px-6 py-2 rounded-lg shadow-lg">
                        <a href={{ route('modules.show', $task->module_id) }} class="text-md mt-2 font-semibold">
                            {{ $task->module->module_code }} &#8227; {{ $task->task_name }}
                        </a> <br>
                        <p class="text-sm {{ $task->fm_due_date->isToday() ? 'text-red-600' : 'text-yellow-600' }}">Due by
                            {{ $task->fm_due_date->format('d/m/Y') }}
                            <strong> {{ $task->fm_due_date->isToday() ? ' - TODAY ' : '' }}
                            </strong>
                        </p>
                    </div>
                @empty
                    <p class="font-semibold text-gray-600 hover:text-black max-w-2xl text-center mt-32">No upcoming
                        deadlines!</p>
                @endforelse
            </div>

            <div class="bg-gray-50 p-6 rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-xs text-gray-700 uppercase"> Missed Deadlines
                </h2>
                @forelse($missedTasks as $task)
                    <div class="bg-slate-200 hover:bg-slate-300 mt-4 px-6 py-2 rounded-lg shadow-lg">
                        <a href={{ route('modules.show', $task->module_id) }} class="text-md mt-2 font-semibold">
                            {{ $task->module->module_code }} &#8227; {{ $task->task_name }}
                        </a> <br>
                        <p class="text-sm text-red-600">Due on {{ $task->fm_due_date->format('d/m/Y') }}</p>
                    </div>
                @empty
                    <p class="font-semibold text-gray-600 hover:text-black max-w-2xl text-center mt-32">You're all caught
                        up!</p>
                @endforelse
            </div>

            <div class="bg-gray-50 p-6 rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-xs text-gray-700 uppercase"> Completed Tasks
                </h2>
                @forelse($completedTasks as $task)
                    <div class="bg-slate-200 hover:bg-slate-300 mt-4 px-6 py-2 rounded-lg shadow-lg">
                        <a href={{ route('modules.show', $task->module_id) }} class="text-md mt-2 font-semibold">
                            {{ $task->module->module_code }} &#8227; {{ $task->task_name }}
                        </a> <br>
                        <p class="text-sm text-green-600">Completed</p>
                    </div>
                @empty
                    <p class="font-semibold text-gray-600 hover:text-black max-w-2xl text-center mt-32">No completed tasks!
                    </p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
