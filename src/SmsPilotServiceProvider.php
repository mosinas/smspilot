<?php

namespace Mosinas\SmsPilot;

use Illuminate\Support\ServiceProvider;

class SmsPilotServiceProvider extends ServiceProvider
{
    protected $configName = 'smspilot';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/config/' . $this->configName . '.php';
        $this->publishes([
            $configPath => config_path($this->configName . '.php')
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('smspilot', function () {
            return new SmsPilot();
        });
    }

    
}