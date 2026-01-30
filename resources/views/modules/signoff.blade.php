@extends('layouts.labapp')
@section('title')
    {{ $module->module_code }} - {{ $module->module_name }}
@endsection

@section('content')
    <div class="flex flex-col items-center justify-center py-10 gap-3">
        <p>You may choose to sign-off a user for half marks by clicking the "Half" button or for full marks by clicking the
            "Full" button.
            Do not sign-off users past the sign-off date unless they have received extenuating circumstances.
        </p>
        <h1> Showing {{ $student->name }}'s ({{ $student->email }}) lab progress for {{ $module->module_code }}</h1>
        @livewire('sign-off-manager', ['module' => $module, 'student' => $student])
    </div>
@endsection
