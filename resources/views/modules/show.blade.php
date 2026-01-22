@extends('layouts.labapp')
@section('title')
    {{ $module->module_code }} - {{ $module->module_name }}
@endsection

@section('content')
    @can('create', App\Models\LabTask::class)
        <a href="{{ route('lab-task.create', $module) }}">Create a Lab Task</a>
    @endcan
@endsection
