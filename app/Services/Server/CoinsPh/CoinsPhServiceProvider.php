<?php

namespace App\Repositories;

use Illuminate\Support\ServiceProvider;

class CoinsPhServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'App\Repositories\DefaultRepositoryInterface',
            function($app)
            {
              return new UserRepository(new User);
            }
        );
    }
}
