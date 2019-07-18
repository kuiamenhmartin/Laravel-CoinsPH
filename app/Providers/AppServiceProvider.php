<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\Passport;

use App\Services\User\Auth\Registration\SendConfirmationEmailService;
use App\Services\User\Auth\ResetPassword\SendResetPasswordRequestEmail;
use App\Services\User\Auth\ResetPassword\SendResetPasswordSuccessEmail;
use App\Services\User\Auth\Registration\SendConfirmationEmailServiceInterface;
use App\Services\User\Auth\ResetPassword\SendResetPasswordRequestEmailInterface;
use App\Services\User\Auth\ResetPassword\SendResetPasswordSuccessEmailInterface;

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
        $this->app->bind(SendConfirmationEmailServiceInterface::class, SendConfirmationEmailService::class);
        $this->app->bind(SendResetPasswordRequestEmailInterface::class, SendResetPasswordRequestEmail::class);
        $this->app->bind(SendResetPasswordSuccessEmailInterface::class, SendResetPasswordSuccessEmail::class);
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
