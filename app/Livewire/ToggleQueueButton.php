<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use App\Models\Module;

class ToggleQueueButton extends Component
{
    public Module $module;

    public function toggleQueue() 
    {
        Gate::authorize('openQueue', $this->module);
        $queue = $this->module->queue;
        $queue->is_open = !$queue->is_open;
        $queue->save();
    }
    public function render()
    {
        return view('livewire.toggle-queue-button');
    }
}
