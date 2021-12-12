<?php

namespace JasperTey\ActivityFeed;

use Illuminate\Support\ServiceProvider;

class ActivityFeedServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'vio');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'vio');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/activityfeed.php', 'activityfeed');

        // Register the service the package provides.
        $this->app->singleton('activityfeed', function ($app) {
            return new ActivityFeed;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['activityfeed'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/activityfeed.php' => config_path('activityfeed.php'),
        ], 'activityfeed.config');

        // Migrations
        if (!class_exists('CreateActivityFeedTables')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/create_activity_feed_tables.php.stub'
                => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_activity_feed_tables.php'),
            ], 'db');
        }

        // Registering package commands.
        $this->commands([

        ]);
    }
}
