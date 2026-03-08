<?php

namespace App\Observers;

use App\Models\Module;
use App\Models\QueueEntry;
use App\Models\Queue;
use App\Events\QueueUpdated;


class QueueEntryObserver
{
        /**
     * Handle the Module "created" event.
     */
    public function created(QueueEntry $queueEntry): void
    {
        QueueUpdated::dispatch($queueEntry);
    }

    /**
     * Handle the Module "deleted" event.
     */
    public function deleting(QueueEntry $queueEntry): void
    {
        QueueUpdated::dispatch($queueEntry);
    }
}
