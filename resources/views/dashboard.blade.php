@extends('layouts.labapp')
@section('title', 'Welcome')

@section('content')
    <div
        class="class= bg-no-repeat bg-cover bg-center bg-fixed flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <span>
            Welcome to LabTracker, {{ auth()->user()->name }}!
        </span>

        <p>
            LabTracker is your home for lab sessions at Swansea University, be it getting assistance in labs or keeping an
            eye on your progress.
        </p>
    </div>
@endsection
