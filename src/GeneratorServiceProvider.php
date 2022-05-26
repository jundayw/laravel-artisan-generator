<?php

namespace Jundayw\LaravelArtisanGenerator;

use Illuminate\Support\ServiceProvider;
use Jundayw\LaravelArtisanGenerator\Console\GeneratorCommand;
use Jundayw\LaravelArtisanGenerator\Console\GeneratorControllerCommand;
use Jundayw\LaravelArtisanGenerator\Console\GeneratorModelCommand;
use Jundayw\LaravelArtisanGenerator\Console\GeneratorRepositoryCommand;
use Jundayw\LaravelArtisanGenerator\Console\GeneratorRequestCommand;
use Jundayw\LaravelArtisanGenerator\Console\GeneratorViewCommand;

class GeneratorServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            GeneratorCommand::class,
            GeneratorControllerCommand::class,
            GeneratorModelCommand::class,
            GeneratorRepositoryCommand::class,
            GeneratorRequestCommand::class,
            GeneratorViewCommand::class,
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/generator.php', 'generator');
        $this->loadViewsFrom(__DIR__ . '/../resources/stubs', 'artisan-generator-stubs');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'artisan-generator-views');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/generator.php' => config_path('generator.php'),
            ], 'artisan-generator-config');
            $this->publishes([
                __DIR__ . '/../resources/stubs' => config('generator.resource.stubs'),
                __DIR__ . '/../resources/views' => config('generator.resource.views'),
            ], 'artisan-generator-views');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
