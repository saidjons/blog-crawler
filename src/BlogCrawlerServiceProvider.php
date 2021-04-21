<?php

namespace Saidjons\BlogCrawler;

use Illuminate\Support\ServiceProvider;

class BlogCrawlerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'blog-crawler');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'blog-crawler');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('blog-crawler.php'),
            ], 'config');
    require_once(__DIR__.'/Helper/helper.php');
            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
            $this->commands([
            Saidjons\BlogCrawler\Commands\GetNews::class
        ]);

        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'blog-crawler');

        // Register the main class to use with the facade
        $this->app->singleton('blog-crawler', function () {
            return new BlogCrawler;
        });
    }
}
