<?php

namespace App\Livewire;

use App\Notifications\SignedOffNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\LabTaskUser;
use Livewire\Component;
use App\Models\User;
use App\Models\Module;
use App\Models\LabTask;

class SignOffManager extends Component
{
    public Module $module;
    public User $student;

    public function mount(Module $module, User $student)
    {
        $this->module = $module;
        $this->student = $student;
    }

    public function getLabTasksProperty()
    {
        return $this->module->labTasks()
        ->orderBy('fm_due_date')
        ->get();
    }

    public function getSignOffsProperty() 
    {
        return LabTaskUser::where('user_id', $this->student->id)
        ->pluck('marks_awarded', 'lab_task_id');
    }

    public function signOff(int $taskId, string $type)
    {
        Gate::authorize('signOff', $this->module);
        $labTask = LabTask::findOrFail($taskId);

        $marks = $labTask->getMarksType($type);
        
        LabTaskUser::updateOrCreate(
            ['lab_task_id'=>$labTask->id,
            'user_id'=>$this->student->id],
            ['marks_awarded'=>$marks,
            'signed_off_by'=>Auth::id(),
            'completed_at'=>now()]
        );

        $this->student->notify(new SignedOffNotification($labTask, $this->module));
    }

    public function removeSignOff(int $taskId) {
        Gate::authorize('signOff', $this->module);

        LabTaskUser::where('lab_task_id', $taskId)
        ->where('user_id', $this->student->id)
        ->delete();
    }

    public function render()
    {
        return view('livewire.sign-off-manager', [
            'labTasks'=>$this->labTasks, 
            'signOffs'=>$this->signOffs
        ]);
    }
}
