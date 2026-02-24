<div>
    @if ($module->queue->is_open)
        <a href="{{ route('module.queue', $module) }}"> <x-secondary-button> View Queue </x-secondary-button></a>
    @endif
</div>
