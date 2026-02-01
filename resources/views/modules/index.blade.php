@extends('layouts.labapp')

@section('title', 'Your modules:')

@section('content')
    <div class="p-5">
        <!-- credit: https://flowbite.com/docs/components/card/-->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-8">
            @foreach ($modules as $module)
                <div
                    class="bg-neutral-primary-soft block max-w-xs overflow-hidden border border-default rounded-lg shadow-sm">
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
                            <h5 class="mt-3 mb-6 text-2xl font-semibold tracking-tight text-heading">
                                {{ $module->module_code }} - {{ $module->module_name }}
                            </h5>
                        </a>
                        <a href="{{ route('modules.show', $module) }}"
                            class="inline-flex items-center text-white bg-blue-800 hover:bg-blue-700 box-border border border-transparent rounded-md hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                            View module
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
