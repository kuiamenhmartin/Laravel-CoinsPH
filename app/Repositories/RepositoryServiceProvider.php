<?php

namespace App\Repositories;

use Illuminate\Support\ServiceProvider;
use App\Repositories\User\UserRepository;

//Models
use App\User;

class RepositoryServiceProvider extends ServiceProvider
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
