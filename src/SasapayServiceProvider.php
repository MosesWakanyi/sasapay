<?php

namespace Mosesgathecha\Sasapay;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Mosesgathecha\Sasapay\Auth\SasapayBase;
use Mosesgathecha\Sasapay\Transactions\SasapayTransaction;

class SasapayServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../config/sasapay.php' => config_path('sasapay.php'),
            ], 'config');
        }
    }
    public function register()
    {
        $this->app->singleton(SasapayBase::class, function ($app) {
            return new SasapayBase(new Client);
        });
        $this->app->bind('sasapay-app', function () {
            return $this->app->make(SasapayTransaction::class);
        });
    }
}
