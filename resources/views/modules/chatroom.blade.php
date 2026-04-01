@extends('layouts.labapp')

@section('title', 'Chatroom')

@section('content')
    <div class="max-w-4xl mx-auto py-6">
        <livewire:livewire-chatroom :chatroom="$chatroom" />
    </div>
@endsection
