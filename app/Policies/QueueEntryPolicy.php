<?php

namespace App\Policies;

use App\Models\QueueEntry;
use App\Models\User;

class QueueEntryPolicy
{
    public function removeFromQueue(User $user, QueueEntry $queueEntry): bool
    {
        return $user->hasRole('Lecturer') || $user->hasRole('Teaching Assistant') || $user->hasRole('Admin');
    }
}
