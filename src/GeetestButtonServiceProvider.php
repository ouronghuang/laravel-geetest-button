<?php

namespace Ouronghuang\GeetestButton;

use Illuminate\Support\ServiceProvider;

class GeetestButtonServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/geetest.php', 'geetest'
        );

        $this->app->singleton(GeetestButton::class, function () {
            return new GeetestButton();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/geetest.php' => config_path('geetest.php'),
        ], 'geetest');
    }
}
