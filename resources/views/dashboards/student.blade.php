@extends('layouts.labapp')
@section('title', 'Welcome')

@section('content')
    <div class="max-w-8xl mx-auto px-6 lg:px-8 mt-6 ">
        <div class="bg-gray-50 p-6 m-10 rounded-lg shadow-lg border border-gray-200">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10">

                <h1 class="text-2xl md:text-3xl font-semibold">
                    Welcome to lab tracker {{ auth()->user()->name }}!
                </h1>
                <h2 class="text-md md:text-xl text-gray-700"> Role: Student
                </h2>
                <p class="mt-1 text-xs text-red-600 max-w-2xl"> If your role is incorrect, please contact
                    systemadmin@labtracker.com</p>

                <p class="mt-2 text-gray-600 max-w-2xl">
                    Your home for lab sessions at Swansea University, whether its getting help or staying on track with your
                    work.
                </p>
            </div>
        </div>

        <div class="bg-gray-50 p-6 m-10 rounded-lg shadow-lg border border-gray-200 w-fit">
            <h2 class="text-mdtext-gray-700"> Upcoming Deadlines:
            </h2>
            @forelse($tasks as $task)
                <div class="bg-slate-200 mt-4 px-6 py-2 rounded-lg shadow-lg">
                    <a href={{ route('modules.show', $task->module_id) }} class="text-md mt-2 font-semibold">
                        {{ $task->module->module_code }} &#8227; {{ $task->task_name }}
                    </a> <br>
                    <p class="text-sm text-red-600">Due by {{ $task->fm_due_date->format('d/m/Y') }}</p>
                </div>
            @empty
                <p class="font-semibold">You're all caught up!</p>
            @endforelse
        </div>

        <div class="bg-gray-50 p-6 m-10 rounded-lg shadow-lg border border-gray-200 w-fit">
            <h2 class="text-mdtext-gray-700"> You can access:
            </h2>
            <a href={{ route('modules.index') }} class="mt-2 text-gray-600 hover:text-black max-w-2xl font-semibold">
                {{ $modules->count() }} modules </a>
        </div>
    </div>
@endsection
