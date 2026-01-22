@extends('layouts.labapp')

@section('content')
    <div>
        @foreach ($modules as $module)
            <a href="{{ route('modules.show', $module) }}">{{ $module->module_code }} - {{ $module->module_name }}</a>
            <br>
        @endforeach
    </div>
@endsection
