@extends('layouts.labapp')

@section('title', 'Modules')

@section('content')
    <div class="mt-4 ml-4">
        <a href="{{ route('dashboard') }}">
            <x-tertiary-button>{{ __('return') }}</x-tertiary-button>
        </a>

        @foreach ($modulesByYear as $moduleYear => $modules)
            <h1 class="text-2xl font-bold ml-6 mt-4">Year {{ $moduleYear }} Modules:</h1>
            <!-- credit: https://flowbite.com/docs/components/card/-->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-8 mt-6 ml-6 mb-8">
                @foreach ($modules as $module)
                    <div class="bg-white block max-w-xs overflow-hidden border border-default rounded-lg shadow-md">
                        <a href="{{ route('modules.show', $module) }}">
                            @if ($module->bg_image_url)
                                <img src="{{ asset('storage/' . $module->bg_image_url) }}" alt="{{ $module->module_name }}"
                                    class='h-56 object-fill'>
                            @else
                                <img src="{{ asset('storage/modules/default.jpg') }}" alt="{{ $module->module_name }}"
                                    class='h-56 object-fill'>
                            @endif
                        </a>
                        <div class="p-6 text-center">
                            <a href="{{ route('modules.show', $module) }}">
                                <h5 class="mt-3 text-2xl font-semibold tracking-tight text-heading hover:text-gray-700">
                                    {{ $module->module_code }} - {{ $module->module_name }} <br>
                                </h5>
                                <p class="mb-6 hover:text-gray-700">
                                    {{ $module->year_taught }}<br>{{ $module->leader?->name ?? 'Leader no longer available' }}
                                </p>
                            </a>
                            <a href="{{ route('modules.show', $module) }}"
                                class="inline-flex items-center text-white bg-blue-800 hover:bg-blue-700 box-border border border-transparent rounded-md hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                                View module
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@endsection
