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
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {



        Gate::define('admin', function (User $user) {
            return $user->role === 'administrator';
        });


        Gate::define('mahasiswa', function (User $user) {
            return $user->role === 'calon_mahasiswa';
        });


        Gate::define('role', function (User $user, ...$roles) {
            return in_array($user->role, $roles);
        });
    }
}
