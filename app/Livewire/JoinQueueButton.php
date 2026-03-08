<?php

namespace App\Livewire;

use Auth;
use Livewire\Component;
use App\Models\Module;
use App\Models\Queue;
use App\Models\QueueEntry;

class JoinQueueButton extends Component
{
    public Queue $queue;
    public function mount(Queue $queue) {
        $this->queue = $queue;
    }
    public function joinQueue()
    {
        $user = Auth::user();

        $nextPosition = QueueEntry::where('queue_id', $this->queue->id)
            ->max('queue_position') + 1;

        QueueEntry::create([
            'queue_id' => $this->queue->id,
            'student_id' => $user->id,
            'queue_position' => $nextPosition ?: 1,
            'joined_at' => now(),
        ]);
    }

    public function leaveQueue()
    {
        $user = Auth::user();
        $queueEntry = QueueEntry::where('queue_id', $this->queue->id)
            ->where('student_id', $user->id)->first();
            
            if ($queueEntry)
                {
                $queueEntry->delete();
                }

        $this->reorderPositions();
    }

    public function isUserInQueue()
    {
        return QueueEntry::where('queue_id', $this->queue->id)
            ->where('student_id', Auth::id())
            ->exists();
    }

    protected function reorderPositions()
    {
        $entries = QueueEntry::where('queue_id', $this->queue->id)
            ->orderBy('joined_at')
            ->get();

        foreach ($entries as $index => $entry) {
            $entry->update(['queue_position' => $index + 1]);
        }
    }
    public function render()
    {
         return view('livewire.join-queue-button', [
            'inQueue' => $this->isUserInQueue(),
        ]);
    }
}
