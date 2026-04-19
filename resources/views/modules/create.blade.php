@extends('layouts.labapp')

@section('title', 'Create a module')

@section('content')

    <div class="mt-4 flex flex-col items-center">

        @include('components.custom.error')

        <div class="bg-gray-50 p-6 m-10 rounded-lg shadow-lg max-w-lg mx-auto">
            <!-- Tailwind CSS form template, modified from https://v1.tailwindcss.com/components/forms , form grid-->
            <form method="POST" action="{{ route('modules.store') }}" class="w-full max-w-lg">
                @csrf
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="grid-module-name">
                            Module Name
                        </label>
                        <input
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="grid-module-name" type="text" name="module_name" placeholder="Input the module name..."
                            value="{{ old('module_name') }}">
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="grid-module-code">
                            Module Code
                        </label>
                        <input
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="grid-module-code" type="text" name="module_code" placeholder="Input the module code..."
                            value="{{ old('module_name') }}">
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="grid-module-coordinator">
                            Module Leader
                        </label>
                        <select
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="grid-module-coordinator" name="module_leader_id">
                            <option value="">Select a lecturer...</option>
                            @foreach ($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}">{{ $lecturer->name }} ({{ $lecturer->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="grid-year-group">
                            Year Group
                        </label>
                        <select
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="grid-year-group" name="year_group">
                            <option value="">Select year group</option>
                            @for ($yearGroup = 0; $yearGroup < 5; $yearGroup++)
                                <option value="{{ $yearGroup }}">{{ $yearGroup }}</option>
                            @endfor
                        </select>
                    </div>
                    @php
                        $currentYear = now()->year;
                    @endphp
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="grid-year-taught">
                            Year Taught
                        </label>
                        <select
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="grid-year-taught" name="year_taught">
                            <option value="">Select year taught</option>
                            @for ($year = $currentYear - 1; $year < $currentYear + 5; $year++)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="grid-bg-image">
                            Background Image
                        </label>
                        <input
                            class="appearance-none block w-full text-gray-700  rounded py-1 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="grid-bg-image" type="file" name="image" value="{{ old('image') }}">
                        <p class="text-gray-600 text-xs italic">Upload a PNG, JPG, or JPEG</p>
                    </div>
                </div>

                <div class="flex flex-row justify-center gap-4">
                    <button type="submit"
                        class="rounded-lg bg-green-500 hover:bg-green-400 px-2 py-1 text-gray-700 text-sm uppercase font-semibold">
                        CREATE</button>
                    <a href="{{ route('admin.dashboard') }}">
                        <x-tertiary-button>CANCEL</x-tertiary-button>
                    </a>
                </div>
            </form>
        </div>
    @endsection
