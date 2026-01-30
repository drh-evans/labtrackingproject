@extends('layouts.labapp')

@section('content')
    <div class="p-5">
        <p>Your modules:</p>
        <div class="grid grid-cols-4 gap-4">
            @foreach ($modules as $module)
                <div>
                    <p>
                        @if ($module->bg_image_url)
                            <img src="{{ asset('storage/' . $module->bg_image_url) }}" alt="{{ $module->module_name }}">
                        @else
                            <img src="{{ asset('storage/modules/default.jpg') }}" alt="{{ $module->module_name }}">
                        @endif
                    </p>

                    <a href="{{ route('modules.show', $module) }}">{{ $module->module_code }} -
                        {{ $module->module_name }}</a>
                    <br>
                </div>
            @endforeach
        </div>
    </div>
@endsection
