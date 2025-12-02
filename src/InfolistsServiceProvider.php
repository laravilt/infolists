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
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            // Register commands
            $this->commands([
                Commands\MakeInfolistCommand::class,
            ]);
        }
    }
}
