@extends('layouts.labapp')
@section('title')
    {{ $module->module_code }} - {{ $module->module_name }} - Student Search
@endsection

@section('content')
    @livewire('student-search-manager', ['module' => $module])
@endsection
