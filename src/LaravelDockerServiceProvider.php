<?php

namespace MeeeetDev\LaravelDocker;

use Illuminate\Support\ServiceProvider;

class LaravelDockerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-docker');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-docker');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../docker' => base_path('docker'),
            ], 'laravel-docker-config');

            // Publishing the views.
            // $this->publishes([
            //     __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-docker'),
            // ], 'views');

            // Publishing assets.
            // $this->publishes([
            //     __DIR__.'/../resources/assets' => public_path('vendor/laravel-docker'),
            // ], 'assets');

            // Publishing the translation files.
            // $this->publishes([
            //     __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-docker'),
            // ], 'lang');

            // Registering package commands.
             $this->commands([
                LaravelDocker::class
             ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        // $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-docker');

        // Register the main class to use with the facade
        // $this->app->singleton('laravel-docker', function () {
        //     return new LaravelDocker;
        // });
    }
}
