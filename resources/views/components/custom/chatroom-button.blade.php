<div>
    @if ($module->chatroom->is_open)
        <a href="{{ route('chatroom.show', $module) }}"> <x-secondary-button> {{ __('Chatroom') }}
            </x-secondary-button></a>
    @endif
</div>
