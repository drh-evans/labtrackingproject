<?php

namespace App\Policies;

use App\Models\Module;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ModulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Module $module): bool
    {
        return $module->users()->where('users.id', $user->id)->exists()
        || $user->hasRole('Admin');
    }

    public function enrol(User $user, Module $module): bool
    {
        return $user->hasRole('Lecturer') || $user->hasRole('Admin');
    }

    public function signOff(User $user, Module $module): bool
    {
        return $user->hasRole('Lecturer') || $user->hasRole('Teaching Assistant') || $user->hasRole('Admin');
    }
    
    public function openQueue(User $user, Module $module): bool
    {
        return $user->hasRole('Lecturer') || $user->hasRole('Admin');
    }

    public function accessQueue(User $user, Module $module): bool
    {
        return $module->queue->is_open;
    }

    public function accessAnalytics(User $user, Module $module): bool
    {
        $isAdmin = $user->hasRole('Admin');
        $isEnroled = $module->users()->where('users.id', $user->id)->exists();
        $isStaff = $user->hasRole('Teaching Assistant') || $user->hasRole('Lecturer');

        return ($isEnroled && $isStaff) || $isAdmin;
    }

    public function accessChatroom(User $user, Module $module): bool
    {
        return $module->chatroom->is_open;
    }
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Module $module): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Module $module): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Module $module): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Module $module): bool
    {
        return $user->hasRole('Admin');
    }
}
