<?php

namespace App\Http\Controllers;

use App\Models\QueueEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\LabTask;
use App\Models\LabTaskUser;

class DashboardController extends Controller
{
    public function index() {
        $user = auth()->user();

        // redirect admins to the admin dashboard 
        if($user->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        }

        $modules = $user->modules()->orderBy('module_code')->get();

        if ($user->hasRole("Student")) {
            $tasks = $this->getUpcomingDeadlines($modules, $user);
            $missedTasks = $this->getMissedDeadlines($modules, $user);
            $completedTasks = $this->getRecentlyCompleted($modules, $user);

            $openModules = $this->getOpenModules($modules);
            $recentModules = $this->getRecentModules($modules);

            $percentageTasksCompleted = $this->getCompletion($modules, $user);
            $averageMarks = $this->getAverageGrade($modules, $user);
            $currentEntry = $this->getCurrentQueueEntry($user);
            return view('dashboards.student', compact(
                'modules', 
                'tasks', 
                'missedTasks', 
                'completedTasks',
                'openModules',
                'recentModules',
                'percentageTasksCompleted',
                'averageMarks',
                'currentEntry'
                ));
        }
        return view('dashboards.staff', compact('modules'));
    }

    private function getUpcomingDeadlines($modules, $user) {
        // get 5 soonest lab tasks due that the user has not completed
        $tasks = LabTask::with('module')
        ->whereIn('module_id', $modules->pluck('id'))
        ->whereBetween('fm_due_date', [now()->startOfDay(), Carbon::now()->addWeeks(2)->endOfDay()])
        ->whereDoesntHave('users', function($query) use ($user) {$query->where('user_id', $user->id);})
        ->orderBy('fm_due_date')
        ->take(5)
        ->get();

        return $tasks;
    }

    private function getMissedDeadlines($modules, $user) {
        // get up to 5 lab tasks the user has missed
        $missed = LabTask::with('module')
        ->whereIn('module_id', $modules->pluck('id'))
        ->where('fm_due_date', '<', now()->startOfDay())
        ->whereDoesntHave('users', function($query) use ($user) {$query->where('user_id', $user->id);})
        ->orderBy('fm_due_date')
        ->take(5)
        ->get();

        return $missed;
    }

    private function getRecentlyCompleted($modules, $user) {
        // get up the 5 most recently completed tasks
        $completed = LabTask::with('module')
        ->whereIn('module_id', $modules->pluck('id'))
        ->whereHas('users', function($query) use ($user) {$query->where('user_id', $user->id);})
        ->orderBy('fm_due_date')
        ->take(5)
        ->get();

        return $completed;
    }

    private function getOpenModules($modules) {
        $open = $modules->filter(fn($module) => $module->queue->is_open);
        return $open;
    }

    private function getRecentModules($modules) {
        $currentYear = now()->year;
        // minimum and maximum year for recent, 1 year ago and 1 year in advance
        $minimumYear = $currentYear - 1;
        $maximumYear = $currentYear + 1;

        $recentModules = $modules->filter(fn($module) => ($module->year_taught >= $minimumYear) && ($module->year_taught <= $maximumYear))->sortBy('module_code');
        return $recentModules; 
    }

    private function getCompletion($modules, $user) {
        $numberOfTasks = LabTask::whereIn('module_id', $modules->pluck('id'))->count();

        if ($numberOfTasks === 0) {
            return 0;
        }

        $numberOfCompleted = LabTask::with('module')
        ->whereIn('module_id', $modules->pluck('id'))
        ->whereHas('users', function($query) use ($user) {$query->where('user_id', $user->id);})
        ->count();

        $percentageCompleted = round(($numberOfCompleted / $numberOfTasks) * 100, 1);
        return $percentageCompleted;
    }

    private function getAverageGrade($modules, $user) {
        $maxMarks = 0;
        $earnedMarks = 0;

        foreach ($modules as $module) {
            $labTasks = $module->labTasks;
            foreach($labTasks as $labTask) {
                $maxMarks += $labTask->total_points;
                $isCompleted = $labTask->completions
                ->where('user_id', $user->id)
                ->first();

                if ($isCompleted) {
                    $earnedMarks += $isCompleted->marks_awarded;
                }
            }
        }
         if ($maxMarks === 0) {
                return 0;
        }
        $percentageEarned = round(($earnedMarks / $maxMarks) * 100,1);
        return $percentageEarned;
    }

    private function getCurrentQueueEntry($user) {
        $currentEntry = QueueEntry::with('queue.module')
        ->where('student_id', $user->id)
        ->whereNull('left_at')
        ->first();

        return $currentEntry;
    }
}
