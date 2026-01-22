@extends('layouts.labapp')

@section('title', 'Create a Lab Task')

@section('content')

    @include('components.custom.error')

    <form method="POST" action="{{ route('lab-task.store', $module) }}">
        @csrf

        <p>Task Name:</p>
        <input type="text" name="task_name" placeholder="Input the task name..." value="{{ old('task_name') }}">
        <p>Task Description:</p>
        <input type="text" name="description" placeholder="Input the task description..." value="{{ old('description') }}">
        <p>Total Points (0-10):</p>
        <input type="number" name="total_points" step="0.5" min="0" max="10"
            value="{{ old('total_points') }}">
        <p>Full Marks Due Date:</p>
        <input type="date" name="fm_due_date">
        <p>Half Marks Due Date:</p>
        <input type="date" name="hm_due_date">

        <div>
            <button type="submit" class="rounded-lg bg-green-500 hover:bg-green-400 px-4 py-2"> Create Task</button>
            <a href="{{ route('modules.show', $module) }}" class="rounded-lg bg-gray-500 hover:bg-gray-400 px-4 py-2">
                Cancel
            </a>
        </div>
    </form>
@endsection
