@extends('layouts.labapp')
@section('title')
    {{ $module->module_code }} - {{ $module->module_name }} - Student Search
@endsection

@section('content')
    <div>
        @include('components.custom.back-button')
        @livewire('student-search-manager', ['module' => $module])
    </div>
@endsection
