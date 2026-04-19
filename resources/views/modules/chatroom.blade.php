@extends('layouts.labapp')

@section('title', 'Chatroom')

@section('content')

    <div class="p-4">
        <div class="bg-gray-50 p-6 m-10 rounded-lg shadow-lg border border-gray-200">
            <a href="{{ route('modules.show', $chatroom->module->id) }}">
                <x-tertiary-button>{{ __('return') }}</x-tertiary-button>
            </a>

            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10">
                <h1 class="text-2xl md:text-3xl font-semibold">
                    Chatroom for {{ $chatroom->module->module_code }}, {{ $chatroom->module->module_name }}
                </h1>
                <h2 class="text-md md:text-xl text-gray-700"> Module Coordinator:
                    @if ($chatroom->module->leader)
                        {{ $chatroom->module->leader->name }} ({{ $chatroom->module->leader->email }})
                    @else
                        Staff member not found.
                    @endif
                </h2>
                <p class="mt-1 text-xs font-semibold text-red-600 max-w-4xl">
                    Whilst chats are anonymous to other students, lecturers and
                    system administrators
                    still maintain full access. <br> If you feel inapproporiate content has been posted please report this
                    to lab
                    staff. </p>
                @can('access-analytics', $chatroom->module)
                    <div class="flex items-center justify-center">
                        <a href="{{ route('chatroom.analytics', $chatroom->module->id) }}">
                            <x-tertiary-button>{{ __('analytics') }}</x-tertiary-button>
                        </a>
                    </div>
                @endcan
            </div>
        </div>
        <div class="m-10 py-6">
            <livewire:livewire-chatroom :chatroom="$chatroom" />
        </div>
    </div>
@endsection
