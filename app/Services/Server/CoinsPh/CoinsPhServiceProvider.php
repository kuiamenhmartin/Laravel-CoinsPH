<?php

namespace App\Repositories;

use Illuminate\Support\ServiceProvider;

use App\Services\Server\CoinsPh\Adapters\CashInService;

class CoinsPhServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'App\Services\Server\CoinsPh\Interfaces\CashInInterface',
            function($app)
            {
              return new CashInService();
            }
        );
    }
}
