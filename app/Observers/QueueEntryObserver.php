<?php

namespace App\Observers;

use App\Models\Module;
use App\Models\QueueEntry;
use App\Models\Queue;
use App\Events\QueueUpdated;

/**
 * changed to broadcast()->toOthers() as previously there was lag on my clients screen between the buttons
 * and the table. Now dispatching another local event for livewire on my clients screen to update instantly,
 * and dispatching these events to update other clients screen.
 */
class QueueEntryObserver
{
    /**
     * Handle the Module "created" event.
     */
    public function created(QueueEntry $queueEntry): void
    {
        broadcast(new QueueUpdated(entry: $queueEntry))->toOthers();
        // QueueUpdated::dispatch($queueEntry);
    }

    /**
     * Handle the Module "deleted" event.
     */
    public function deleting(QueueEntry $queueEntry): void
    {
        broadcast(new QueueUpdated(entry: $queueEntry))->toOthers();
        // QueueUpdated::dispatch($queueEntry);
    }
}
