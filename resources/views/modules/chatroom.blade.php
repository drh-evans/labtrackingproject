@extends('layouts.labapp')

@section('title', 'Chatroom')

@section('content')

    <div class="p-4">
        <div class="mb-4 ml-4">
            <h1 class="text-2xl font-semibold">Chatroom for {{ $module->module_code }}, {{ $module->module_name }}</h1>
        </div>
        <div class="max-w-4xl mx-auto py-6">
            <livewire:livewire-chatroom :chatroom="$chatroom" />
        </div>
    </div>
@endsection
