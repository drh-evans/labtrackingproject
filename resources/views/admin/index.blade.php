@extends('layouts.labapp')

@section('title', 'Admin Centre')

@section('content')

    <div class="max-w-8xl mx-auto px-6 lg:px-8 mt-6 ">
        <div class="bg-gray-50 p-6 m-10 rounded-lg shadow-lg border border-gray-200">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10">

                <h1 class="text-2xl md:text-3xl font-semibold">
                    Welcome to lab tracker {{ auth()->user()->name }}!
                </h1>
                <h2 class="text-md md:text-xl text-gray-700"> Role: System Administrator
                </h2>

                <p class="mt-2 text-gray-600 max-w-2xl">
                    Welcome to the admin dashboard, your hub for managing Lab Tracker. </p>
                <p class="text-xs font-bold mt-2 text-red-600 max-w-2xl">
                    Important: CSV files must be imported in Comma-seperated Values format, CSV UTF-8 will
                    fail. </p>

                <div class="flex flex-r0w gap-4 mt-4 items-center justify-center">
                    <a href="{{ route('modules.create') }}">
                        <x-secondary-button>{{ __('Create Module') }}</x-secondary-button> </a>

                    <a href="{{ route('admin.users') }}">
                        <x-secondary-button>{{ __('User Management') }}</x-secondary-button> </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mx-10 mt-6">
            <div class="bg-gray-50 p-6  rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-xs text-gray-700 uppercase"> Users
                </h2>
                <a href="{{ route('admin.users') }}">
                    <p class="text-lg mt-2 text-gray-600 hover:text-black max-w-2xl font-semibold">
                        {{ $totalStudents }} Students <br>
                        {{ $totalTA }} TAs <br>
                        {{ $totalLecturers }} Lecturers
                    </p>
                </a>
            </div>

            <div class="bg-gray-50 p-6  rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-xs text-gray-700 uppercase"> Total modules
                </h2>
                <a href="{{ route('modules.index') }}">
                    <p class="text-lg mt-2 text-gray-600 hover:text-black max-w-2xl font-semibold">
                        {{ $totalModules }} </p>
                </a>
            </div>

            <div class="bg-gray-50 p-6  rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-xs text-gray-700 uppercase"> Total tasks
                </h2>
                <p class="text-lg mt-2 text-gray-600 hover:text-black max-w-2xl font-semibold">
                    {{ $totalTasks }} </p>
            </div>

            <div class="bg-gray-50 p-6  rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-xs text-gray-700 uppercase"> active queues
                </h2>
                <p class="text-lg mt-2 text-gray-600 hover:text-black max-w-2xl font-semibold">
                    {{ $activeQueues }} </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mx-10 mt-6 mb-10">
            <div class="bg-gray-50 p-6 rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-sm text-gray-700 uppercase"> import users
                </h2>
                <p class="text-xs text-gray-500 mt-1">CSV in the format: <br>email,name,role </p>
                <form method="POST" action="{{ route('users.import') }}" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    <input type="file" name="csv" accept=".csv,.txt" class="block w-full text-sm text-gray-700 mb-3">
                    <x-input-error :messages="$errors->get('csv')" class="mt-1" />
                    <button type="submit"
                        class="rounded-lg font-semibold text-sm uppercase text-white bg-green-500 hover:bg-green-700 px-2 py-1 w-full">Import
                        Users</button>
                </form>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-sm text-gray-700 uppercase"> import modules
                </h2>
                <p class="text-xs text-gray-500 mt-1">CSV in the format:
                    module_code,module_name,coordinator_email,year_group,year_taught </p>
                <form method="POST" action="{{ route('modules.import') }}" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    <input type="file" name="csv" accept=".csv,.txt" class="block w-full text-sm text-gray-700 mb-3">
                    <x-input-error :messages="$errors->get('csv')" class="mt-1" />
                    <button type="submit"
                        class="rounded-lg font-semibold text-sm uppercase text-white bg-green-500 hover:bg-green-700 px-2 py-1 w-full">Import
                        Modules</button>
                </form>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg shadow-lg border border-gray-200 ">
                <h2 class="text-sm text-gray-700 uppercase"> bulk enrol
                </h2>
                <p class="text-xs text-gray-500 mt-1">CSV in the format:<br> email,module_code,year_taught </p>
                <form method="POST" action="{{ route('enrolment.import') }}" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    <input type="file" name="csv" accept=".csv,.txt" class="block w-full text-sm text-gray-700 mb-3">
                    <x-input-error :messages="$errors->get('csv')" class="mt-1" />
                    <button type="submit"
                        class="rounded-lg font-semibold text-sm uppercase text-white bg-green-500 hover:bg-green-700 px-2 py-1 w-full">Enrol
                        Users</button>
                </form>
            </div>
        </div>
    </div>
@endsection
