<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Auth;

use App\Models\QueueEntry;
use App\Models\Queue;
use App\Models\Module;

class QueueController extends Controller
{
    public function show(Module $module){

        Gate::authorize('accessQueue', $module);

        $queue = $module->queue;
        $labTasks = $module->labTasks()->orderBy('task_name')->get();

        return(view('queues.show', ['queue'=>$queue, 'module'=>$module, 'labTasks'=>$labTasks]));
    }

    public function joinQueue(Request $request, Module $module) 
    {
        Gate::authorize('accessQueue', $module);
        Gate::authorize('join-queue');
        $queue = $module->queue;
        $validatedData = $request->validateWithBag('joinQueue'.$queue->id,[
            'task' => 'required|string|max:150',
            'location' => 'required|string|max:150',
            'assistanceType'=> 'required|string|max:150',
            'information'=> 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        if($this->isInQueue()) {
            return redirect()->back()->with('error', 'Already a member of another queue!');
        }

        // find out what position this user is going to join at
        $nextPosition = QueueEntry::where('queue_id', $queue->id)
            ->whereNull('left_at')
            ->max('queue_position') + 1;

        QueueEntry::create([
            'queue_id' => $queue->id,
            'student_id' => $user->id,
            // if there's a next position set it to it otherwise set to 1

            'task' => $validatedData['task'],
            'location' => $validatedData['location'],
            'assistance_type'=> $validatedData['assistanceType'],
            'information'=> $validatedData['information'],
            'queue_position' => $nextPosition ?: 1,
            'joined_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Successfully joined the queue!');
    }

    // helper to see if they've joined a queue already
    private function isInQueue() : bool {
        $isInQueue = QueueEntry::where('student_id', Auth::id())
        ->whereNull('left_at')
        ->exists();
        return $isInQueue;
    }
}
