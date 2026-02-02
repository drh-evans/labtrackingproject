@extends('layouts.labapp')

@section('title', 'Module Enrolment')

@section('content')
    @include('components.custom.back-button')
    @livewire('module-enrolment-manager', ['module' => $module])
@endsection
