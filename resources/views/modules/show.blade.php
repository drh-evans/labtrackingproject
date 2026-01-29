@extends('layouts.labapp')
@section('title')
    {{ $module->module_code }} - {{ $module->module_name }}
@endsection

@section('content')
    @can('create', App\Models\LabTask::class)
        <a href="{{ route('lab-task.create', $module) }}">Create a Lab Task</a>
    @endcan

    @can('access-enrolment', $module)
        <a href="{{ route('module.enrolment', $module) }}"> Enrol Users</a>
    @endcan

    <a href="{{ route('module.search', $module) }}"> Staff </a>
@endsection
