<?php

namespace Afrittella\LaravelPages;

use Afrittella\LaravelRepository\LaravelRepositoryServiceProvider;
use GrahamCampbell\Markdown\MarkdownServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class PagesProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot(Router $router)
    {
        $this->loadMigrationsFrom(
            \dirname(__DIR__, 1) . '/database/migrations'
        );

        $this->publishFiles();
    }

    public function register()
    {
        $config = $this->app->config['pages'];

        // use the vendor configuration file as fallback
        $this->mergeConfigFrom(
            __DIR__ . '/../config/pages.php', 'pages'
        );

        $this->app->register(LaravelRepositoryServiceProvider::class);
        $this->app->register(MarkdownServiceProvider::class);
    }

    protected function publishFiles()
    {

        // publish config file
        $this->publishes([__DIR__ . '/../config/pages.php' => config_path() . '/pages.php'], 'config');

        // publish migrations
        $this->publishes([__DIR__ . '/../database/migrations/' => database_path('migrations')], 'migrations');
    }
}