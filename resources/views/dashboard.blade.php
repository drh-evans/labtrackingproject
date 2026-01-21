@extends('layouts.labapp')
@section('title')
    Welcome
@endsection
@section('content')
    <div
        class="class= bg-no-repeat bg-cover bg-center bg-fixed flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <span
            class="bg-gradient-to-r from-yellow-600 via-yellow-400 to-yellow-200 bg-clip-text text-transparent text-6xl font-extrabold">
            Welcome to Labtracker, {{ auth()->user()->name }}!</span>
    </div>
@endsection
