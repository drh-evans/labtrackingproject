<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAll(User $user) {
        return $user->hasRole('Admin');
    }

    public function update(User $user, User $targetUser) {
        return $user->hasRole('Admin');
    }

    public function delete(User $user, User $targetUser) {
        return $user->hasRole('Admin') && $user->id !== $targetUser->id;
    }
}
