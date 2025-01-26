<?php

namespace Albarslan01\CurrencyToString;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class CurrencyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // $this->publishes([
        //     __DIR__.'/../config/currency.php' => config_path('currency.php'),
        // ], 'config');


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
            ->get('/currency-to-string', '\Albarslan01\CurrencyToString\Controllers\CurrencyConvert@currencyFormat')
            ->name('currency.format');
    }
}