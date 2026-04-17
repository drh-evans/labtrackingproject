@extends('layouts.labapp')
@section('title')
    Queue for {{ $module->module_code }} - {{ $module->module_name }}
@endsection

@section('content')
    <div>
        <div class="py-4">
            <livewire:queue-viewer :queue="$queue" />
        </div>
    </div>

    @include('components.custom.join-queue-modal', [
        'module' => $module,
        'queue' => $queue,
        'labTasks' => $labTasks,
    ])
@endsection
