<?php

namespace App\Livewire;

use App\Models\Queue;

use App\Models\QueueEntry;
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
        $entries = $this->queue
        ->queueEntries()
        ->with('student')
        ->whereNull('left_at')
        ->orderBy('queue_position')
        ->get();
        return $entries;
    }

    private function getAverageWait()
    {
        // get the finished queue entries
        $leftEntries = $this->queue->queueEntries()
        ->whereNotNull('left_at')
        ->get();

        if($leftEntries->isEmpty()) {
            return '00:00';
        }

        $averageWait = $leftEntries->avg(function($entry){
            $difference = $entry->left_at->diffInSeconds($entry->joined_at, true);
            return $difference;
        });

        return gmdate('i:s', $averageWait);
    }

    public function getListeners()
    {
        return ["queue-updated-locally"=>'$refresh', "echo-private:queues.{$this->queue->id},.queue-updated" => '$refresh',];
    }

    public function render()
    {
        // get the current users queue entry, null if doesn't exist
        $currentEntry = $this->queue->queueEntries()
        ->where('student_id', auth()->id())
        ->whereNull('left_at')
        ->first();

        // get the time joined for the current queue entry if it exists
        $joinedAt = $currentEntry?->joined_at?->timestamp;

        return view('livewire.queue-viewer', ['entries'=>$this->getQueueEntries(), 'averageWait'=>$this->getAverageWait(), 'joinedAt'=>$joinedAt]);
    }
}
