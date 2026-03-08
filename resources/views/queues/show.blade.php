@extends('layouts.labapp')
@section('title')
    Queue for {{ $module->module_code }} - {{ $module->module_name }}
@endsection

@section('content')
    <div class="flex flex-row items-center px-4 py-2">
        @include('components.custom.back-button')
        <p class="p-6 font-bold text-2xl">Queue for {{ $module->module_code }} - {{ $module->module_name }}</p>
    </div>

    <div class="flex flex-col items-center">
        <livewire:join-queue-button :queue="$queue" />
        <div class="py-4">
            <livewire:queue-viewer :queue="$queue" />
        </div>
    </div>
@endsection
