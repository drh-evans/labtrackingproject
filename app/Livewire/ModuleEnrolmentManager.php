<?php

namespace App\Livewire;

use App\Notifications\ModuleEnrollmentNotification;
use App\Notifications\RemovedFromModuleNotification;
use Livewire\Component;
use App\Models\Module;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\WithPagination;

class ModuleEnrolmentManager extends Component
{
    use WithPagination;
    public Module $module;
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
        $user = User::findOrFail($userID);
        $user->notify(new ModuleEnrollmentNotification($this->module));
    }

    public function unenrol(int $userID)
    {
        Gate::authorize('enrol', $this->module);
        $this->module->users()->detach($userID);
        $user = User::findOrFail($userID);
        $user->notify(new RemovedFromModuleNotification($this->module));
    }

    // reset the page whenever the name is updated to prevent getting stuck
    public function updatingNameSearch() {
        $this->resetPage();
    }

    // reset the page whenever the name is updated to prevent getting stuck
    public function updatingRoleFilter() {
        $this->resetPage();
    }

    public function render()
    {
        // Get the users based on name or email -- assume all users use an official uni email. If there's a role filter apply that as well.
        $users = User::query()->when($this->nameSearch, fn($query) => $query->where(function($q) {
            $q->where('email','like',"%{$this->nameSearch}@swansea.test%")
                ->orWhere('name','like', "%{$this->nameSearch}%");
        }))
        ->when($this->roleFilter, fn($query)=>$query->whereHas('roles', fn($roles)=>$roles->where('name', $this->roleFilter)))
        ->orderBy('name')
        ->paginate(6);

        // Get the users enrolled on the module currently to display enrol or unenrol option
        $this->enroledUsers = $this->module->users()->pluck('users.id')->toArray();

        return view('livewire.module-enrolment-manager', ['users'=>$users]);
    }
}
