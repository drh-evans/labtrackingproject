<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\LabTask;
use App\Models\LabTaskUser;

class DashboardController extends Controller
{
    public function index() {
        $user = auth()->user();

        $modules = $user->hasRole('Admin') 
        ? Module::all() 
        : $user->modules()->orderBy('module_code')->get();

        // get all lab tasks due soon that the user has not completed
        $tasks = LabTask::with('module')
        ->whereIn('module_id', $modules->pluck('id'))
        ->whereBetween('fm_due_date', [now(), Carbon::now()->addWeeks(2)])
        ->whereDoesntHave('users', function($query) use ($user) {$query->where('user_id', $user->id);})
        ->orderBy('fm_due_date')
        ->get();

        if ($user->hasRole("Student")) {
            return view('dashboards.student', compact('modules', 'tasks'));
        }
        return view('dashboards.staff', compact('modules'));
    }
}
