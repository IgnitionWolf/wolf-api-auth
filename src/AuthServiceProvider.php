<?php

namespace IgnitionWolf\API\Auth;

use IgnitionWolf\API\Auth\Commands\InstallCommand;
use IgnitionWolf\API\Auth\Support\Stub;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register all modules.
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/config.php';
        $this->mergeConfigFrom($configPath, 'api-auth');
        $this->publishes([
            $configPath => config_path('api-auth.php'),
        ], 'config');

        $path = $this->app['config']->get('api-auth.stubs.path') ?? __DIR__ . '/Commands/stubs';
        Stub::setBasePath($path);

        $this->commands([InstallCommand::class]);
    }
}
