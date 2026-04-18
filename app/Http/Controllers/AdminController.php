<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Module;
use App\Models\User;
use App\Models\Queue;
use App\Models\LabTask;



class AdminController extends Controller
{
    public function index() {
        // total users by category
        $totalStudents = User::whereHas('roles', fn($user)=> $user->where('name', 'Student'))->count();
        $totalTA = User::whereHas('roles', fn($user)=> $user->where('name', 'Teaching Assistant'))->count();
        $totalLecturers = User::whereHas('roles', fn($user)=> $user->where('name', 'Lecturer'))->count();

        // total modules on the system
        $totalModules = Module::count();
        // total lab tasks on the system
        $totalTasks = LabTask::count();
        // total active queues
        $activeQueues = Queue::where('is_open',true)->count();

        return view('admin.index', compact('totalStudents','totalTA','totalLecturers','totalModules','totalTasks','activeQueues'));
    }
}
