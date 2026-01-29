@extends('layouts.labapp')
@section('title')
    {{ $module->module_code }} - {{ $module->module_name }}
@endsection

@section('content')
    <h1> {{ $student->name }}, {{ $student->email }} </h1>
    @livewire('sign-off-manager', ['module' => $module, 'student' => $student])
@endsection
