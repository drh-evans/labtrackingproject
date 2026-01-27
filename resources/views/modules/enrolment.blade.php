@extends('layouts.labapp')

@section('title', 'Module Enrolment')

@section('content')
    @livewire('module-enrolment-manager', ['module' => $module])
@endsection
