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
        $this->users = User::query()->when($this->nameSearch, fn($query) => $query->where('email','like',"%{$this->nameSearch}@swansea.test%"))
        ->when($this->roleFilter, fn($query)=>$query->whereHas('roles', fn($roles)=>$roles->where('name', $this->roleFilter)))
        ->orderBy('email')
        ->get();

        $this->enroledUsers = $this->module->users()->pluck('users.id')->toArray();

        return view('livewire.module-enrolment-manager');
    }
}
