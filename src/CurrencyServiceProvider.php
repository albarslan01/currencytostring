<?php

namespace Albarslan01\CurrencyToString;

use Illuminate\Support\ServiceProvider;
use Albarslan01\CurrencyToString\Middleware\CurrencyMiddleware;
use Illuminate\Support\Facades\Route;

class CurrencyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // $this->publishes([
        //     __DIR__.'/../config/currency.php' => config_path('currency.php'),
        // ], 'config');

        // $this->app['router']->pushMiddlewareToGroup('web', CurrencyMiddleware::class);
        // $this->app['router']->pushMiddlewareToGroup('api', CurrencyMiddleware::class);

        $this->registerRoutes();
    }

    public function register()
    {
        // $this->mergeConfigFrom(
        //     __DIR__.'/../config/currency.php', 'currency'
        // );
    }

    private function registerRoutes()
    {
        Route::middleware('web')
            ->get('/invoices-products', '\Albarslan01\CurrencyToString\Controllers\CurrencyConvert@changeFormat')
            ->name('currency.export');
    }
}