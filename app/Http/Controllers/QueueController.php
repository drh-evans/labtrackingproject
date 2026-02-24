<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Queue;
use App\Models\Module;

class QueueController extends Controller
{
    public function show(Module $module){

        Gate::authorize('accessQueue', $module);

        $queue = $module->queue;

        return(view('queues.show', ['queue'=>$queue, 'module'=>$module]));
    }
}
