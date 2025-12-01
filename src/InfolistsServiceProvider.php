<?php

namespace Laravilt\Infolists;

use Illuminate\Support\ServiceProvider;

class InfolistsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravilt-infolists.php',
            'laravilt-infolists'
        );

        // Register any services, bindings, or singletons here
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'infolists');


        // Load web routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');


        if ($this->app->runningInConsole()) {
            // Publish config
            $this->publishes([
                __DIR__ . '/../config/laravilt-infolists.php' => config_path('laravilt-infolists.php'),
            ], 'laravilt-infolists-config');

            // Publish assets
            $this->publishes([
                __DIR__ . '/../dist' => public_path('vendor/laravilt/infolists'),
            ], 'laravilt-infolists-assets');


            // Register commands
            $this->commands([
                Commands\InstallInfolistsCommand::class,
            ]);
        }
    }
}
