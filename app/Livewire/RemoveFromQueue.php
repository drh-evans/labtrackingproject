<?php

namespace App\Livewire;

use App\Models\QueueEntry;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class RemoveFromQueue extends Component
{
    public QueueEntry $queueEntry;
    public function mount(QueueEntry $queueEntry) 
    {
        $this->queueEntry = $queueEntry;
    }

    public function remove()
    {
        Gate::authorize('removeFromQueue', $this->queueEntry);
        $this->queueEntry->delete();
        $this->dispatch(event: "queue-updated-locally");
        $this->queueEntry->queue->reorderPositions();
    }

    public function render()
    {
        return view('livewire.remove-from-queue');
    }
}
