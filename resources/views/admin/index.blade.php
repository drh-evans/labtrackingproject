@extends('layouts.labapp')

@section('title', 'Admin Centre')

@section('content')
    <a href="{{ route('modules.create') }}"> Create a Module </a>
    <p> User Management</p>
@endsection
