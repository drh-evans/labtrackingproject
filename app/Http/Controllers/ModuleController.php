<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Module;
use App\Models\LabTaskUser;
use App\Models\User;
use Storage;

class ModuleController extends Controller
{
    public function index() {
        Gate::authorize('viewAny', Module::class);

        $user = auth()->user();

        $modules = $user->hasRole('Admin') 
        ? Module::all() 
        : $user->modules()->orderBy('module_code')->get();

        return view('modules.index', compact('modules'));
    }

    public function show(Module $module) {
        Gate::authorize('view', $module);

        // all existing lab tasks
        $labTasks = $module->labTasks()
        ->orderBy('fm_due_date')
        ->get();

        // current users id
        $userId = auth()->id();

        // sign off records
        $signOffs = LabTaskUser::where('user_id', $userId)
        ->pluck('marks_awarded', 'lab_task_id');

        return view('modules.show', ['module'=>$module, 'labTasks'=>$labTasks, 'signOffs'=>$signOffs,]);
    }

    public function create() {
        Gate::authorize('create', Module::class);
        return view('modules.create');
    }

    public function store(Request $request) {

        Gate::authorize('create', Module::class);

        $validatedData = $request->validate([
            'module_code'=>'required|string|max:10|unique:modules,module_code',
            'module_name'=>'required|string|max:30',
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        $module = new Module;
        $module->module_code = $validatedData['module_code'];
        $module->module_name = $validatedData['module_name'];

        if ($request->hasFile('image')) {
            $imageUrl = $request->file('image')->store('modules','public');
            $module->bg_image_url = $imageUrl;
        }

        $module->save();

        return redirect()
        ->route('admin.dashboard')
        ->with('success', 'Module created!');
    }

    public function edit(Module $module) {
        Gate::authorize('edit', Module::class);
        return view('modules.edit', ['module'=>$module]);
    }

    public function update(Request $request, Module $module) {
        Gate::authorize('update', $module);

        $validatedData = $request->validate([
            'module_code'=>'required|string|max:10|unique:modules,module_code',
            'module_name'=>'required|string|max:30',
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        $module->module_code = $validatedData['module_code'];
        $module->module_name = $validatedData['module_name'];

        if ($request->hasFile('image')) {
            if ($module->bg_image_url) {
                Storage::delete('public/' . $module->bg_image_url);
            }
            $imageUrl = $request->file('image')->store('posts', 'public');;
            $module->bg_image_url = $imageUrl;
        }
        $module->save();

        return redirect()->route('admin.dashboard')->with('success', 'Module updated');
    }

    public function destroy(Module $module) {
        Gate::authorize('delete', $module);
        if($module->bg_image_url) {
            Storage::delete('public/' . $module->bg_image_url);
        }
        $module->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Module deleted.');
    }

    public function enrolment(Module $module) {
        Gate::authorize('enrol', $module);
        return view('modules.enrolment', ['module'=>$module,]);
    }

    public function search(Module $module) {
        Gate::authorize('signOff', $module);
        return view('modules.search', ['module'=>$module,]);
    }

    public function signOff(Module $module, User $student) {
        Gate::authorize('signOff', $module);
        return view('modules.signoff', ['module'=>$module,'student'=>$student]);
    }
}
