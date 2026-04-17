@extends('layouts.labapp')
@section('title')
    {{ $module->module_code }} - {{ $module->module_name }}
@endsection

@section('content')
    <div class="bg-gray-50 p-6 m-10 rounded-lg shadow-lg border border-gray-200">
        <a href="{{ route('module.search', $module->id) }}">
            <x-tertiary-button>{{ __('return') }}</x-tertiary-button>
        </a>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10">
            <h1 class="text-2xl md:text-3xl font-semibold">
                Showing {{ $student->name }}'s ({{ $student->email }}) progress for {{ $module->module_code }}
            </h1>
            <h2 class="text-md md:text-xl text-gray-700"> Module Coordinator:
                @if ($module->leader)
                    {{ $module->leader->name }} ({{ $module->leader->email }})
                @else
                    Staff member not found.
                @endif
            </h2>
            <p class="mt-1 text-gray-600 max-w-2xl">
                Teaching Year: {{ $module->year_taught }}
            </p>

            <p class="mt-2 text-gray-600 font-medium max-w-5xl">
                You may choose to sign-off a user for half marks by clicking the "Half" button or for full marks
                by clicking the "Full" button.
            </p>


            <p class="mt-1 text-red-600 font-semibold max-w-3xl">
                Do not sign-off users past the sign-off date unless they have received extenuating circumstances.
            </p>
        </div>
    </div>

    <div class="flex flex-col items-center justify-center py-10 gap-3">
        @livewire('sign-off-manager', ['module' => $module, 'student' => $student])
    </div>
@endsection
