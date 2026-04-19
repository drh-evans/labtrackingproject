@extends('layouts.labapp')

@section('title', 'Admin Centre')

@section('content')

    <div class="max-w-8xl mx-auto px-6 lg:px-8 mt-6 ">
        <div class="bg-gray-50 p-6 m-10 rounded-lg shadow-lg border border-gray-200">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10">
                <a href="{{ route('admin.dashboard') }}"> <x-tertiary-button>{{ __('return') }}</x-tertiary-button>
                </a>
                <h1 class="text-2xl md:text-3xl font-semibold">
                    LabTracker User Management
                </h1>
                <h2 class="text-md md:text-xl text-gray-700"> Role: System Administrator
                </h2>
                <p class="text-sm font-bold mt-2 text-red-600 max-w-2xl">
                    All changes are final and cannot be reversed automatically. </p>
            </div>
        </div>

        <div class="mb-8">
            @livewire('user-management')
        </div>

        @foreach ($users as $user)
            @include('custom.edit-user-form', $user)
        @endforeach
    </div>
@endsection
