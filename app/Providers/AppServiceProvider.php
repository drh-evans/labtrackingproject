<?php

namespace App\Providers;

use App\Policies\LabTaskPolicy;
use App\Policies\ModulePolicy;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\LabTask;
use App\Models\Module;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('access-admin', fn(User $user) => $user->hasRole('Admin'));
        Gate::define('access-enrolment', fn(User $user) => $user->hasRole('Admin') || $user->hasRole('Lecturer'));
        Gate::define('access-staff', fn(User $user) => $user->hasRole('Admin') || $user->hasRole('Lecturer') || $user->hasRole('Teaching Assistant'));
        Gate::policy(LabTask::class, LabTaskPolicy::class);
        Gate::policy(Module::class, ModulePolicy::class);


    }
}
