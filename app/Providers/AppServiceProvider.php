<?php

namespace App\Providers;

use App\Observers\ModuleObserver;
use App\Policies\LabTaskPolicy;
use App\Policies\ModulePolicy;
use App\Policies\UserPolicy;

use App\Services\CSVService;
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
        // binding for CSV service
        app()->singleton(CSVService::class, function ($app) {
            return new CSVService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('access-admin', fn(User $user) => $user->hasRole('Admin'));
        Gate::define('access-enrolment', fn(User $user) => $user->hasRole('Admin') || $user->hasRole('Lecturer'));
        Gate::define('access-staff', fn(User $user) => $user->hasRole('Admin') || $user->hasRole('Lecturer') || $user->hasRole('Teaching Assistant'));
        Gate::define('join-queue', fn(User $user) => $user->hasRole('Student'));
        Gate::define('access-analytics', [ModulePolicy::class, 'accessAnalytics']);
        Gate::policy(LabTask::class, LabTaskPolicy::class);
        Gate::policy(Module::class, ModulePolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        Module::observe(ModuleObserver::class);
    }
}
