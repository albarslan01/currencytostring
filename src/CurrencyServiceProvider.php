<?php

namespace Albarslan01\CurrencyToString;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class CurrencyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerRoutes();
    }

    public function register()
    {

    }

    private function registerRoutes()
    {
        Route::middleware('web')
            ->get('/currency-to-string', '\Albarslan01\CurrencyToString\Controllers\CurrencyConvert@currencyFormat')
            ->name('currency.format');
    }
}