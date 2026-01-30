@extends('layouts.labapp')

@section('content')
    <div class="p-5">
        <p>Your modules:</p>
        <div class="grid grid-cols-4 gap-4">
            @foreach ($modules as $module)
                <a href="{{ route('modules.show', $module) }}">{{ $module->module_code }} - {{ $module->module_name }}</a>
                <br>
            @endforeach
        </div>
    </div>
@endsection
