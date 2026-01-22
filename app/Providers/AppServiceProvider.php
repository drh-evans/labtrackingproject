<?php

namespace App\Providers;

use App\Policies\LabTaskPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\LabTask;

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
        Gate::policy(LabTask::class, LabTaskPolicy::class);

    }
}
