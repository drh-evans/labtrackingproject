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
        ? Module::with('leader')->orderBy('year_group', 'desc')->orderBy('module_code')->orderBy('year_taught', 'desc')->get()
        : $user->modules()->with('leader')->orderBy('year_group', 'desc')->orderBy('module_code')->orderBy('year_taught','desc')->get();

        $modulesByYear = $modules->groupBy('year_group')->sortKeysDesc();
        return view('modules.index', compact('modulesByYear'));
    }

    public function show(Module $module) {
        Gate::authorize('view', $module);

        // all existing lab tasks
        $labTasks = $module->labTasks()
        ->orderBy('fm_due_date')
        ->get();

        // sum up the total marks for a module i.e. 100
        $totalModuleMarks = $labTasks->sum('total_points');

        // current users id
        $userId = auth()->id();

        // sign off records
        $signOffs = LabTaskUser::where('user_id', $userId)
        ->pluck('marks_awarded', 'lab_task_id');

        $totalEarnedMarks = $signOffs->sum();
        // defaults to 0 if the total marks are 0 as 0/0 results in a warning, otherwise does the %
        $percentageEarned = $totalModuleMarks > 0 ? (($totalEarnedMarks / $totalModuleMarks) * 100) : 0;

        return view('modules.show', ['module'=>$module, 'labTasks'=>$labTasks, 'signOffs'=>$signOffs, 'totalModuleMarks'=>$totalModuleMarks, 'totalEarnedMarks'=>$totalEarnedMarks, 'percentageEarned'=>$percentageEarned,'lecturers'=>$this->getLecturers()]);
    }

    public function create() {
        Gate::authorize('create', Module::class);
        return view('modules.create', ['lecturers'=>$this->getLecturers()]);
    }

    public function store(Request $request) {

        Gate::authorize('create', Module::class);

        $validatedData = $request->validate([
            'module_code'=>'required|string|max:10|unique:modules,module_code',
            'module_name'=>'required|string|max:30',

            'module_leader_id'=>'required|integer|min:0',

            'year_group'=>'required|integer|min:0|max:4',
            'year_taught'=>'required|integer|min:2015|max:2050',

            'image'=>'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $module = new Module;
        $module->module_code = $validatedData['module_code'];
        $module->module_name = $validatedData['module_name'];
        $module->module_leader_id = $validatedData['module_leader_id'];
        $module->year_taught = $validatedData['year_taught'];
        $module->year_group = $validatedData['year_group'];


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
        return view('modules.edit', ['module'=>$module, 'lecturers'=>$this->getLecturers()]);
    }

    public function update(Request $request, Module $module) {
        Gate::authorize('update', $module);

        $validatedData = $request->validateWithBag('moduleEdit' . $module->id,[
            'module_code'=>'required|string|max:10|unique:modules,module_code,'.$module->id,
            'module_name'=>'required|string|max:30',

            'module_leader_id'=>'required|integer|min:0',

            'year_group'=>'required|integer|min:0|max:4',
            'year_taught'=>'required|integer|min:2015|max:2050',

            'image'=>'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $module = new Module;
        $module->module_code = $validatedData['module_code'];
        $module->module_name = $validatedData['module_name'];
        $module->module_leader_id = $validatedData['module_leader_id'];
        $module->year_taught = $validatedData['year_taught'];
        $module->year_group = $validatedData['year_group'];

        if ($request->hasFile('image')) {
            if ($module->bg_image_url) {
                Storage::delete('public/' . $module->bg_image_url);
            }
            $imageUrl = $request->file('image')->store('posts', 'public');;
            $module->bg_image_url = $imageUrl;
        }
        $module->save();

        return redirect()->route('modules.show', ['module'=>$module])->with('success', 'Module updated successfully!');
    }

    public function destroy(Module $module) {
        Gate::authorize('delete', $module);
        if($module->bg_image_url) {
            Storage::delete('public/' . $module->bg_image_url);
        }
        $module->delete();
        return redirect()->route('modules.index')->with('success', 'Module deleted successfully!');
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

    private function getLecturers() {
        $lecturers = User::whereHas('roles', function($query) {
            $query->where('name', 'Lecturer');
        })
        ->orderByDesc('email')
        ->get();
        return $lecturers;
    }
}
