<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Permission;

use Gate;

class PermissionsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Permission::get()->map(function ($permission) {
        //     Gate::define($permission->slug, function ($user) use ($permission) {
        //         return $user->hasPermissionTo($permission);
        //     });
        //
        // });
    }
}
