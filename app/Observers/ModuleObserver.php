<?php

namespace App\Observers;

use App\Models\Module;

class ModuleObserver
{
    /**
     * Handle the Module "created" event.
     */
    public function created(Module $module): void
    {
        $module->queue()->create([
            'is_open' => false
        ]);
    }

    /**
     * Handle the Module "updated" event.
     */
    public function updated(Module $module): void
    {
        //
    }

    /**
     * Handle the Module "deleted" event.
     */
    public function deleted(Module $module): void
    {
        //
    }

    /**
     * Handle the Module "restored" event.
     */
    public function restored(Module $module): void
    {
        //
    }

    /**
     * Handle the Module "force deleted" event.
     */
    public function forceDeleted(Module $module): void
    {
        //
    }
}
