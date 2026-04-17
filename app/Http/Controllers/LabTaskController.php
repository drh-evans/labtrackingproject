<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Module;
use App\Models\LabTask;
use App\Models\LabTaskUser;


class LabTaskController extends Controller
{
    public function create(Module $module) {
        Gate::authorize('create', LabTask::class);
        return view('lab-tasks.create', ['module'=>$module,]);
    }

    public function store(Request $request, Module $module) {
        Gate::authorize('create', LabTask::class);

        $validatedData = $request->validate([
            'task_name' => 'required|string|max:100',
            'description'=> 'nullable|string|max:500',
            'total_points'=>'required|numeric|min:0|max:10',
            'fm_due_date'=>'required|date',
            'hm_due_date'=>'required|date|after:due_date',
        ]);

        $labTask = new LabTask();
        $labTask->module_id = $module->id;
        $labTask->created_by = auth()->id();
        $labTask->task_name = $validatedData['task_name'];
        $labTask->description = $validatedData['description'];
        $labTask->total_points = $validatedData['total_points'];
        $labTask->fm_due_date = $validatedData['fm_due_date'];
        $labTask->hm_due_date = $validatedData['hm_due_date'];
        $labTask->save();

        return redirect()
        ->route('modules.show', $module)
        ->with('success', 'Lab task created!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LabTask $labTask)
    {
        Gate::authorize('edit', LabTask::class);
        return view('lab-tasks.edit', ['labTask'=>$labTask]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Module $module, LabTask $labTask)
    {
        Gate::authorize('update', $labTask);

        $validatedData = $request->validateWithBag('labTaskEdit'.$labTask->id,[
            'task_name' => 'required|string|max:100',
            'description'=> 'nullable|string|max:500',
            'total_points'=>'required|numeric|min:0|max:10',
            'fm_due_date'=>'required|date',
            'hm_due_date'=>'required|date|after:due_date',
        ]);
     
        $labTask->task_name = $validatedData['task_name'];
        $labTask->description = $validatedData['description'];
        $labTask->total_points = $validatedData['total_points'];
        $labTask->fm_due_date = $validatedData['fm_due_date'];
        $labTask->hm_due_date = $validatedData['hm_due_date'];
        $labTask->save();

        return redirect()->route('modules.show', ['module'=>$module])->with('success', 'Task updated successfully!');
    }

    public function destroy(Module $module, LabTask $labTask) {
        Gate::authorize('delete', $labTask);
        $labTask->delete();
        return redirect()->route('modules.show', ['module'=>$module])->with('success', 'Task deleted successfully!');
    }
}
