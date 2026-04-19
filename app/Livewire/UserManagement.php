<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class UserManagement extends Component
{
    use WithPagination;

    public $userSearch = '';
    public $roleFilter = '';

    // reset the page whenever the search is updated to prevent getting stuck
    public function updatingUserSearch() {
        $this->resetPage();
    }

    public function updatingRoleFilter() {
        $this->resetPage();
    }
    public function deleteUser(int $userId) {
        $user = User::findOrFail($userId);
        Gate::authorize('delete', $user);
        $user->delete();
    }
    public function render()
    {
        // Get the users based on name or email -- assume all users use an official uni email. If there's a role filter apply that as well.
        $users = User::query()->when($this->userSearch, fn($query) => $query->where(function($q) {
            $q->where('email','like',"%{$this->userSearch}@swansea.test%")
                ->orWhere('name','like', "%{$this->userSearch}%");
        }))
        ->when($this->roleFilter, fn($query)=>$query->whereHas('roles', fn($roles)=>$roles->where('name', $this->roleFilter)))
        ->orderBy('name')
        ->paginate(6);
        return view('livewire.user-management', ['users'=>$users]);
    }
}
