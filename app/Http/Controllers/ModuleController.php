<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Module;

class ModuleController extends Controller
{
    public function index() {
        Gate::authorize('viewAny', Module::class);

        $user = auth()->user();

        $modules = $user->hasRole('Administator') 
        ? Module::all() 
        : $user->modules()->orderBy('module_code')->get();

        return view('modules.index', compact('modules'));
    }

    public function show(Module $module) {
        //
    }

    public function create(Request $request) {
        // create a module
    }

    public function store() {
        // Store module new module
    }

    public function edit(Module $module) {
        // edit the module lecturer/admin
    }

    public function update(Request $request, Module $module) {
        // update edited module
    }
}
