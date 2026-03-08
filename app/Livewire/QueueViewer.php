<?php

namespace App\Livewire;

use App\Models\Queue;

use Livewire\Component;

class QueueViewer extends Component
{
    public Queue $queue;

    public function mount(Queue $queue) 
    {
        $this->queue = $queue;
    }

    private function getQueueEntries()
    {
        $entries = $this->queue->queueEntries()->with('student')->orderBy('joined_at')->get();
        return $entries;
    }

    public function getListeners()
    {
        return ["echo-private:queues.{$this->queue->id},.queue-updated" => '$refresh',];
    }

    public function render()
    {
        return view('livewire.queue-viewer', ['entries'=>$this->getQueueEntries()]);
    }
}
