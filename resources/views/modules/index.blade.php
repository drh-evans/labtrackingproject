@extends('layouts.labapp')

@section('content')
    <div>
        @foreach ($modules as $module)
            <p>{{ $module->module_code }} - {{ $module->module_name }}</p>
        @endforeach
    </div>
@endsection
