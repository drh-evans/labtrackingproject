<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Module;
use App\Models\LabTask;

class SignOffManager extends Component
{
    public Module $module;
    public User $student;
    public $signedOffBy;
    public LabTask $labTask;

    public function mount()
    {

    }
    public function render()
    {
        return view('livewire.sign-off-manager');
    }
}
