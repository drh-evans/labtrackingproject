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
        $queue = $this->module->queue()->first();
        $queue->is_open = !$queue->is_open;
        $queue->save();

        $chatroom = $this->module->chatroom()->first();
        $chatroom->is_open = !$chatroom->is_open;
        $chatroom->save();

        // fully wipe the queue when the queue is closed so that future averages are not impacted badly
        if(!$queue->is_open) {
            $queue->queueEntries()->delete();
        }

        // redirect after toggling as the main page is not blade so harder to refresh using events
        return redirect()->route('modules.show', $this->module);
    }

    public function render()
    {
        return view('livewire.toggle-queue-button');
    }
}
