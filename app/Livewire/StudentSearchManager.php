<?php

namespace App\Livewire;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\LabTaskUser;

use App\Models\User;
use App\Models\Module;
use App\Models\LabTask;


// Search students within a module to access their page

class StudentSearchManager extends Component
{

    public Module $module;
    public string $nameSearch = '';

    public function mount(Module $module)
    {
        $this->module = $module;
    }

    public function getStudentsProperty() 
    {
        return $this->module->users()->when($this->nameSearch, fn($query) => $query->where('email','like',"%{$this->nameSearch}%"))
        ->when( fn($query)=>$query->whereHas('roles', fn($role)=>$role->where('name', 'Student')))
        ->orderBy('name')
        ->get();
    }

    public function render()
    {
        return view('livewire.student-search-manager', ['students'=>$this->students,]);
    }
}
