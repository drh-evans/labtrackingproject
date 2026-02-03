@extends('layouts.labapp')
@section('title')
    {{ $module->module_code }} - {{ $module->module_name }}
@endsection

@section('content')
    <div class="flex flex-col items-center justify-center py-10 gap-3">
        <div class="max-w-4xl bg-slate-800 rounded-lg p-5">
            <p class="text-white font-sm">
                You may choose to sign-off a user for half marks by clicking the "Half" button or for full marks
                by clicking the "Full" button.
            </p>

            <p class="text-red-600 font-semibold">
                Do not sign-off users past the sign-off date unless they have received extenuating circumstances.
            </p>
        </div>
        @livewire('sign-off-manager', ['module' => $module, 'student' => $student])
    </div>
@endsection
