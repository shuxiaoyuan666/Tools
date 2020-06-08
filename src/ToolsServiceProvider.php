<?php

namespace Ecrm\Tools;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class ToolsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $router->aliasMiddleware('tools', \Ecrm\Tools\Middleware\ToolsMiddleware::class);

        $this->publishes([
            __DIR__.'/Config/tools.php' => config_path('tools.php'),
        ], 'tools_config');

        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/Translations', 'tools');

        $this->publishes([
            __DIR__ . '/Translations' => resource_path('lang/vendor/tools'),
        ]);

        $this->loadViewsFrom(__DIR__ . '/Views', 'tools');

        $this->publishes([
            __DIR__ . '/Views' => resource_path('views/vendor/tools'),
        ]);

        $this->publishes([
            __DIR__ . '/Assets' => public_path('vendor/tools'),
        ], 'tools_assets');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Ecrm\Tools\Commands\ToolsCommand::class,
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
            __DIR__ . '/Config/tools.php', 'tools'
        );
    }
}
