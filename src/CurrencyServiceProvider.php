<?php

namespace Albarslan01\CurrencyToString;

use Illuminate\Support\ServiceProvider;
use Albarslan01\CurrencyToString\Middleware\CurrencyMiddleware;

class CurrencyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/currency.php' => config_path('currency.php'),
        ], 'config');

        $this->app['router']->pushMiddlewareToGroup('web', CurrencyMiddleware::class);
        $this->app['router']->pushMiddlewareToGroup('api', CurrencyMiddleware::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/currency.php', 'currency'
        );
    }
}
