@extends('layouts.labapp')
@section('title', 'Welcome')

@section('content')
    <div class="max-w-8xl mx-auto px-6 lg:px-8 mt-6 ">
        <div class="bg-gray-50 p-6 m-10 rounded-lg shadow-lg border border-gray-200">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10">

                <h1 class="text-2xl md:text-3xl font-semibold">
                    Welcome to lab tracker {{ auth()->user()->name }}!
                </h1>
                <h2 class="text-md md:text-xl text-gray-700"> Role:
                    @if (auth()->user()->hasRole('Admin'))
                        System Administrator
                    @elseif (auth()->user()->hasRole('Lecturer'))
                        Lecturer
                    @elseif (auth()->user()->hasRole('Teaching Assistant'))
                        Teaching Assistant
                    @elseif (auth()->user()->hasRole('Student'))
                        Student
                    @endif
                </h2>

                <p class="mt-2 text-gray-600 max-w-2xl">
                    Your home for lab sessions at Swansea University, whether its getting help or staying on track with your
                    work.
                </p>
            </div>
        </div>
    </div>
@endsection
