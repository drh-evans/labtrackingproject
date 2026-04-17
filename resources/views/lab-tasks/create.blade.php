@extends('layouts.labapp')

@section('title', 'Create a Lab Task')

@section('content')

    <div class="mt-4 flex flex-col items-center">
        @include('components.custom.error')

        <div class="bg-gray-50 p-6 rounded-lg shadow-lg max-w-lg m-10">
            <!-- Tailwind CSS form template, modified from https://v1.tailwindcss.com/components/forms , form grid-->
            <form method="POST" action="{{ route('lab-task.store', $module) }}" class="w-full max-w-lg">
                @csrf
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="grid-task-name">
                            Task Name
                        </label>
                        <input
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="grid-task-name" type="text" name="task_name" placeholder="Input the task name..."
                            value="{{ old('task_name') }}">
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="grid-description">
                            Task Description
                        </label>
                        <textarea
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="grid-description" type="text" name="description" placeholder="Input the task description...">{{ old('description') }}</textarea>
                        <p class="text-gray-600 text-xs italic">The description has a limit of 500 characters</p>
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="grid-total-points">
                            Total Points
                        </label>
                        <input
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="grid-total-points" type="number" name="total_points" step="0.5" min="0"
                            max="10" value="{{ old('total_points') }}" placeholder="0">
                        <p class="text-gray-600 text-xs italic">Range of 0 and 10</p>
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="grid-full-marks">
                            Full Marks Due
                        </label>
                        <input
                            class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="grid-full-marks" type="date" name="fm_due_date">
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="grid-half-marks">
                            Half Marks Due
                        </label>
                        <input
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="grid-half-marks" type="date" name="hm_due_date">
                    </div>
                </div>

                <div class="flex flex-row justify-center gap-2">
                    <button type="submit"
                        class="rounded-lg font-semibold shadow-md text-sm uppercase bg-green-500 hover:bg-green-600 text-white border-green-300 hover:border-green-400 px-2 py-1">
                        Create</button>
                    <a href="{{ route('modules.show', $module) }}"> <x-tertiary-button>CANCEL</x-tertiary-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
