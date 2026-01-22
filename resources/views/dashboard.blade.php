@extends('layouts.labapp')
@section('title', 'Welcome')

@section('content')
    <div
        class="class= bg-no-repeat bg-cover bg-center bg-fixed flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <span>
            Welcome to Labtracker, {{ auth()->user()->name }}!</span>
    </div>
@endsection
