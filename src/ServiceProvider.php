<?php

namespace Stri8ed\LaravelConfigJs;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    protected $config = __DIR__.'/../config/config-js.php';

    public function register(): void
    {
        $this->mergeConfigFrom($this->config, 'config-js');
    }

    public function boot(): void
    {
        $this->publishes([
            $this->config => config_path('config-js.php')
        ], 'config-js');

        Blade::directive('configJs', [Directive::class, 'compile']);
    }
}