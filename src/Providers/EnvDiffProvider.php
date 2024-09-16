<?php

declare(strict_types=1);

namespace AmjadBM\EnvDiff\Providers;

use AmjadBM\EnvDiff\Console\Commands\DiffEnvFiles;
use Illuminate\Support\ServiceProvider;

class EnvDiffProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '/../config/env-diff.php' => config_path('env-diff.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                DiffEnvFiles::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/../config/env-diff.php',
            'env-diff'
        );
    }
}
