@extends('layouts.labapp')
@section('title')
    Queue for {{ $module->module_code }} - {{ $module->module_name }}
@endsection

@section('content')
    @include('components.custom.back-button')
    @livewire('join-queue-button', ['queue' => $queue])
@endsection
