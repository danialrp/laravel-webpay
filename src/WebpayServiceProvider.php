<?php

namespace DanialPanah\WebPay;

use Illuminate\Support\ServiceProvider;

class WebpayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('webpay', function ($app) {
            return new Webpay();
        });

        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'webpay');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('webpay.php'),
            ], 'config');
        }
    }
}