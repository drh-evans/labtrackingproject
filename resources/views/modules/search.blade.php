@extends('layouts.labapp')
@section('title')
    {{ $module->module_code }} - {{ $module->module_name }} - Student Search
@endsection

@section('content')
    <div>
        <div class="bg-gray-50 p-6 m-10 rounded-lg shadow-lg border border-gray-200">
            <a href="{{ route('modules.show', $module->id) }}">
                <x-tertiary-button>{{ __('return') }}</x-tertiary-button>
            </a>

            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10">
                <h1 class="text-2xl md:text-3xl font-semibold">
                    Users on {{ $module->module_code }}, {{ $module->module_name }}
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
            </div>
        </div>
        @livewire('student-search-manager', ['module' => $module])
    </div>
@endsection
