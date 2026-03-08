<?php

namespace App\Policies;

use App\Models\Queue;
use App\Models\User;

class QueuePolicy
{
    public function accessAdmin(User $user, Queue $queue): bool
    {
        return $user->hasRole('Lecturer') || $user->hasRole('Admin') || $user->hasRole('Teaching Assistant');
    }
}
