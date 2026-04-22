<?php

namespace App\Console\Commands;

use App\Notifications\WeeklyReportNotification;
use Illuminate\Console\Command;
use App\Models\Module;
use App\Models\LabTask;

class MissedConsecutiveLabsCommand extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'app:missed-consecutive-labs-command';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Find students who have missed 2 or more consecutive lab deadlines and alert the module coordinator of this.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // only the current modules to prevent looking at all modules from history and spamming
        $modules = Module::with(['labTasks.users','users','leader'])->where('year_taught',now()->year)->get();
        foreach($modules as $module) {
            // check if there exists a leader, otherwise no point continuing as emailing isn't possible
            if(!$module->leader) {
                continue;
            }

            $students = $module->users->filter(fn($user)=>$user->hasRole('Student'));
            $missedStudents = [];

            foreach($students as $student) {
                // consider missed tasks to be those who are beyond full marks deadline
                // email would be to help them to get signed off for half marks
                $missedTasks = $module->labTasks
                               ->filter(fn($task)=> $task->fm_due_date->lt(now()->startOfDay()))
                               ->filter(fn($task)=> !$task->users->contains($student->id))
                               ->sortBy('fm_due_date')
                               ->values();

                $consecutiveMissed = $this->hasMissedConsecutiveLabs($module, $missedTasks);

                if ($consecutiveMissed) {
                    $missedStudents[] = [
                        'student'=>$student,'missedTasks'=>$missedTasks
                        ];
                }
            }

            if(!empty($missedStudents)) {
                $module->leader->notify(new WeeklyReportNotification($module, $missedStudents));
            }
        }
    }

     private function hasMissedConsecutiveLabs($module, $missedTasks) : bool {
        // if there's not even 2 missed tasks this is impossible so return false
        if ($missedTasks->count() < 2) {
            return false;
        }

        // get all tasks to compare with the ones missed
        $allTasks = $module->labTasks
                    ->sortBy('fm_due_date')
                    ->values();

        // loop all existing tasks
        for($i = 0; $i < $allTasks->count() - 1; $i++) {
            $currentLab = $allTasks[$i];
            $nextLab = $allTasks[$i + 1];

            // check if the current and next labs have been missed
            $currentMissed = $missedTasks->contains('id', $currentLab->id);
            $nextMissed = $missedTasks->contains('id', $nextLab->id);

            // if both of these are true then 2 consecutive labs have been missed
            if ($currentMissed && $nextMissed) {
                return true;
            }
        }
        return false;
    }
}
