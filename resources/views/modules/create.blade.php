@extends('layouts.labapp')

@section('title', 'Create a module')

@section('content')

    <div class="mt-4 flex flex-col items-center">

        @include('components.custom.error')

        <form method="POST" action="{{ route('modules.store') }}" enctype="multipart/form-data">
            @csrf
            <p>Module Code:</p>
            <input type="text" name="module_code" placeholder="Input the module code..." value="{{ old('module_code') }}">
            <p>Module Name:</p>
            <input type="text" name="module_name" placeholder="Input the module name..." value="{{ old('module_name') }}">
            <input type="file" class="mt-2" name="image">
            <div>
                <button type="submit" class="rounded-lg bg-green-500 hover:bg-green-400 px-4 py-2"> Create Module</button>
                <a href="{{ route('admin.dashboard') }}" class="rounded-lg bg-gray-500 hover:bg-gray-400 px-4 py-2"> Cancel
                </a>
            </div>
        </form>
    </div>

@endsection
