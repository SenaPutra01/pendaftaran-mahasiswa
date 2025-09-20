<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        // $this->registerPolicies();

        // Gate untuk admin
        Gate::define('admin', function (User $user) {
            return $user->role === 'administrator';
        });

        // Gate untuk mahasiswa
        Gate::define('mahasiswa', function (User $user) {
            return $user->role === 'calon_mahasiswa';
        });

        // Gate untuk multiple roles
        Gate::define('role', function (User $user, ...$roles) {
            return in_array($user->role, $roles);
        });
    }
}
