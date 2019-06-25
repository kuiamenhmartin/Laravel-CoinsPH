<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\Passport;

use App\Services\User\SendConfirmationEmailService;
use App\Services\User\SendConfirmationEmailInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //https://code.tutsplus.com/tutorials/how-to-register-use-laravel-service-providers--cms-28966
        $this->app->bind(SendConfirmationEmailInterface::class, SendConfirmationEmailService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        // Passport::$ignoreCsrfToken = true;
    }
}
