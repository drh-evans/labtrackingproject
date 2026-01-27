<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Module;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class ModuleEnrolmentManager extends Component
{
    public Module $module;
    public $users = [];
    public $enroledUsers = [];
    public string $nameSearch = '';
    public string $roleFilter = '';

    public function mount(Module $module)
    {
        $this->module = $module;
    }

    public function enrol(int $userID)
    {
        Gate::authorize('enrol', $this->module);
        $this->module->users()->syncWithoutDetaching($userID);
    }

    public function unenrol(int $userID)
    {
        Gate::authorize('enrol', $this->module);
        $this->module->users()->detach($userID);
    }

    public function render()
    {
        // Get the users based on email -- assumption all users use an official uni email. If there's a role filter apply that as well.
        $this->users = User::query()->when($this->nameSearch, fn($query) => $query->where('email','like',"%{$this->nameSearch}@swansea.test%"))
        ->when($this->roleFilter, fn($query)=>$query->whereHas('roles', fn($roles)=>$roles->where('name', $this->roleFilter)))
        ->orderBy('email')
        ->get();

        // Get the users enrolled on the module currently to display enrol or unenrol option
        $this->enroledUsers = $this->module->users()->pluck('users.id')->toArray();

        //TODO fix bug where once enrol is clicked the new unenrol button cannot be fixed.
        return view('livewire.module-enrolment-manager');
    }
}
