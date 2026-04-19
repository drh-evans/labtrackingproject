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
        Gate::authorize('access-admin');
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

    public function users(){
        Gate::authorize('access-admin');
        $users = User::with('roles')->get();
        return view('admin.users', ['users'=>$users]);
    }

    public function userUpdate(Request $request, User $user) {
        Gate::authorize('access-admin');

        $validatedData = $request->validateWithBag('userEdit' . $user->id,[
            'name' => 'required|string|max:50',
            'email'=> 'required|string|unique:users,email,' . $user->id,
            'role'=>'required|integer|min:1|max:3',
        ]);
     
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->save();

        // replace old role with the new one in pivot
        $user->roles()->sync([$validatedData['role']]);

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }
}
