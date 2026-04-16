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

        // find out what position this user is going to join at
        $nextPosition = QueueEntry::where('queue_id', $this->queue->id)
            ->max('queue_position') + 1;

        QueueEntry::create([
            'queue_id' => $this->queue->id,
            'student_id' => $user->id,
            // if there's a next position set it to it otherwise set to 1
            'queue_position' => $nextPosition ?: 1,
            'joined_at' => now(),
        ]);

        // previously had lag issues, local one updates instantly, main one updates for other users
        $this->dispatch("queue-updated-locally");
    }

    public function leaveQueue()
    {
        $user = Auth::user();
        $queueEntry = QueueEntry::where('queue_id', $this->queue->id)
            ->where('student_id', $user->id)
            ->whereNull('left_at')
            ->first();
        
        if (!$queueEntry) {
            return;
        }

        $queueEntry->update([
            // they have now left so make them leave
            // queues are only erased when the queue is closed for use
            'left_at'=>now(),
            'queue_position'=> null,
        ]);
        $this->dispatch(event: "queue-updated-locally");
        $queueEntry->queue->reorderPositions();
    }

    // check if the current user has joined the queue already
    public function isUserInQueue()
    {
        return QueueEntry::where('queue_id', $this->queue->id)
            ->whereNull('left_at')
            ->where('student_id', Auth::id())
            ->exists();
    }

    public function getListeners()
    {
        return ["queue-updated-locally" => '$refresh', "echo-private:queues.{$this->queue->id},.queue-updated" => '$refresh',];
    }

    // return the view with inQueue which shows if the current user is in the queue - this is to control if join or leave is shown
    public function render()
    {
         return view('livewire.join-queue-button', [
            'inQueue' => $this->isUserInQueue(),
        ]);
    }
}
